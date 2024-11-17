@extends('layouts.app')

@section('title','Realizar venta')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <div class="col-12">
                            <select name="producto_id" id="producto_id" class="form-control selectpicker" data-live-search="true" data-size="1" title="Busque un producto aquí">
                                @foreach ($productos as $item)
                                <option value="{{$item->id}}-{{$item->stock}}-{{$item->precio_venta}}">{{$item->codigo.' '.$item->nombre}}</option>
                                @endforeach
                            </select>
                        </div>

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
                            <input disabled type="number" name="precio_venta" id="precio_venta" class="form-control" step="0.1">
                        </div>

                        <!-----Cantidad---->
                        <div class="col-sm-4">
                            <label for="cantidad" class="form-label">Cantidad:</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-control">
                        </div>

                        <!----Descuento---->
                        <div class="col-sm-4">
                            <label for="descuento" class="form-label">Descuento:</label>
                            <input type="number" name="descuento" id="descuento" class="form-control">
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
                                <label for="servicios_id" class="form-label">Busque su servicio aquí:</label>
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
                                    <label for="precio" class="form-label">Precio de servicio:</label>
                                    <input disabled type="number" name="precio" id="precio" class="form-control" step="0.1">
                                </div>

                                <div class="col-sm-4">
                            <label for="descuentoS" class="form-label">Descuento de servicios:</label>
                            <input type="number" name="descuentoS" id="descuentoS" class="form-control">
                        </div>
                            </div>
                            <div class="col-12">
                          <div class="table-responsive">
                                <table id="tabla_detalle_servicios" class="table table-hover">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th class="text-white">#</th>
                                            <th class="text-white">Servicio</th>
                                            <th class="text-white">Precio</th>
                                            <th class="text-white">Descuento</th>
                                            <th class="text-white">Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th></th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">Sumas</th>
                                            <th colspan="2"><span id="precioS">0</span></th>
                                        </tr>
                                          <tr>
                                            <th></th>
                                            <th colspan="4">IGV %</th>
                                            <th colspan="2"><span id="igvS">0</span></th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">Total</th>
                                            <th colspan="2"> <input type="hidden" name="total" value="0" id="inputTotalS"> <span id="totalS">0</span></th>
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
                                            <th class="text-white">Precio venta</th>
                                            <th class="text-white">Descuento</th>
                                            <th class="text-white">Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th></th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">Sumas</th>
                                            <th colspan="2"><span id="sumas">0</span></th>
                                        </tr>
                                          <tr>
                                            <th></th>
                                            <th colspan="4">IGV %</th>
                                            <th colspan="2"><span id="igv">0</span></th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">Total</th>
                                            <th colspan="2"> <input type="hidden" name="total" value="0" id="inputTotal"> <span id="total">0</span></th>
                                        </tr>
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
                                <option value="{{$item->id}}">{{$item->tipo_comprobante}}</option>
                                @endforeach
                            </select>
                            @error('comprobante_id')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <!-- Numero de comprobante -->
                        <div class="col-12">
                            <label for="numero_comprobante" class="form-label">Numero de comprobante:</label>
                            <input required type="number" name="numero_comprobante" id="numero_comprobante" class="form-control" oninput="validateNumberInput(this)">
                            @error('numero_comprobante')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <script>
                            function validateNumberInput(input) {
                                input.value = input.value.replace(/[^0-9]/g, '');
                            }
                        </script>

                        <!--Impuesto---->
                        <div class="col-sm-6">
                            <label for="impuesto" class="form-label">Impuesto(IGV):</label>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    $(document).ready(function() {
        $('#producto_id').change(mostrarValores);
        $('#servicios_id').change(mostrarValoresS);
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
        $('#btnCancelarVenta').click(function() {
            console.log("Botón de cancelar venta presionado.");

            cancelarVenta();

        });
        disableButtons();
        $('#impuesto').val(impuesto + '%');
    });
    function validarDatosProducto() {
    let precioVenta = parseFloat($('#precio_venta').val());
    return precioVenta > 0 && $('#producto_id').val() !== '';
}

// Función para validar datos de servicio
function validarDatosServicio() {
    let precioServicio = parseFloat($('#precio').val());
    return precioServicio > 0 && $('#servicios_id').val() !== '';
}
    // Variables globales
    let cont = 0;
    let subtotal = [];
    let sumas = 0;
    let igv = 0;
    let total = 0;
    let precioS = 0;

    // Constantes
    const impuesto = 16;

    function mostrarValores() {
        let dataProducto = document.getElementById('producto_id').value.split('-');
        $('#stock').val(dataProducto[1]);
        $('#precio_venta').val(dataProducto[2]);
    }

    function mostrarValoresS() {
        let dataServicios = document.getElementById('servicios_id').value.split('-');
        $('#nombre').val(dataServicios[1]);
        $('#precio').val(dataServicios[2]);
        console.log("Valores de servicio mostrados.");
    }

    function agregarServicios() {
        let dataServicio = document.getElementById('servicios_id').value.split('-');
        let idServicios = dataServicio[0];
        let nameServicio = $('#servicios_id option:selected').text();
        let precioServicio = $('#precio').val();
        let descuentoS = $('#descuentoS').val();
        console.log("Datos de servicio - ID:", idServicios, "Nombre:", nameServicio, "Precio:", precioServicio, "Descuento:", descuentoS);
        if (nameServicio && precioServicio > 0) {
            subtotal[cont] = round(precioServicio * 1 - descuentoS);
            precioS += subtotal[cont];
            igv = round(precioS / 100 * impuesto);
            total = round(precioS + igv);

            let fila = '<tr id="fila' + cont + '">' +
                        '<th>' + (cont + 1) + '</th>' +
                        '<td><input type="hidden" name="arrayidservicio[]" value="' + idServicios + '">' + nameServicio + '</td>' +
                        '<td><input type="hidden" name="arrayprecioservicio[]" value="' + precioServicio + '">' + precioServicio + '</td>' +
                        '<td><input type="hidden" name="arraydescuentoservicio[]" value="' + descuentoS + '">' + descuentoS + '</td>' +
                        '<td>' + subtotal[cont] + '</td>' +
                        '<td><button class="btn btn-danger" type="button" onClick="eliminarServicio(' + cont + ')"><i class="fa-solid fa-trash"></i></button></td>' +
                        '</tr>';

            $('#tabla_detalle_servicios').append(fila);
            limpiarCamposS();
            cont++;
            disableButtons();

            // Mostrar los totales calculados
            $('#precioS').html(precioS);
            $('#igvS').html(igv);
            $('#totalS').html(total);
            $('#impuestos').val(igv);
            $('#inputTotalS').val(total);
        } else {
            showModal('Valores Incorrectos');
        }
    }

    function eliminarServicio(indice) {
        precioS -= round(subtotal[indice]);
        igv = round(precioS / 100 * impuesto);
        total = round(precioS + igv);

        $('#precioS').html(precioS);
        $('#igvS').html(igv);
        $('#totalS').html(total);
        $('#impuestos').val(igv);
        $('#inputTotalS').val(total);

        $('#fila' + indice).remove();
        disableButtons();
    }

    function agregarProducto() {
        let dataProducto = document.getElementById('producto_id').value.split('-');
        let idProducto = dataProducto[0];
        let nameProducto = $('#producto_id option:selected').text();
        let cantidad = $('#cantidad').val();
        let precioVenta = $('#precio_venta').val();
        let descuento = parseFloat($('#descuento').val()) || 0;
        let stock = $('#stock').val();

        if (idProducto && cantidad) {
            if (parseInt(cantidad) > 0 && cantidad % 1 === 0 && descuento >= 0) {
                if (parseInt(cantidad) <= parseInt(stock)) {
                    subtotal[cont] = round(cantidad * precioVenta - descuento);
                    sumas += subtotal[cont];
                    igv = round(sumas / 100 * impuesto);
                    total = round(sumas + igv);

                    let fila = '<tr id="fila' + cont + '">' +
                        '<th>' + (cont + 1) + '</th>' +
                        '<td><input type="hidden" name="arrayidproducto[]" value="' + idProducto + '">' + nameProducto + '</td>' +
                        '<td><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                        '<td><input type="hidden" name="arrayprecioventa[]" value="' + precioVenta + '">' + precioVenta + '</td>' +
                        '<td><input type="hidden" name="arraydescuento[]" value="' + descuento + '">' + descuento + '</td>' +
                        '<td>' + subtotal[cont] + '</td>' +
                        '<td><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + cont + ')"><i class="fa-solid fa-trash"></i></button></td>' +
                        '</tr>';

                    $('#tabla_detalle').append(fila);
                    limpiarCampos();
                    //limpiarCamposS();
                    cont++;
                    disableButtons();

                    $('#sumas').html(sumas);
                    $('#igv').html(igv);
                    $('#total').html(total);
                    $('#impuesto').val(igv);
                    $('#inputTotal').val(total);
                } else {
                    showModal('Cantidad incorrecta');
                }
            } else {
                showModal('Valores incorrectos');
            }
        } else {
            showModal('Le faltan campos por llenar');
        }
    }

    function eliminarProducto(indice) {
        sumas -= round(subtotal[indice]);
        igv = round(sumas / 100 * impuesto);
        total = round(sumas + igv);

        $('#sumas').html(sumas);
        $('#igv').html(igv);
        $('#total').html(total);
        $('#impuesto').val(igv);
        $('#inputTotal').val(total);

        $('#fila' + indice).remove();
        disableButtons();
    }

    function cancelarVenta() {
        $('#tabla_detalle tbody').empty();

        let fila = '<tr>' +
            '<th></th>' +
            '<td></td>' +
            '<td></td>' +
            '<td></td>' +
            '<td></td>' +
            '<td></td>' +
            '<td></td>' +
            '</tr>';
        $('#tabla_detalle').append(fila);

        cont = 0;
        subtotal = [];
        sumas = 0;
        igv = 0;
        total = 0;

        $('#sumas').html(sumas);
        $('#igv').html(igv);
        $('#total').html(total);
        $('#impuesto').val(impuesto + '%');
        $('#inputTotal').val(total);

        limpiarCampos();
        limpiarCamposS();
        disableButtons();
    }

    function disableButtons() {
        if (total == 0) {
            $('#guardar').hide();
            $('#cancelar').hide();
        } else {
            $('#guardar').show();
            $('#cancelar').show();
        }
    }

    function limpiarCampos() {
        let select = $('#producto_id');
        select.selectpicker('val', '');
        $('#cantidad').val('');
        $('#precio_venta').val('');
        $('#descuento').val('');
        $('#stock').val('');
    }

    function limpiarCamposS() {
        let select = $('#servicios_id');
        select.val('').change();
        $('#nombre').val('');
        $('#precio').val('');
    }

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
        })

        Toast.fire({
            icon: icon,
            title: message
        })
    }

    function round(num, decimales = 2) {
        var signo = (num >= 0 ? 1 : -1);
        num = num * signo;
        if (decimales === 0)
            return signo * Math.round(num);
        num = num.toString().split('e');
        num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
        num = num.toString().split('e');
        return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
    }
</script>
@endpush
