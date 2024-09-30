<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCaracteristicaRequest;
use App\Http\Requests\UpdatePresentacioneRequest;
use App\Models\Caracteristica;
use App\Models\Fabricante;
use Exception;
use Illuminate\Support\Facades\DB;

class fabricanteController extends Controller
{
    function __construct()
    {
        // Middleware para verificar permisos para ver, crear, editar y eliminar fabricantes
        $this->middleware('permission:ver-fabricante|crear-fabricante|editar-fabricante|eliminar-fabricante', ['only' => ['index']]);
        $this->middleware('permission:crear-fabricante', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-fabricante', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-fabricante', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los fabricantes junto con sus características, ordenados por los más recientes
        $fabricantes = Fabricante::with('caracteristica')->latest()->get();
        return view('fabricante.index', compact('fabricantes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fabricante.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCaracteristicaRequest $request)
    {
        try {
            // Iniciar transacción
            DB::beginTransaction();
            
            // Crear una nueva característica
            $caracteristica = Caracteristica::create($request->validated());

            // Asociar la característica con un nuevo fabricante
            $caracteristica->fabricante()->create([
                'caracteristica_id' => $caracteristica->id
            ]);

            // Confirmar transacción
            DB::commit();
        } catch (Exception $e) {
            // Si ocurre un error, revertir la transacción
            DB::rollBack();
        }

        return redirect()->route('fabricantes.index')->with('success', 'Fabricante registrado');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fabricante $fabricante)
    {
        // Mostrar el formulario de edición para el fabricante
        return view('fabricante.edit', compact('fabricante'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePresentacioneRequest $request, Fabricante $fabricante)
    {
        // Actualizar la característica asociada al fabricante
        Caracteristica::where('id', $fabricante->caracteristica->id)
            ->update($request->validated());

        return redirect()->route('fabricantes.index')->with('success', 'Fabricante editado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        // Encontrar el fabricante por su ID
        $fabricante = Fabricante::find($id);

        // Cambiar el estado de la característica asociada (activar/desactivar)
        if ($fabricante->caracteristica->estado == 1) {
            Caracteristica::where('id', $fabricante->caracteristica->id)
                ->update(['estado' => 0]);
            $message = 'Fabricante eliminado';
        } else {
            Caracteristica::where('id', $fabricante->caracteristica->id)
                ->update(['estado' => 1]);
            $message = 'Fabricante restaurado';
        }

        return redirect()->route('fabricantes.index')->with('success', $message);
    }
}
