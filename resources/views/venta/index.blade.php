@extends('layouts.app')

@section('title','ventas')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush
@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .row-not-space {
        width: 110px;
    }
</style>
@endpush

@section('content')

@include('layouts.partials.alert')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Ventas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Ventas</li>
    </ol>

    @can('crear-venta')
    <div class="mb-4">
        <a href="{{route('ventas.create')}}">
            <button type="button" class="btn btn-primary">Agregar nuevo registro</button>
        </a>
    </div>
    @endcan
    <div class="mb-3">
    <label for="fecha_filtro" class="form-label">Filtrar ventas por fecha:</label>
    <input type="date" id="fecha_filtro" class="form-control" />
</div>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla ventas
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>Comprobante</th>
                        <th>Cliente</th>
                        <th>Fecha y hora</th>
                        <th>Vendedor</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ventas as $item)
                    @php
                    $totalServicios = 0;
                    $impuesto = 16;
                    foreach ($item->servicios as $item2){
                        $precioServi = $item2->pivot->precio -$item2->pivot->descuento;
                        $igv = $precioServi * $impuesto / 100;
                        $totalServicios += $precioServi + $igv;
                    }
                        $totalVenta = $item->total + $totalServicios;
                    @endphp
                    <tr>
                        <td>
                            <p class="fw-semibold mb-1">{{$item->comprobante->tipo_comprobante ?? 'Comprobante no disponible'}}</p>
                            <p class="text-muted mb-0">{{$item->numero_comprobante}}</p>
                        </td>
                        <td>
                            <p class="fw-semibold mb-1">{{ ucfirst($item->cliente->persona->tipo_persona) }}</p>
                            <p class="text-muted mb-0">{{$item->cliente->persona->razon_social}}</p>
                        </td>
                        <td>
                            <div class="row-not-space">
                                <p class="fw-semibold mb-1"><span class="m-1"><i class="fa-solid fa-calendar-days"></i></span>{{\Carbon\Carbon::parse($item->fecha_hora)->format('d-m-Y')}}</p>
                                <p class="fw-semibold mb-0"><span class="m-1"><i class="fa-solid fa-clock"></i></span>{{\Carbon\Carbon::parse($item->fecha_hora)->format('H:i')}}</p>
                            </div>
                        </td>
                        <td>
                            {{$item->user->name}}
                        </td>
                        <td>
                        {{ number_format($item->total, 2) }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center" role="group" aria-label="Basic mixed styles example">
                                @can('mostrar-venta')
                                <a href="{{ route('ventas.show', ['venta' => $item]) }}" class="text-success mx-3" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan
                                |
                                @can('imprimir-venta')
                                <a href="{{ route('ventas.imprimir', ['venta' => $item]) }}" target="_blank" class="text-primary mx-3" title="Imprimir">
                                    <i class="fas fa-print"></i>
                                </a>
                                @endcan
                                |
                                @can('eliminar-venta')
                                <a href="#" class="text-danger mx-3" title="Eliminar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
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
                                    ¿Seguro que quieres eliminar el registro?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('ventas.destroy',['venta'=>$item->id]) }}" method="post">
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
    </div> <!-- Cierre del div de la tabla -->
    </div> <!-- Cierre del card -->

    <!-- Botón para generar reporte PDF -->
    <div class="text-end mt-3">
        <form method="GET" action="{{ route('ventas.reporte') }}">
            <input type="hidden" id="fecha_reporte" name="fecha" value="">
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Generar Reporte PDF
            </button>
        </form>
    </div>

    </div> <!-- Cierre del container -->
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    window.addEventListener('DOMContentLoaded', event => {
        const dataTable = new simpleDatatables.DataTable("#datatablesSimple", {})
    });

    document.addEventListener('DOMContentLoaded', function() {
        const inputFecha = document.getElementById('fecha_filtro');
        const tablaVentas = document.querySelector('#datatablesSimple tbody');

        document.getElementById('fecha_filtro').addEventListener('change', function () {
    const fechaSeleccionada = this.value;

    if (fechaSeleccionada) {
        fetch(`/ventas/filtrar?fecha=${fechaSeleccionada}`)
            .then(response => response.json())
            .then(data => {
                const tablaVentas = document.querySelector('#datatablesSimple tbody');
                tablaVentas.innerHTML = ''; // Limpiar la tabla

                if (data.length > 0) {
                    data.forEach(venta => {
                        const acciones = `
                            <div class="d-flex align-items-center">
                                ${venta.acciones.can_ver ? `
                                    <a href="${venta.acciones.ver}" class="text-success mx-3" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>` : ''}
                                ${venta.acciones.can_imprimir ? `
                                    <a href="${venta.acciones.imprimir}" target="_blank" class="text-primary mx-3" title="Imprimir">
                                        <i class="fas fa-print"></i>
                                    </a>` : ''}
                                ${venta.acciones.can_eliminar ? `
                                    <a href="${venta.acciones.eliminar}" class="text-danger mx-3" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>` : ''}
                            </div>`;

                        const fila = `
                            <tr>
                                <td>
                                    <p class="fw-semibold mb-1">${venta.comprobante}</p>
                                    <p class="text-muted mb-0">${venta.numero_comprobante}</p>
                                </td>
                                <td>
                                    <p class="fw-semibold mb-1">${venta.cliente}</p>
                                </td>
                                <td>${venta.fecha}</td>
                                <td>${venta.vendedor}</td>
                                <td>${venta.total}</td>
                                <td>${acciones}</td>
                            </tr>`;
                        tablaVentas.insertAdjacentHTML('beforeend', fila);
                    });
                } else {
                    tablaVentas.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center">No hay ventas registradas para esta fecha.</td>
                        </tr>`;
                }
            })
            .catch(error => console.error('Error al filtrar ventas:', error));
    }
});


        // Capturar la fecha seleccionada y agregarla al formulario de reporte
        inputFecha.addEventListener('change', function () {
            document.getElementById('fecha_reporte').value = this.value;
        });
    });
</script>
@endpush