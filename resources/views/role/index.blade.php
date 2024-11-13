@extends('layouts.app')

@section('title','Roles')
@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush
@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .action-icon {
        color: #555;
        font-size: .8rem; /* Ajuste del tamaño del icono */
        margin: 0 5px;
        cursor: pointer;
        background: none; /* Eliminar fondo */
        border: none; /* Eliminar bordes */
        padding: 0; /* Eliminar relleno */
    }
    .action-divider {
        display: inline-block;
        width: 1px;
        height: 1rem;
        background-color: #ddd;
        margin: 0 5px;
    }
</style>
@endpush

@section('content')

@include('layouts.partials.alert')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Roles</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Roles</li>
    </ol>

    @can('crear-role')
    <div class="mb-4">
        <a href="{{route('roles.create')}}">
            <button type="button" class="btn btn-primary">Agregar nuevo rol</button>
        </a>
    </div>
    @endcan

    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Lista roles
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped fs-6">
                <thead>
                    <tr>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        
                        <td>
                            <div class="d-flex align-items-center">
                                @can('editar-role')
                                <a href="{{route('roles.edit',['role'=>$item])}}" class="action-icon" title="Editar">
                                    <i class="fas fa-pen"></i> <!-- Ícono de edición -->
                                </a>
                                @endcan
                                
                                <span class="action-divider"></span>
                                
                                @can('eliminar-role')
                                <button type="button" class="action-icon" title="Eliminar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}">
                                    <i class="fas fa-trash"></i> <!-- Ícono de eliminación -->
                                </button>
                                @endcan
                            </div>
                        </td>
                        
                    </tr>

                    <!-- Modal de confirmación-->
                    <div class="modal fade" id="confirmModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Seguro que quieres eliminar el rol?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('roles.destroy',['role'=>$item->id]) }}" method="post">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dataTable = new simpleDatatables.DataTable("#datatablesSimple", {
            perPageSelect: [10, 25, 50, 100]  // Opciones de cantidad de registros para mostrar
        });
    });
</script>
@endpush
