@extends('layouts.app')

@section('title','Realizar compra')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
<style>
    /* Contorno rojo para precio de venta inválido */
    .invalid-input {
        border: 2px solid red !important;
        box-shadow: 0 0 5px rgba(255, 0, 0, 0.5);
    }
</style>

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Compra</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('compras.index')}}">Compras</a></li>
        <li class="breadcrumb-item active">Crear Compra</li>
    </ol>
</div>

<form action="{{ route('compras.store') }}" method="post">
    @csrf

    <div class="container-lg mt-4">
        <div class="row gy-4">
            <!------Compra producto---->
            <div class="col-xl-8">
                <div class="text-white bg-primary p-1 text-center">
                    Detalles de la compra
                </div>
                <div class="p-3 border border-3 border-primary">
                    <div class="row">
                        <!-----Producto---->
                        <div class="col-12 mb-4">
                            <select name="producto_id" id="producto_id" class="form-control selectpicker" data-live-search="true" data-size="1" title="Busque un producto aquí">
                                @foreach ($productos as $item)
                                <option value="{{$item->id}}">{{$item->codigo.' '.$item->nombre}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-----Cantidad---->
                        <div class="col-sm-4 mb-2">
                            <label for="cantidad" class="form-label">Cantidad:</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-control">
                        </div>

                        <!-----Precio de compra---->
                        <div class="col-sm-4 mb-2">
                            <label for="precio_compra" class="form-label">Precio de compra:</label>
                            <div class="input-group">
                                <span class="input-group-text">MX$</span>
                                <input type="number" name="precio_compra" id="precio_compra" class="form-control" step="0.1">
                            </div>
                        </div>

                        <!-----Precio de venta---->
                        <div class="col-sm-4 mb-2">
                            <label for="precio_venta" class="form-label">Precio de venta:</label>
                            <div class="input-group">
                                <span class="input-group-text">MX$</span>
                                <input type="number" name="precio_venta" id="precio_venta" class="form-control" step="0.1">
                            </div>
                            <!-- Contenedor para el mensaje de error -->
                            <small id="precio_venta_error" class="text-danger d-none">
                                El precio de venta no puede ser menor que el precio de compra.
                            </small>
                        </div>

                        <!-----botón para agregar--->
                        <div class="col-12 mb-4 mt-2 text-end">
                            <button id="btn_agregar" class="btn btn-primary" type="button">Agregar</button>
                        </div>

                        <!-----Tabla para el detalle de la compra--->
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tabla_detalle" class="table table-hover">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th class="text-white">#</th>
                                            <th class="text-white">Producto</th>
                                            <th class="text-white">Cantidad</th>
                                            <th class="text-white">Precio compra MX$</th>
                                            <th class="text-white">Precio venta MX$</th>
                                            <th class="text-white">Subtotal MX$</th>
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
                                            <th colspan="2"> MX$ <span id="sumas">0</span></th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">IVA %</th>
                                            <th colspan="2">MX$ <span id="igv">0</span></th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th colspan="4">Total</th>
                                            <th colspan="2"> <input type="hidden" name="total" value="0" id="inputTotal">MX$ <span id="total">0</span></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!--Boton para cancelar compra-->
                        <div class="col-12 mt-2">
                            <button id="cancelar" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Cancelar compra
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!-----Compra---->
            <div class="col-xl-4">
                <div class="text-white bg-success p-1 text-center">
                    Datos generales
                </div>
                <div class="p-3 border border-3 border-success">
                    <div class="row">
                        <!--Proveedor-->
                        <div class="col-12 mb-2">
                            <label for="proveedore_id" class="form-label">Proveedor:</label>
                            <select name="proveedore_id" id="proveedore_id" class="form-control selectpicker show-tick" data-live-search="true" title="Selecciona" data-size='2'>
                                @foreach ($proveedores as $item)
                                <option value="{{$item->id}}">{{$item->persona->razon_social}}</option>
                                @endforeach
                            </select>
                            @error('proveedore_id')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <!--Tipo de comprobante-->
                        <div class="col-12 mb-2">
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

                        <!--Numero de comprobante-->
                        <div class="col-12 mb-2">
                            <label for="numero_comprobante" class="form-label">Numero de folio:</label>
                            <input required type="text" name="numero_comprobante" id="numero_comprobante" class="form-control"  maxlength="6" oninput="validateFolioInput(this)">
                            @error('numero_comprobante')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <!--Impuesto---->
                        <div class="col-sm-6 mb-2">
                            <label for="impuesto" class="form-label">Impuesto(IVA):</label>
                            <input readonly type="text" name="impuesto" id="impuesto" class="form-control border-success">
                            @error('impuesto')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <!--Fecha--->
                        <div class="col-sm-6 mb-2">
                            <label for="fecha" class="form-label">Fecha:</label>
                            <input readonly type="date" name="fecha" id="fecha" class="form-control border-success" value="<?php echo date("Y-m-d") ?>">
                            <?php

                            use Carbon\Carbon;

                            $fecha_hora = Carbon::now()->toDateTimeString();
                            ?>
                            <input type="hidden" name="fecha_hora" value="{{$fecha_hora}}">
                        </div>

                        <!--Botones--->
                        <div class="col-12 mt-4 text-center">
                            <button type="submit" class="btn btn-success" id="guardar">Realizar compra</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cancelar la compra -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Advertencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Seguro que quieres cancelar la compra?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btnCancelarCompra" type="button" class="btn btn-danger" data-bs-dismiss="modal">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

</form>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    $(document).ready(function() {
        $('#btn_agregar').click(function() {
            agregarProducto();
        });

        $('#btnCancelarCompra').click(function() {
            cancelarCompra();
        });

        disableButtons();

        $('#impuesto').val(impuesto + '%');
    });

    //Variables
    let cont = 0;
    let subtotal = [];
    let sumas = 0;
    let igv = 0;
    let total = 0;

    //Constantes
    const impuesto = 16;

    function cancelarCompra() {
        //Elimar el tbody de la tabla
        $('#tabla_detalle tbody').empty();

        //Añadir una nueva fila a la tabla
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

        //Reiniciar valores de las variables
        cont = 0;
        subtotal = [];
        sumas = 0;
        igv = 0;
        total = 0;

        //Mostrar los campos calculados
        $('#sumas').html(sumas);
        $('#igv').html(igv);
        $('#total').html(total);
        $('#impuesto').val(impuesto + '%');
        $('#inputTotal').val(total);

        limpiarCampos();
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

    function agregarProducto() {
        //Obtener valores de los campos
        let idProducto = $('#producto_id').val();
        let nameProducto = ($('#producto_id option:selected').text()).split(' ')[1];
        let cantidad = $('#cantidad').val();
        let precioCompra = $('#precio_compra').val();
        let precioVenta = $('#precio_venta').val();

        //Validaciones
        //1.Para que los campos no esten vacíos
        if (nameProducto != '' && nameProducto != undefined && cantidad != '' && precioCompra != '' && precioVenta != '') {

            //2. Para que los valores ingresados sean los correctos
            if (parseInt(cantidad) > 0 && (cantidad % 1 == 0) && parseFloat(precioCompra) > 0 && parseFloat(precioVenta) > 0) {

                //3. Para que el precio de compra sea menor que el precio de venta
                if (parseFloat(precioVenta) > parseFloat(precioCompra)) {
                    //Calcular valores
                    subtotal[cont] = round(cantidad * precioCompra);
                    sumas += subtotal[cont];
                    igv = round(sumas / 100 * impuesto);
                    total = round(sumas + igv);

                    //Crear la fila
                    let fila = '<tr id="fila' + cont + '">' +
                        '<th>' + (cont + 1) + '</th>' +
                        '<td><input type="hidden" name="arrayidproducto[]" value="' + idProducto + '">' + nameProducto + '</td>' +
                        '<td><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                        '<td><input type="hidden" name="arraypreciocompra[]" value="' + precioCompra + '">' + precioCompra + '</td>' +
                        '<td><input type="hidden" name="arrayprecioventa[]" value="' + precioVenta + '">' + precioVenta + '</td>' +
                        '<td>' + subtotal[cont] + '</td>' +
                        '<td><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + cont + ')"><i class="fa-solid fa-trash"></i></button></td>' +
                        '</tr>';

                    //Acciones después de añadir la fila
                    $('#tabla_detalle').append(fila);
                    limpiarCampos();
                    cont++;
                    disableButtons();

                    //Mostrar los campos calculados
                    $('#sumas').html(sumas);
                    $('#igv').html(igv);
                    $('#total').html(total);
                    $('#impuesto').val(igv);
                    $('#inputTotal').val(total);
                } else {
                    showModal('Precio de compra incorrecto');
                }

            } else {
                showModal('Valores incorrectos');
            }

        } else {
            showModal('Le faltan campos por llenar');
        }



    }

    function eliminarProducto(indice) {
        //Calcular valores
        sumas -= round(subtotal[indice]);
        igv = round(sumas / 100 * impuesto);
        total = round(sumas + igv);

        //Mostrar los campos calculados
        $('#sumas').html(sumas);
        $('#igv').html(igv);
        $('#total').html(total);
        $('#impuesto').val(igv);
        $('#InputTotal').val(total);

        //Eliminar el fila de la tabla
        $('#fila' + indice).remove();

        disableButtons();

    }

    function limpiarCampos() {
        let select = $('#producto_id');
        select.selectpicker('val', '');
        $('#cantidad').val('');
        $('#precio_compra').val('');
        $('#precio_venta').val('');
    }

    function round(num, decimales = 2) {
        var signo = (num >= 0 ? 1 : -1);
        num = num * signo;
        if (decimales === 0) //con 0 decimales
            return signo * Math.round(num);
        // round(x * 10 ^ decimales)
        num = num.toString().split('e');
        num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
        // x * 10 ^ (-decimales)
        num = num.toString().split('e');
        return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
    }
    //Fuente: https://es.stackoverflow.com/questions/48958/redondear-a-dos-decimales-cuando-sea-necesario

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
    $(document).ready(function () {
    // Validación del precio de venta
    $('#precio_venta').on('input', function () {
        validarPrecioVenta();
    });

    function validarPrecioVenta() {
        let precioCompra = parseFloat($('#precio_compra').val());
        let precioVenta = parseFloat($('#precio_venta').val());
        let mensajeError = $('#precio_venta_error');

        // Mostrar o esconder el mensaje de error dependiendo de la validación
        if (precioVenta < precioCompra) {
            mensajeError.removeClass('d-none'); // Muestra el mensaje
            $('#precio_venta').addClass('invalid-input'); // Resalta el campo en rojo
        } else {
            mensajeError.addClass('d-none'); // Oculta el mensaje
            $('#precio_venta').removeClass('invalid-input'); // Elimina el resalte del campo
        }
    }
});
$(document).ready(function () {
    // Aplica el estilo azul al contenedor del producto
    $('#producto_id').closest('.bootstrap-select').css({
        'border': '2px solid #0d6efd',
        'border-radius': '4px'
    });

    // Aplica el estilo verde al contenedor del proveedor
    $('#proveedore_id').closest('.bootstrap-select').css({
        'border': '2px solid #198754',
        'border-radius': '5px'
    });

    // Aplica el estilo verde al contenedor del comprobante
    $('#comprobante_id').closest('.bootstrap-select').css({
        'border': '2px solid #198754',
        'border-radius': '5px'
    });
});
$(document).ready(function () {
    function validarFormulario() {
        const proveedor = $('#proveedore_id').val();
        const comprobante = $('#comprobante_id').val();
        const numeroComprobante = $('#numero_comprobante').val();

        if (proveedor && comprobante && numeroComprobante) {
            $('#guardar').prop('disabled', false);
        } else {
            $('#guardar').prop('disabled', true);
        }
    }

    // Escuchar cambios en los campos requeridos
    $('#proveedore_id, #comprobante_id, #numero_comprobante').on('change input', function () {
        validarFormulario();
    });

    // Llama a la función una vez para establecer el estado inicial del botón
    validarFormulario();
});
$(document).ready(function () {
    // Restringir entrada en Precio Compra y Precio Venta
    $('#precio_compra, #precio_venta').on('keydown', function (e) {
        // Permitir: teclas de control como Backspace, Tab, Flechas, etc.
        if (
            e.key === "Backspace" || e.key === "Tab" || e.key === "ArrowLeft" || e.key === "ArrowRight" ||
            e.key === "Delete"
        ) {
            return; // Permitir teclas de control
        }
        // Permitir números (0-9) y punto decimal
        if ((e.key >= "0" && e.key <= "9") || e.key === ".") {
            // Validar si ya existe un punto decimal para evitar múltiples
            if (e.key === "." && $(this).val().includes(".")) {
                e.preventDefault(); // Bloquear si ya hay un punto
            }
            return; // Permitir número o punto
        }
        // Bloquear cualquier otra tecla
        e.preventDefault();
    });
    // Validar entrada en Cantidad (solo números enteros)
    $('#cantidad').on('keydown', function (e) {
        // Permitir: teclas de control como Backspace, Tab, Flechas, etc.
        if (
            e.key === "Backspace" || e.key === "Tab" || e.key === "ArrowLeft" || e.key === "ArrowRight" ||
            e.key === "Delete"
        ) {
            return; // Permitir teclas de control
        }
        // Permitir solo números (0-9)
        if (e.key >= "0" && e.key <= "9") {
            return; // Permitir número
        }
        // Bloquear cualquier otra tecla
        e.preventDefault();
    });
});
$(document).ready(function () {
    // Validación del precio de venta y habilitación del botón
    $('#precio_compra, #precio_venta').on('input', function () {
        validarCamposAgregar();
    });

    // Validar los campos y habilitar o deshabilitar el botón Agregar
    function validarCamposAgregar() {
        const producto = $('#producto_id').val();
        const cantidad = $('#cantidad').val();
        const precioCompra = parseFloat($('#precio_compra').val());
        const precioVenta = parseFloat($('#precio_venta').val());
        const btnAgregar = $('#btn_agregar');

        // Verificar si todos los campos están llenos y el precio de venta es mayor o igual al precio de compra
        if (producto && cantidad && precioCompra && precioVenta && precioVenta >= precioCompra) {
            btnAgregar.prop('disabled', false); // Habilitar botón
            $('#precio_venta_error').addClass('d-none'); // Ocultar mensaje de error
            $('#precio_venta').removeClass('invalid-input'); // Remover estilo de error
        } else {
            btnAgregar.prop('disabled', true); // Deshabilitar botón
            if (precioVenta < precioCompra) {
                $('#precio_venta_error').removeClass('d-none'); // Mostrar mensaje de error
                $('#precio_venta').addClass('invalid-input'); // Aplicar estilo de error
            } else {
                $('#precio_venta_error').addClass('d-none'); // Ocultar mensaje de error si no aplica
                $('#precio_venta').removeClass('invalid-input'); // Remover estilo de error si no aplica
            }
        }
    }

    // Llamar a la función una vez al cargar la página
    validarCamposAgregar();
});
$(document).ready(function () {
    // Validar longitud de Cantidad
    $('#cantidad').on('input', function () {
        const maxLength = 5;
        if (this.value.length > maxLength) {
            this.value = this.value.slice(0, maxLength); // Recortar a la longitud máxima
        }
    });

    // Validar longitud de Precio de Compra
    $('#precio_compra').on('input', function () {
        validarLongitudDecimal(this, 10);
    });

    // Validar longitud de Precio de Venta
    $('#precio_venta').on('input', function () {
        validarLongitudDecimal(this, 10);
    });

    // Función para validar longitud de números decimales
    function validarLongitudDecimal(input, maxLength) {
        const regexDecimal = /^\d*(\.\d{0,2})?$/; // Máximo 2 decimales
        if (!regexDecimal.test(input.value) || input.value.length > maxLength) {
            input.value = input.value.slice(0, maxLength); // Recortar a la longitud máxima
        }
    }
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
    // Función principal para validar los campos
    function validarCamposObligatorios() {
        const proveedor = $('#proveedore_id');
        const numeroFolio = $('#numero_comprobante');
        const producto = $('#producto_id');
        const cantidad = $('#cantidad');
        const precioCompra = $('#precio_compra');
        const precioVenta = $('#precio_venta');

        // Validar cada campo
        validarCampo(proveedor.closest('.bootstrap-select'), proveedor.val(), 'Registro obligatorio');
        validarCampo(numeroFolio, numeroFolio.val().trim(), 'Registro obligatorio');
        validarCampo(producto.closest('.bootstrap-select'), producto.val(), 'Registro obligatorio');
        validarCampo(cantidad, cantidad.val().trim() && parseFloat(cantidad.val()) > 0, 'Registro obligatorio');
        validarCampoInputGroup(precioCompra, precioCompra.val().trim() && parseFloat(precioCompra.val()) > 0, 'Registro obligatorio');
        validarCampoInputGroup(precioVenta, precioVenta.val().trim() && parseFloat(precioVenta.val()) > 0, 'Registro obligatorio');
    }

    // Función para validar campos normales
    function validarCampo(campo, condicion, mensaje) {
        if (!condicion) {
            if (!campo.next('.error-message').length) {
                campo.after(`<div class="error-message text-danger">${mensaje}</div>`);
            }
        } else {
            campo.next('.error-message').remove();
        }
    }

    // Función para validar campos en Input Group
    function validarCampoInputGroup(input, condicion, mensaje) {
        const inputGroup = input.closest('.input-group'); // Selecciona el div .input-group
        const errorContainer = inputGroup.next('.error-message'); // Verifica si existe un mensaje de error

        if (!condicion) {
            if (!errorContainer.length) {
                inputGroup.after(`<small class="error-message text-danger">${mensaje}</small>`);
            }
        } else {
            errorContainer.remove();
        }
    }

    // Validar inicialmente al cargar la página
    validarCamposObligatorios();

    // Escuchar cambios en tiempo real
    $('#proveedore_id, #numero_comprobante, #producto_id, #cantidad, #precio_compra, #precio_venta').on('input change', function () {
        validarCamposObligatorios();
    });
});

</script>
@endpush
