@extends('layouts.app')

@section('title','Realizar venta')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .invalid-input {
        border: 2px solid red !important;
        box-shadow: 0 0 5px rgba(255, 0, 0, 0.5);
    }
    .error-message {
        color: red;
        font-size: 0.9rem;
        margin-top: 5px;
    }
    /* Clase para indicar el estado seleccionado */
button.selected {
    background-color: #28a745; 
    color: white;
    border: 1px solid #0a58ca; 
}

#btn_no_servicio.selected {
    background-color: #dc3545; 
    color: white;
    border: 1px solid #b02a37;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Realizar Venta</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ventas.index')}}">Ventas</a></li>
        <li class="breadcrumb-item active">Realizar Venta</li>
    </ol>
</div>

<form action="{{ route('ventas.store') }}" method="post">
    @csrf
    <div class="container-lg mt-4">
        <div class="row gy-4">

            <!------venta producto---->
            <div class="col-xl-8">
                <div class="text-white bg-primary p-1 text-center">
                    Detalles de la venta
                </div>
                <div class="p-3 border border-3 border-primary">
                    <div class="row gy-4">

                        <!-----Producto---->
                        <select name="producto_id" id="producto_id" class="form-control selectpicker" data-live-search="true" data-size="8" title="Ingrese el codigo del producto">
                            @foreach ($productos->unique('id') as $item) {{-- Evita duplicados --}}
                            <option value="{{ $item->id }}-{{ $item->stock }}-{{ $item->ultimaCompraProducto->precio_venta ?? '0' }}">
                                {{ $item->codigo . ' ' . $item->nombre }}
                            </option>
                            @endforeach
                        </select>


                        <!-----Stock--->
                        <div class="d-flex justify-content-end">
                            <div class="col-12 col-sm-6">
                                <div class="row">
                                    <label for="stock" class="col-form-label col-4">Stock:</label>
                                    <div class="col-8">
                                        <input disabled id="stock" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-----Precio de venta---->
                        <div class="col-sm-4">
                            <label for="precio_venta" class="form-label">Precio de venta:</label>
                            <div class="input-group">
                                <span class="input-group-text">MX$</span>
                                <input disabled type="number" name="precio_venta" id="precio_venta" class="form-control" step="0.1">
                            </div>
                        </div>

                        <!-----Cantidad---->
                        <div class="col-sm-4 mb-2">
                            <label for="cantidad" class="form-label">Cantidad:</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-control">
                            <!-- Mensaje de error para cantidad -->
                            <div id="error-cantidad" class="error-message d-none"></div>
                        </div>

                        <!----Descuento---->
                        <div class="col-sm-4">
                            <label for="descuento" class="form-label">Descuento:</label>
                            <div class="input-group">
                                <span class="input-group-text">MX$</span>
                                <input type="number" name="descuento" id="descuento" class="form-control">
                            </div>
                        </div>
                         <!-- Pregunta para agregar servicios -->
                         <div class="col-12 mt-3">
                            <label class="form-label">¿Desea agregar algún servicio extra?</label>
                            <div class="d-flex gap-3">
                                <button type="button" id="btn_si_servicio" class="btn btn-outline-success">Sí</button>
                                <button type="button" id="btn_no_servicio" class="btn btn-outline-danger">No</button>
                            </div>
                        </div>

                        <!-- Botón para mostrar/ocultar el selector de servicios extras -->
                        <div class="col-12 mb-4 mt-2 text-end">
                            <button id="btn_agregar" class="btn btn-primary" type="button">Agregar</button>

                            <button id="btn_servicios" class="btn btn-primary" type="button">Servicios Extras</button>
                        </div>

                        <!-- Contenedor de servicios que se muestra/oculta -->
                        <div class="col-xl-8" id="buscar_servicios" style="display: none;">

                            <!-- Selector de servicios con búsqueda -->
                            <div class="mb-3">
                                <label for="servicios_id" class="form-label">Ingrese el codigo de servicio:</label>
                                <select name="servicios_id" id="servicios_id" class="form-control selectpicker" data-live-search="true" data-size="1" title="Seleccione un servicio">
                                    @foreach ($servicios as $item)
                                    <option value="{{$item->id}}-{{$item->nombre}}-{{$item->precio}}">{{$item->codigo.' '.$item->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Contenedor para el nombre y el precio del servicio seleccionado, alineado en una fila -->
                            <div class="row">
                                <!-- Nombre de servicio -->
                                <div class="col-sm-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre de servicio:</label>
                                    <input disabled type="text" name="nombre" id="nombre" class="form-control">
                                </div>

                                <!-- Precio de servicio -->
                                <div class="col-sm-6 mb-3">
                                    <label for="precio" class="form-label">Precio de servicio MX$:</label>
                                    <input disabled type="number" name="precio" id="precio" class="form-control" step="0.1">
                                </div>

                                <div class="col-sm-4">
                                    <label for="descuentoS" class="form-label">Descuento de servicios:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">MX$</span>
                                        <input type="number" name="descuentoS" id="descuentoS" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="tabla_detalle_servicios" class="table table-hover">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th class="text-white">#</th>
                                                <th class="text-white">Servicio</th>
                                                <th class="text-white">Precio MX$</th>
                                                <th class="text-white">Descuento MX$</th>
                                                <th class="text-white">Subtotal MX$</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Las filas se agregarán dinámicamente aquí -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th colspan="4">Sumas</th>
                                                <th colspan="2">MX$ <span id="precioS">0</span></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th colspan="4">IVA %</th>
                                                <th colspan="2">MX$ <span id="igvS">0</span></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th colspan="4">Total</th>
                                                <th colspan="2">MX$ 
                                                    <input type="hidden" name="total" value="0" id="inputTotalS">
                                                    <span id="totalS">0</span>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Script para mostrar/ocultar el contenedor de servicios -->
                        <script>
                            document.getElementById("btn_servicios").addEventListener("click", function() {
                                var ser = document.getElementById("buscar_servicios");
                                ser.style.display = ser.style.display === "none" ? "block" : "none";
                            });
                        </script>

                        <!-----Tabla para el detalle de la venta--->
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tabla_detalle" class="table table-hover">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th class="text-white">#</th>
                                            <th class="text-white">Producto</th>
                                            <th class="text-white">Cantidad</th>
                                            <th class="text-white">Precio venta MX$</th>
                                            <th class="text-white">Descuento MX$</th>
                                            <th class="text-white">Subtotal MX$</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Las filas se agregarán dinámicamente aquí -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">Sumas</th>
                                            <th colspan="2">MX$ <span id="sumas">0</span></th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">IVA %</th>
                                            <th colspan="2">MX$ <span id="igv">0</span></th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">Total</th>
                                            <th colspan="2">MX$
                                                <input type="hidden" name="total" value="0" id="inputTotal">
                                                <span id="total">0</span>
                                            </th>
                                        </tr>$
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!--Boton para cancelar venta--->
                        <div class="col-12">
                            <button id="cancelar" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Cancelar servicio
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!-----Venta---->
            <div class="col-xl-4">
                <div class="text-white bg-success p-1 text-center">
                    Datos generales
                </div>
                <div class="p-3 border border-3 border-success">
                    <div class="row gy-4">
                        <!--Cliente-->
                        <div class="col-12">
                            <label for="cliente_id" class="form-label">Cliente:</label>
                            <select name="cliente_id" id="cliente_id" class="form-control selectpicker show-tick" data-live-search="true" title="Selecciona" data-size='2'>
                                @foreach ($clientes as $item)
                                <option value="{{$item->id}}">{{$item->persona->razon_social}}</option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <!--Tipo de comprobante-->
                        <div class="col-12">
                            <label for="comprobante_id" class="form-label">Comprobante:</label>
                            <select name="comprobante_id" id="comprobante_id" class="form-control selectpicker" title="Selecciona">
                                @foreach ($comprobantes as $item)
                                <option value="{{$item->id}}" selected>{{$item->tipo_comprobante}}</option>
                                @endforeach
                            </select>
                            @error('comprobante_id')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <!-- Numero de comprobante -->
                        <div class="col-12">
                            <label for="numero_comprobante" class="form-label">Numero de folio:</label>
                            <input required type="number" name="numero_comprobante" id="numero_comprobante" class="form-control" maxlength="6" oninput="validateFolioInput(this)" >
                            @error('numero_comprobante')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <script>
                            function validateNumberInput(input) {
                                input.value = input.value.replace(/[^0-9]/g, '');
                            }
                        </script>
                        <!-- Cantidad a pagar -->
                        <div class="col-12">
                            <label for="cantidad_pagar" class="form-label">Cantidad a pagar:</label>
                            <div class="input-group">
                                <span class="input-group-text">MX$</span>
                                <input type="number" id="cantidad_pagar" class="form-control" step="0.01" placeholder="Ingrese el monto que paga el cliente">
                            </div>
                            <small id="error-cantidad-pagar" class="error-message d-none">La cantidad a pagar no puede ser menor que el total.</small>
                        </div>

                        <!-- Cambio -->
                        <div class="col-12">
                            <label for="cambio" class="form-label">Cambio:</label>
                            <div class="input-group">
                                <span class="input-group-text">MX$</span>
                                <input readonly type="number" id="cambio" class="form-control" step="0.01">
                            </div>
                        </div>
                        <!--Impuesto---->
                        <div class="col-sm-6">
                            <label for="impuesto" class="form-label">Impuesto(IVA) MX$:</label>
                            <input readonly type="text" name="impuesto" id="impuesto" class="form-control border-success">
                            @error('impuesto')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <!--Fecha--->
                        <div class="col-sm-6">
                            <label for="fecha" class="form-label">Fecha:</label>
                            <input readonly type="date" name="fecha" id="fecha" class="form-control border-success" value="<?php echo date("Y-m-d") ?>">
                            <?php

                            use Carbon\Carbon;

                            $fecha_hora = Carbon::now()->toDateTimeString();
                            ?>
                            <input type="hidden" name="fecha_hora" value="{{$fecha_hora}}">
                        </div>

                        <!----User--->
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                        <!--Botones--->
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success" id="guardar">Realizar venta</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cancelar la venta -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Advertencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Seguro que quieres cancelar la venta?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btnCancelarVenta" type="button" class="btn btn-danger" data-bs-dismiss="modal">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

</form>
@endsection
@push('js')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<script>
    function desactivarBotonAgregar() {
    const btnAgregar = $('#btn_agregar');
    btnAgregar.prop('disabled', true); // Deshabilitar el botón
    console.log('Botón Agregar deshabilitado al inicio.');
}
    $(document).ready(function() {
        // Asociar eventos a los selects
        $('#producto_id').change(mostrarValores);
        $('#servicios_id').change(mostrarValoresS);

        // Evento click para el botón 'Agregar'
        $('#btn_agregar').click(function() {
            console.log("Botón de agregar presionado.");

            if ($('#producto_id').val() !== '') {
                agregarProducto();
            } else if ($('#servicios_id').val() !== '') {
                agregarServicios();
            } else {
                showModal('Por favor, selecciona un producto o servicio para agregar.');
            }
        });

        // Evento click para el botón 'Cancelar Venta'
        $('#btnCancelarVenta').click(function() {
            console.log("Botón de cancelar venta presionado.");
            cancelarVenta();
        });

        // Inicializar el valor de impuesto
        $('#impuesto').val(impuesto + '%');

        // Inicializar botones según el estado
        disableButtons();
        desactivarBotonAgregar(); // Deshabilitar el botón al cargar la página

    });

    // Funciones de validación
    function validarDatosProducto() {
        let precioVenta = parseFloat($('#precio_venta').val());
        return precioVenta > 0 && $('#producto_id').val() !== '';
    }

    function validarDatosServicio() {
        let precioServicio = parseFloat($('#precio').val());
        return precioServicio > 0 && $('#servicios_id').val() !== '';
    }

    // Variables globales
    let cont = 0;
    let subtotal = [];
    let sumas = 0;
    let igvProductos = 0;
    let igvServicios = 0;
    let total = 0;
    let precioS = 0;

    // Constantes
    const impuesto = 16; // Definir el impuesto en porcentaje

    // Mostrar valores del producto seleccionado
    function mostrarValores() {
        let dataProducto = document.getElementById('producto_id').value.split('-');
        $('#stock').val(dataProducto[1]);
        $('#precio_venta').val(dataProducto[2]);
    }

    // Mostrar valores del servicio seleccionado
    function mostrarValoresS() {
        let dataServicios = document.getElementById('servicios_id').value.split('-');
        $('#nombre').val(dataServicios[1]);
        $('#precio').val(dataServicios[2]);
    }

    // Agregar servicio a la tabla
    function agregarServicios() {
        let dataServicio = document.getElementById('servicios_id').value.split('-');
        let idServicios = dataServicio[0];
        let nameServicio = $('#servicios_id option:selected').text();
        let precioServicio = parseFloat($('#precio').val());
        let descuentoS = parseFloat($('#descuentoS').val()) || 0;

        if (nameServicio && precioServicio > 0) {
            subtotal[cont] = round(precioServicio - descuentoS);
            precioS += subtotal[cont];
            igvServicios = round(precioS * impuesto / 100);
            total = round(precioS + igvProductos + igvServicios);  // Sumar impuestos de productos y servicios

            let fila = '<tr id="filaS' + cont + '">' +
                '<th>' + (cont + 1) + '</th>' +
                '<td><input type="hidden" name="arrayidservicio[]" value="' + idServicios + '">' + nameServicio + '</td>' +
                '<td><input type="hidden" name="arrayprecioservicio[]" value="' + precioServicio + '">' + precioServicio.toFixed(2) + '</td>' +
                '<td><input type="hidden" name="arraydescuentoservicio[]" value="' + descuentoS + '">' + descuentoS.toFixed(2) + '</td>' +
                '<td>' + subtotal[cont].toFixed(2) + '</td>' +
                '<td><button class="btn btn-danger" type="button" onClick="eliminarServicio(' + cont + ')"><i class="fa-solid fa-trash"></i></button></td>' +
                '</tr>';

            $('#tabla_detalle_servicios tbody').append(fila); // Apuntar al tbody
            limpiarCamposS();
            cont++;

            // Actualizar los totales
            $('#precioS').html(precioS.toFixed(2));
            $('#igvS').html(igvServicios.toFixed(2));
            $('#totalS').html(total.toFixed(2));
            $('#impuesto').val(igvProductos + igvServicios);  // Mostrar impuesto total
            $('#inputTotalS').val(total.toFixed(2));
        } else {
            showModal('Valores Incorrectos');
        }
    }

    // Eliminar servicio de la tabla
    function eliminarServicio(indice) {
        precioS -= round(subtotal[indice]);
        igvServicios = round(precioS * impuesto / 100);
        total = round(sumas + precioS + igvProductos + igvServicios);

        $('#precioS').html(precioS.toFixed(2));
        $('#igvS').html(igvServicios.toFixed(2));
        $('#totalS').html(total.toFixed(2));
        $('#impuesto').val(igvProductos + igvServicios);
        $('#inputTotalS').val(total.toFixed(2));

        $('#filaS' + indice).remove();
        disableButtons();
    }

    // Agregar producto a la tabla
    function agregarProducto() {
        let dataProducto = document.getElementById('producto_id').value.split('-');
        let idProducto = dataProducto[0];
        let nameProducto = $('#producto_id option:selected').text();
        let cantidad = parseInt($('#cantidad').val());
        let precioVenta = parseFloat($('#precio_venta').val());
        let descuento = parseFloat($('#descuento').val()) || 0;
        let stock = parseInt($('#stock').val());

        if (idProducto && cantidad) {
            if (cantidad > 0 && Number.isInteger(cantidad) && descuento >= 0) {
                if (cantidad <= stock) {
                    subtotal[cont] = round((cantidad * precioVenta) - descuento);
                    sumas += subtotal[cont];
                    igvProductos = round(sumas * impuesto / 100);
                    total = round(sumas + igvProductos + igvServicios);  // Sumar impuestos de productos y servicios

                    let fila = '<tr id="fila' + cont + '">' +
                        '<th>' + (cont + 1) + '</th>' +
                        '<td><input type="hidden" name="arrayidproducto[]" value="' + idProducto + '">' + nameProducto + '</td>' +
                        '<td><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                        '<td><input type="hidden" name="arrayprecioventa[]" value="' + precioVenta + '">' + precioVenta.toFixed(2) + '</td>' +
                        '<td><input type="hidden" name="arraydescuento[]" value="' + descuento + '">' + descuento.toFixed(2) + '</td>' +
                        '<td>' + subtotal[cont].toFixed(2) + '</td>' +
                        '<td><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + cont + ')"><i class="fa-solid fa-trash"></i></button></td>' +
                        '</tr>';

                    $('#tabla_detalle tbody').append(fila); // Apuntar al tbody
                    limpiarCampos();
                    cont++;

                    // Actualizar los totales
                    $('#sumas').html(sumas.toFixed(2));
                    $('#igv').html(igvProductos.toFixed(2));
                    $('#total').html(total.toFixed(2));
                    $('#impuesto').val(igvProductos + igvServicios);  // Mostrar impuesto total
                    $('#inputTotal').val(total.toFixed(2));
                } else {
                    showModal('Cantidad incorrecta: supera el stock disponible.');
                }
            } else {
                showModal('Valores incorrectos: asegúrate de ingresar una cantidad válida y descuentos no negativos.');
            }
        } else {
            showModal('Le faltan campos por llenar.');
        }
    }

    // Eliminar producto de la tabla
    function eliminarProducto(indice) {
        sumas -= round(subtotal[indice]);
        igvProductos = round(sumas * impuesto / 100);
        total = round(sumas + igvProductos + igvServicios);

        $('#sumas').html(sumas.toFixed(2));
        $('#igv').html(igvProductos.toFixed(2));
        $('#total').html(total.toFixed(2));
        $('#impuesto').val(igvProductos + igvServicios);
        $('#inputTotal').val(total.toFixed(2));

        $('#fila' + indice).remove();
        disableButtons();
    }

    // Cancelar venta y limpiar todo
    function cancelarVenta() {
        $('#tabla_detalle tbody').empty();
        $('#tabla_detalle_servicios tbody').empty(); // Limpiar también servicios si es necesario

        cont = 0;
        subtotal = [];
        sumas = 0;
        igvProductos = 0;
        igvServicios = 0;
        total = 0;
        precioS = 0;

        $('#sumas').html(sumas.toFixed(2));
        $('#igv').html(igvProductos.toFixed(2));
        $('#total').html(total.toFixed(2));
        $('#impuesto').val(impuesto + '%');
        $('#inputTotal').val(total.toFixed(2));
        $('#inputTotalS').val(total.toFixed(2));

        limpiarCampos();
        limpiarCamposS();
        disableButtons();
    }

    // Mostrar u ocultar botones según el estado del total
    // Mostrar u ocultar botones según el estado del total
function disableButtons() {
    if (total <= 0 && precioS <= 0) {
        $('#guardar').show();  // Ocultar el botón si el total y el precio son cero
        $('#cancelar').hide(); // Ocultar el botón si no hay productos o servicios
    } else {
        $('#guardar').show();  // Mostrar el botón si el total o precio es mayor que cero
        $('#cancelar').show(); // Mostrar el botón de cancelar
    }
}
console.log("Total: ", total);
console.log("PrecioS: ", precioS);



    // Limpiar campos del producto
    function limpiarCampos() {
        let select = $('#producto_id');
        select.selectpicker('val', '');
        $('#cantidad').val('');
        $('#precio_venta').val('');
        $('#descuento').val('');
        $('#stock').val('');
        $('#btn_agregar').prop('disabled', true);
    }

    // Limpiar campos del servicio
    function limpiarCamposS() {
        let select = $('#servicios_id');
        select.val('').change();
        $('#nombre').val('');
        $('#precio').val('');
        $('#descuentoS').val('');
    }

    // Mostrar mensajes con SweetAlert2
    function showModal(message, icon = 'error') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: icon,
            title: message
        });
    }

    // Función para redondear números
    function round(num, decimales = 2) {
        return +(Math.round(num + "e+" + decimales) + "e-" + decimales);
    }
    $(document).ready(function () {
    // Forzar contornos desde el script
    $('#producto_id').closest('.bootstrap-select').css({
        'border': '2px solid #0d6efd',
        'border-radius': '5px'
    });
    $('#cliente_id').closest('.bootstrap-select').css({
        'border': '2px solid #198754',
        'border-radius': '5px'
    });
    $('#comprobante_id').closest('.bootstrap-select').css({
        'border': '2px solid #198754',
        'border-radius': '5px'
    });
    $('#servicios_id').closest('.bootstrap-select').css({
        'border': '2px solid #0d6efd',
        'border-radius': '5px'
    });
    $('#numero_comprobante').css({
        'border': '2px solid #198754',
        'border-radius': '5px'
    });
    $('#cantidad_pagar').css({
        'border': '2px solid #198754',
        'border-radius': '5px'
    });
    $('#cambio').css({
        'border': '2px solid #198754',
        'border-radius': '5px'
    });

    // Validar cantidad en tiempo real
    $('#cantidad').on('input', validarCantidad);

    // Validar campos obligatorios para habilitar el botón "Guardar"
    $('#cliente_id, #comprobante_id, #numero_comprobante').on('change input', validarFormularioVenta);

    // Validar producto y cantidad para habilitar "Agregar"
    $('#producto_id, #cantidad').on('change input', validarProductoYCantidad);

    // Ejecutar validaciones iniciales al cargar la página
    validarFormularioVenta();
    validarProductoYCantidad();
});

function validarFormularioVenta() {
    const cliente = $('#cliente_id').val();
    const comprobante = $('#comprobante_id').val();
    const numeroComprobante = $('#numero_comprobante').val();
    const totalVenta = parseFloat($('#total').text()) || 0;
    const montoPagar = parseFloat($('#cantidad_pagar').val()) || 0;
    const guardarButton = $('#guardar');

    if (cliente && comprobante && numeroComprobante && montoPagar >= totalVenta) {
        guardarButton.prop('disabled', false);
    } else {
        guardarButton.prop('disabled', true);
    }
}

// Evento para validar formulario
$('#cliente_id, #comprobante_id, #numero_comprobante, #cantidad_pagar').on('change input', validarFormularioVenta);

// Validación inicial
validarFormularioVenta();


function validarProductoYCantidad() {
    const productoSeleccionado = $('#producto_id').val(); // Validar si se seleccionó un producto
    const cantidad = parseFloat($('#cantidad').val()); // Validar si la cantidad es mayor a 0
    const stock = parseInt($('#stock').val()); // Validar si la cantidad no supera el stock disponible
    const btnAgregar = $('#btn_agregar');
    btnAgregar.prop('disabled', true);
    if (productoSeleccionado && cantidad > 0 && cantidad <= stock) {
        btnAgregar.prop('disabled', false); // Habilitar el botón
    } else {
        btnAgregar.prop('disabled', true); // Deshabilitar el botón

    }
}

// Evento para validar el botón "Agregar"
$('#producto_id, #cantidad').on('change input', function () {
    validarProductoYCantidad();
});

// Llamada inicial al cargar la página
validarProductoYCantidad();

// Declarar la función fuera de $(document).ready()
function validarCantidad() {
    const cantidad = parseInt($('#cantidad').val()) || 0;
    const stock = parseInt($('#stock').val()) || 0;
    const btnAgregar = $('#btn_agregar');
    const errorCantidad = $('#error-cantidad');

    if (cantidad > stock) {
        $('#cantidad').addClass('invalid-input');
        if ($('#error-cantidad').length === 0) {
            $('#cantidad').after('<div id="error-cantidad" class="error-message">La cantidad no puede exceder el stock disponible.</div>');
        }
        btnAgregar.prop('disabled', true);
    } else {
        $('#cantidad').removeClass('invalid-input');
        $('#error-cantidad').remove();
        btnAgregar.prop('disabled', false);
    }
}

// Asegurarse de que el DOM esté listo
$(document).ready(function () {
    console.log("Document ready: inicializando scripts...");

    // Validar cantidad contra el stock
    $('#cantidad').on('input', validarCantidad);

    // Restringir entrada para "Cantidad" y "Descuento"
    $('#cantidad, #descuento').on('keydown input', function (e) {
        const inputField = $(this);
        const maxLength = inputField.attr('id') === 'cantidad' ? 5 : 10;

        // Permitir: teclas de control como Backspace, Tab, Delete, etc.
        if (
            e.key === "Backspace" || e.key === "Tab" || e.key === "Delete" ||
            e.key === "ArrowLeft" || e.key === "ArrowRight" ||
            e.key === "Home" || e.key === "End"
        ) {
            return;
        }

        // Verificar si el límite de caracteres ya se alcanzó
        if (inputField.val().length >= maxLength) {
            e.preventDefault(); // Bloquear entrada adicional
        }

        // Permitir solo números
        if ((e.key >= "0" && e.key <= "9")) {
            return;
        }

        // Bloquear cualquier otra tecla
        e.preventDefault();
    });

    // Validación en tiempo real para recortar caracteres
    $('#cantidad, #descuento').on('input', function () {
        const inputField = $(this);
        const maxLength = inputField.attr('id') === 'cantidad' ? 5 : 10;

        if (inputField.val().length > maxLength) {
            inputField.val(inputField.val().slice(0, maxLength));
        }

        if (inputField.attr('id') === 'cantidad') {
            validarCantidad();
        }
    });

    // Llamada inicial
    validarCantidad();
});

</script>
<script>
    $(document).ready(function () {
    $('.selectpicker').selectpicker({
        noneResultsText: 'El producto no existe o está agotado.'
    });
});

    document.addEventListener('DOMContentLoaded', function () {
        const btnSiServicio = document.getElementById('btn_si_servicio');
        const btnNoServicio = document.getElementById('btn_no_servicio');
        const btnServicios = document.getElementById('btn_servicios');

        // Si el usuario elige "Sí"
        btnSiServicio.addEventListener('click', function () {
            btnServicios.disabled = false; // Habilitar el botón de servicios extras
            btnSiServicio.classList.add('selected'); // Marcar el botón "Sí" como seleccionado
            btnNoServicio.classList.remove('selected'); // Quitar la selección del botón "No"
        });

        // Si el usuario elige "No"
        btnNoServicio.addEventListener('click', function () {
            btnServicios.disabled = true; // Deshabilitar el botón de servicios extras
            btnNoServicio.classList.add('selected'); // Marcar el botón "No" como seleccionado
            btnSiServicio.classList.remove('selected'); // Quitar la selección del botón "Sí"
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
    const cantidadPagar = document.getElementById('cantidad_pagar');
    const cambio = document.getElementById('cambio');
    const totalElement = document.getElementById('total');
    const guardarButton = document.getElementById('guardar');
    const errorCantidadPagar = document.getElementById('error-cantidad-pagar');

    // Validar cantidad a pagar
    cantidadPagar.addEventListener('input', function () {
    const totalVenta = parseFloat(totalElement.textContent) || 0;
    const montoPagar = parseFloat(cantidadPagar.value) || 0;

    if (montoPagar >= totalVenta) {
        cambio.value = (montoPagar - totalVenta).toFixed(2);
        errorCantidadPagar.classList.add('d-none');
        guardarButton.disabled = false;
    } else {
        const falta = (totalVenta - montoPagar).toFixed(2);
        cambio.value = '0.00';
        errorCantidadPagar.textContent = `Te faltan MX$ ${falta} por pagar.`;
        errorCantidadPagar.classList.remove('d-none');
        guardarButton.disabled = true;
    }
});


    // Validación inicial
    guardarButton.disabled = true; // Deshabilitar el botón al cargar
});

function validateFolioInput(input) {
        // Reemplaza cualquier carácter que no sea un número
        input.value = input.value.replace(/[^0-9]/g, '');

        // Limita la cantidad de caracteres a 6
        if (input.value.length > 6) {
            input.value = input.value.slice(0, 6);
        }
    }
    $(document).ready(function () {
    // Función para validar los campos obligatorios
    function validarCamposObligatorios() {
        const cliente = $('#cliente_id');
        const numeroFolio = $('#numero_comprobante');
        const producto = $('#producto_id');
        const cantidad = $('#cantidad');
        const cantidadPagar = $('#cantidad_pagar');

        // Validar Cliente
        validarCampo(cliente.closest('.bootstrap-select'), cliente.val(), 'Registro obligatorio');

        // Validar Número de Folio
        validarCampo(numeroFolio, numeroFolio.val().trim(), 'Registro obligatorio');

        // Validar Producto
        validarCampo(producto.closest('.bootstrap-select'), producto.val(), 'Registro obligatorio');

        // Validar Cantidad
        validarCampo(cantidad, cantidad.val().trim() && parseFloat(cantidad.val()) > 0, 'Registro obligatorio');

        // Validar Cantidad a Pagar
        validarCampo(cantidadPagar, cantidadPagar.val().trim() && parseFloat(cantidadPagar.val()) > 0, 'Registro obligatorio');
    }

    // Función para mostrar el mensaje de error
    function validarCampo(campo, condicion, mensaje) {
        if (!condicion) {
            if (!campo.next('.error-message').length) {
                campo.after(`<div class="error-message">${mensaje}</div>`);
            }
        } else {
            campo.next('.error-message').remove();
        }
    }

    // Validación inicial al cargar la página
    validarCamposObligatorios();

    // Validación en tiempo real
    $('#cliente_id, #numero_comprobante, #producto_id, #cantidad, #cantidad_pagar').on('input change', function () {
        validarCamposObligatorios();
    });
});


</script>
@endpush
