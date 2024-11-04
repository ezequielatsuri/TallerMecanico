<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Http\Requests\StoreServicioRequest;
use App\Http\Requests\UpdateServicioRequest;
use Exception;
use Illuminate\Support\Facades\DB;

class ServicioController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-servicio|crear-servicio|editar-servicio|eliminar-servicio', ['only' => ['index']]);
        $this->middleware('permission:crear-servicio', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-servicio', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-servicio', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicios = Servicio::latest()->get();
        return view('servicio.index', compact('servicios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('servicio.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServicioRequest $request)
    {
        try {
            DB::beginTransaction();
            $servicio = new Servicio();
            $servicio->fill([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio
            ]);
            $servicio->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Error al registrar el servicio');
        }

        return redirect()->route('servicios.index')->with('success', 'Servicio registrado');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Servicio $servicio)
    {
        return view('servicio.edit', compact('servicio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServicioRequest $request, Servicio $servicio)
    {
        try {
            DB::beginTransaction();
            $servicio->fill([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
            ]);
            $servicio->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Error al editar el servicio');
        }

        return redirect()->route('servicios.index')->with('success', 'Servicio editado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Servicio $servicio)
    {
        $message = '';
        if ($servicio->estado == 1) {
            $servicio->update(['estado' => 0]);
            $message = 'Servicio eliminado';
        } else {
            $servicio->update(['estado' => 1]);
            $message = 'Servicio restaurado';
        }

        return redirect()->route('servicios.index')->with('success', $message);
    }
}
