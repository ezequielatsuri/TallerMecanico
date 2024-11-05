@extends('layouts.app')

@section('title', 'Servicios')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

@include('layouts.partials.alert')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Servicios</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Servicios</li>
    </ol>

    @can('crear-servicio')
    <div class="mb-4">
        <a href="{{ route('servicios.create') }}">
            <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
        </a>
    </div>
    @endcan

    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Tabla de servicios
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table-striped fs-6">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($servicios as $servicio)
                <tr>
                    <td>{{ $servicio->nombre }}</td>
                    <td>{{ $servicio->descripcion }}</td>
                    <td>{{ $servicio->precio }}</td>
                    <td>
                        @if ($servicio->estado == 1)
                        <span class="badge rounded-pill text-bg-success">Activo</span>
                        @else
                        <span class="badge rounded-pill text-bg-danger">Suspendido</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-around">
                            @can('editar-servicio')
                            <a href="{{ route('servicios.edit', ['servicio' => $servicio->id]) }}" class="btn btn-datatable btn-icon btn-transparent-dark">
                                <svg class="svg-inline--fa fa-pencil-alt" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="pencil-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M497.9 74.16L437.8 14.06c-18.75-18.75-49.14-18.75-67.88 0L43.94 340c-2.75 2.75-5 5.94-6.69 9.5L0 484c-2.81 10.94 .94 22.56 10.06 29.56 7.06 5.5 17 6.88 25.88 3.5l134.44-37.44c3.56-1.69 6.75-3.94 9.5-6.69L497.9 142c18.75-18.75 18.75-49.12 0-67.84zM64.07 446.94c-1.5 1.5-3.5 1.88-5.25 1.5l-92.25 25.69 25.69-92.25c.38-1.75 .06-3.75-1.44-5.25l-21.25-21.25 306.06-306.12 115.31 115.31L64.07 446.94zM432.3 144.6l-90.5-90.56L412.2 73.9c3.12-3.12 8.19-3.12 11.31 0l21.25 21.25c3.12 3.12 3.12 8.19 0 11.31l-12.46 12.44z"></path>
                                </svg>
                            </a>
                            @endcan
                            <div><div class="vr"></div></div>
                            <div>
                                @can('eliminar-servicio')
                                @if ($servicio->estado == 1)
                                <button title="Eliminar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $servicio->id }}" class="btn btn-datatable btn-icon btn-transparent-dark">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                                @else
                                <button title="Restaurar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $servicio->id }}" class="btn btn-datatable btn-icon btn-transparent-dark">
                                    <i class="fa fa-sync"></i>
                                </button>
                                @endif
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="confirmModal-{{ $servicio->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                {{ $servicio->estado == 1 ? '¿Seguro que quieres eliminar el servicio?' : '¿Seguro que quieres restaurar el servicio?' }}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <form action="{{ route('servicios.destroy', ['servicio' => $servicio->id]) }}" method="post">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Confirmar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
