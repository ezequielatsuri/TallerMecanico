<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVentaRequest;
use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Venta;
use Exception;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
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
        $subquery = DB::table('compra_producto')
            ->select('producto_id', DB::raw('MAX(created_at) as max_created_at'))
            ->groupBy('producto_id');

        $productos = Producto::join('compra_producto as cpr', function ($join) use ($subquery) {
            $join->on('cpr.producto_id', '=', 'productos.id')
                ->whereIn('cpr.created_at', function ($query) use ($subquery) {
                    $query->select('max_created_at')
                        ->fromSub($subquery, 'subquery')
                        ->whereRaw('subquery.producto_id = cpr.producto_id');
                });
        })
            ->select('productos.nombre', 'productos.id', 'productos.stock', 'cpr.precio_venta')
            ->where('productos.estado', 1)
            ->where('productos.stock', '>', 0)
            ->get();

        $clientes = Cliente::whereHas('persona', function ($query) {
            $query->where('estado', 1);
        })->get();

        $comprobantes = Comprobante::all();
        $servicios = Servicio::where('estado', 1)->get();

        return view('venta.create', compact('productos', 'clientes', 'comprobantes', 'servicios'));
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

            // Guardar servicios de la venta
            $arrayServicio_id = $request->get('arrayidservicio');
            $arrayPrecioServicio = $request->get('arrayprecioservicio');
            $arrayDescuentoServicio = $request->get('arraydescuentoservicio');
            if ($arrayServicio_id) {
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
            return redirect()->route('ventas.index')->withErrors('Error al registrar la venta.');
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
}
