@extends('layouts.app')

@section('title','Productos')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endpush

@section('content')

@include('layouts.partials.alert')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Productos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Productos</li>
    </ol>

    @can('crear-producto')
    <div class="mb-4">
        <a href="{{route('productos.create')}}">
            <button type="button" class="btn btn-primary">Agregar nuevo registro</button>
        </a>
    </div>
    @endcan

    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Lista productos
        </div>
        <div class="card-body">
            @php
                // Array de colores predefinidos para asignar a las categorías
                $colores = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-secondary'];
                $categoriasColores = []; // Array para almacenar el color asignado a cada categoría
            @endphp
            <table id="datatablesSimple" class="table table-striped fs-6">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Fabricante</th>
                        <th>Categorías</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $item)
                    <tr>
                        <td>{{$item->codigo}}</td>
                        <td>{{$item->nombre}}</td>
                        <td>{{$item->marca->caracteristica->nombre}}</td>
                        <td>{{$item->fabricante->caracteristica->nombre}}</td>
                        <td>
                            @foreach ($item->categorias as $category)
                                @php
                                    // Si la categoría ya tiene un color asignado, lo reutilizamos
                                    if (isset($categoriasColores[$category->caracteristica->nombre])) {
                                        $colorClass = $categoriasColores[$category->caracteristica->nombre];
                                    } else {
                                        // Si no tiene color asignado, le asignamos el siguiente color disponible en el array
                                        $colorClass = $colores[count($categoriasColores) % count($colores)];
                                        $categoriasColores[$category->caracteristica->nombre] = $colorClass;
                                    }
                                @endphp
                                <div class="container" style="font-size: small;">
                                    <div class="row">
                                        <span class="m-1 rounded-pill p-1 text-white text-center {{ $colorClass }}">
                                            {{ $category->caracteristica->nombre }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </td>
                        <td>
                            @if ($item->estado == 1)
                            <span class="badge rounded-pill text-bg-success">activo</span>
                            @else
                            <span class="badge rounded-pill text-bg-danger">inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-around">
                                <!-- Editar -->
                                @can('editar-producto')
                                <a href="{{route('productos.edit',['producto' => $item])}}" title="Editar" class="btn btn-datatable btn-icon btn-transparent-dark">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan

                                <!-- Ver -->
                                @can('ver-producto')
                                <button title="Ver" class="btn btn-datatable btn-icon btn-transparent-dark" data-bs-toggle="modal" data-bs-target="#verModal-{{$item->id}}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @endcan

                                <!-- Eliminar o Restaurar -->
                                @can('eliminar-producto')
                                @if ($item->estado == 1)
                                <button title="Eliminar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}" class="btn btn-datatable btn-icon btn-transparent-dark">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                @else
                                <button title="Restaurar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}" class="btn btn-datatable btn-icon btn-transparent-dark">
                                    <i class="fas fa-undo"></i>
                                </button>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Ver Producto -->
                    <div class="modal fade" id="verModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detalles del producto</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <p><span class="fw-bolder">Descripción: </span>{{$item->descripcion}}</p>
                                        </div>
                                        <div class="col-12">
                                            <p><span class="fw-bolder">Fecha de vencimiento: </span>{{$item->fecha_vencimiento == '' ? 'No tiene' : $item->fecha_vencimiento}}</p>
                                        </div>
                                        <div class="col-12">
                                            <p><span class="fw-bolder">Stock: </span>{{$item->stock}}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="fw-bolder">Imagen:</p>
                                            <div>
                                                @if ($item->img_path != null)
                                                <img src="{{ Storage::url('productos/'.$item->img_path) }}" alt="{{$item->nombre}}" class="img-fluid img-thumbnail border border-4 rounded">
                                                @else
                                                <img src="" alt="{{$item->nombre}}">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de Confirmación -->
                    <div class="modal fade" id="confirmModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{ $item->estado == 1 ? '¿Seguro que quieres eliminar el producto?' : '¿Seguro que quieres restaurar el producto?' }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('productos.destroy',['producto'=>$item->id]) }}" method="post">
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
<script src="{{ asset('js/notifications.js') }}"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dataTable = new simpleDatatables.DataTable("#datatablesSimple", {
            perPageSelect: [10, 25, 50, 100]  // Opciones de cantidad de registros para mostrar
        });
    });

</script>

@endpush
