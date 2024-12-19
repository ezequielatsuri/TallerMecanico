<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreVentaRequest;
use Illuminate\Http\Request; // Importa la clase correcta
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\Producto;
use App\Models\Servicio;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class VentaController extends Controller
{
    public $timestamps = true;

    function __construct()
    {
        $this->middleware('permission:ver-venta|crear-venta|mostrar-venta|eliminar-venta', ['only' => ['index']]);
        $this->middleware('permission:crear-venta', ['only' => ['create', 'store']]);
        $this->middleware('permission:mostrar-venta', ['only' => ['show']]);
        $this->middleware('permission:eliminar-venta', ['only' => ['destroy']]);
    }

    public function index()
    {
        $ventas = Venta::with(['comprobante','cliente.persona','user'])
            ->where('estado', 1)
            ->latest()
            ->get();

        return view('venta.index', compact('ventas'));
    }

    public function create()
{
    // Obtener productos activos con stock > 0 y cargar la última compra
    $productos = Producto::with(['ultimaCompraProducto'])
        ->where('estado', 1)
        ->where('stock', '>', 0)
        ->get();

    // Verifica si hay duplicados en $productos
    $productos = $productos->unique('id'); // Esto elimina duplicados por 'id'

    // Obtener clientes, comprobantes y servicios activos
    $clientes = Cliente::whereHas('persona', function ($query) {
        $query->where('estado', 1);
    })->get();

    $comprobantes = Comprobante::all();
    $servicios = Servicio::where('estado', 1)->get();

    return view('venta.create', compact('productos', 'clientes', 'comprobantes', 'servicios'));
}



public function filtrarVentas(Request $request)
{
    $fecha = $request->query('fecha');

    if (!$fecha) {
        return response()->json(['error' => 'Fecha no proporcionada'], 400);
    }

    $ventas = Venta::with(['cliente.persona', 'comprobante', 'user', 'servicios'])
        ->whereDate('fecha_hora', $fecha)
        ->where('estado', 1)
        ->get();

    $ventasFiltradas = $ventas->map(function ($venta) {
        $totalServicios = 0;
        $impuesto = 16;

        foreach ($venta->servicios as $servicio) {
            $precioServicio = ($servicio->pivot->precio ?? 0) - ($servicio->pivot->descuento ?? 0);
            $igv = $precioServicio * $impuesto / 100;
            $totalServicios += $precioServicio + $igv;
        }

        $totalVenta = ($venta->total ?? 0) + $totalServicios;

        return [
            'id' => $venta->id,
            'comprobante' => optional($venta->comprobante)->tipo_comprobante ?? 'No disponible',
            'numero_comprobante' => $venta->numero_comprobante,
            'cliente' => optional(optional($venta->cliente)->persona)->razon_social ?? 'No disponible',
            'fecha' => \Carbon\Carbon::parse($venta->fecha_hora)->format('d-m-Y H:i'),
            'vendedor' => optional($venta->user)->name ?? 'No disponible',
            'total' => number_format($totalVenta, 2),
            'acciones' => [
                'ver' => route('ventas.show', $venta->id),
                'imprimir' => route('ventas.imprimir', $venta->id),
                'eliminar' => route('ventas.destroy', $venta->id),
                'can_ver' => auth()->user()->can('mostrar-venta'),
                'can_imprimir' => auth()->user()->can('imprimir-venta'),
                'can_eliminar' => auth()->user()->can('eliminar-venta'),
            ],
        ];
    });

    return response()->json($ventasFiltradas);
}

    public function store(StoreVentaRequest $request)
{
    try {
        DB::beginTransaction();

        // Crear la venta
        $venta = Venta::create($request->validated());

        // Guardar productos de la venta
        $arrayProducto_id = $request->get('arrayidproducto');
        $arrayCantidad = $request->get('arraycantidad');
        $arrayPrecioVenta = $request->get('arrayprecioventa');
        $arrayDescuento = $request->get('arraydescuento');

        // Validar si hay productos para agregar
        if (!is_null($arrayProducto_id) && is_array($arrayProducto_id)) {
            $siseArray = count($arrayProducto_id);
            for ($i = 0; $i < $siseArray; $i++) {
                $venta->productos()->syncWithoutDetaching([
                    $arrayProducto_id[$i] => [
                        'cantidad' => $arrayCantidad[$i],
                        'precio_venta' => $arrayPrecioVenta[$i],
                        'descuento' => $arrayDescuento[$i]
                    ]
                ]);

                // Actualizar stock
                $producto = Producto::find($arrayProducto_id[$i]);
                $producto->stock -= intval($arrayCantidad[$i]);
                $producto->save();
            }
        }
                // Guardar servicios de la venta
                $arrayServicio_id = $request->get('arrayidservicio');
                $arrayPrecioServicio = $request->get('arrayprecioservicio');
                $arrayDescuentoServicio = $request->get('arraydescuentoservicio');
        
                // Validar si hay servicios para agregar
                if (!is_null($arrayServicio_id) && is_array($arrayServicio_id)) {
                    $siseArrayServicio = count($arrayServicio_id);
                    for ($j = 0; $j < $siseArrayServicio; $j++) {
                        $venta->servicios()->attach($arrayServicio_id[$j], [
                            'precio' => $arrayPrecioServicio[$j],
                            'descuento' => $arrayDescuentoServicio[$j]
                        ]);
                    }
                }
        



        DB::commit();
    } catch (Exception $e) {
        DB::rollBack();
        return redirect()->route('ventas.index')->withErrors('Error al registrar la venta. Detalle: ' . $e->getMessage());
    }

    return redirect()->route('ventas.index')->with('success', 'Venta registrada exitosamente');
}


    public function imprimirFactura($id)
    {
        $venta = Venta::with('cliente', 'comprobante', 'user')->findOrFail($id);
        return view('venta.factura', compact('venta'));
    }

    public function show(Venta $venta)
    {
        return view('venta.show', compact('venta'));
    }

    public function destroy(string $id)
    {
        Venta::where('id', $id)->update(['estado' => 0]);
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada');
    }

    public function generarReporte(Request $request)
{
    // Obtener la fecha seleccionada del filtro
    $fecha = $request->query('fecha');

    // Obtener ventas filtradas o todas
    $ventas = Venta::with(['cliente.persona', 'comprobante', 'user'])
        ->when($fecha, function ($query) use ($fecha) {
            $query->whereDate('fecha_hora', $fecha);
        })
        ->where('estado', 1)
        ->get();

    // Datos para el reporte
    $data = [
        'logo' => public_path('Recursos/Logo.png'), // Ruta al logo
        'fecha_reporte' => Carbon::now()->format('d-m-Y H:i'),
        'usuario' => Auth::user()->name,
        'ventas' => $ventas,
        'fecha_filtro' => $fecha ? "Fecha seleccionada: $fecha" : "Todas las fechas"
    ];

    // Cargar la vista y generar el PDF
    $pdf = Pdf::loadView('reporte.ventas', $data);

    // Descargar el archivo PDF
    return $pdf->download('reporte_ventas.pdf');
}


}
