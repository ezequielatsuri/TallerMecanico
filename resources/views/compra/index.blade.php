@extends('layouts.app')

@section('title','compras')
@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush
@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .row-not-space {
        width: 110px;
    }
    /* Ajuste para que solo los iconos aparezcan en los botones sin texto */
    .btn-icon-only {
        padding: 5px;
        width: 40px;
        height: 40px;
        background-color: transparent;
        border: none;
    }
    /* Color gris claro para los iconos */
    .btn-icon-only i {
        color: #6c757d; /* Gris claro */
    }
    /* Estilo para el separador vertical */
    .icon-separator {
        width: 1px;
        height: 20px;
        background-color: #ccc; /* Gris claro para el separador */
        margin: 0 5px;
        display: inline-block;
    }
</style>
@endpush

@section('content')

@include('layouts.partials.alert')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Compras</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Compras</li>
    </ol>

    @can('crear-compra')
    <div class="mb-4">
        <a href="{{ route('compras.create') }}">
            <button type="button" class="btn btn-primary">Agregar nuevo registro</button>
        </a>
    </div>
    @endcan

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla compras
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>Comprobante</th>
                        <th>Proveedor</th>
                        <th>Fecha y hora</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($compras as $item)
                    <tr>
                        <td>
                        <p class="fw-semibold mb-1">{{$item->comprobante->tipo_comprobante ?? 'Comprobante no disponible'}}</p>
                        <p class="text-muted mb-0">{{ $item->numero_comprobante }}</p>
                        </td>
                        <td>
                            <p class="fw-semibold mb-1">{{ ucfirst($item->proveedore->persona->tipo_persona) }}</p>
                            <p class="text-muted mb-0">{{ $item->proveedore->persona->razon_social }}</p>
                        </td>
                        <td>
                            <div class="row-not-space">
                                <p class="fw-semibold mb-1"><span class="m-1"><i class="fa-solid fa-calendar-days"></i></span>{{ \Carbon\Carbon::parse($item->fecha_hora)->format('d-m-Y') }}</p>
                                <p class="fw-semibold mb-0"><span class="m-1"><i class="fa-solid fa-clock"></i></span>{{ \Carbon\Carbon::parse($item->fecha_hora)->format('H:i') }}</p>
                            </div>
                        </td>
                        <td>
                            {{ $item->total }}
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                @can('mostrar-compra')
                                <form action="{{ route('compras.show', ['compra' => $item]) }}" method="get" style="display: inline;">
                                    <button type="submit" class="btn btn-icon-only">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </form>
                                @endcan

                                <span class="icon-separator"></span>

                                @can('imprimir-compra')
                                <form action="{{ route('compras.print', ['compra' => $item]) }}" method="get" target="_blank" style="display: inline;">
                                    <button type="submit" class="btn btn-icon-only">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </form>
                                @endcan

                                <span class="icon-separator"></span>

                                @can('eliminar-compra')
                                <button type="button" class="btn btn-icon-only" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $item->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>

                    <!-- Modal de confirmación-->
                    <div class="modal fade" id="confirmModal-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Seguro que quieres eliminar el registro?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('compras.destroy', ['compra' => $item->id]) }}" method="post">
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
