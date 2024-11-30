@extends('layouts.app')

@section('title','Crear Producto')

@push('css')
<style>
    #descripcion {
        resize: none;
    }

    /* Estilos para validación de campos */
    .is-invalid {
        border: 2px solid red;
    }

    .is-valid {
        border: 2px solid green;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Producto</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('productos.index')}}">Productos</a></li>
        <li class="breadcrumb-item active">Crear producto</li>
    </ol>

    <!-- Mostrar Mensajes de Éxito y Error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <div class="card">
        <form action="{{ route('productos.store') }}" method="post" enctype="multipart/form-data" id="productoForm">
            @csrf
            <div class="card-body text-bg-light">

                <div class="row g-4">

                    <!-- Código -->
                    <div class="col-md-6">
                        <label for="codigo" class="form-label"><strong>Código:</strong></label>
                        <input type="text" name="codigo" id="codigo" class="form-control" value="{{ old('codigo') }}" maxlength="20" oninput="validarCampos()">
                        @error('codigo')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                        <small id="codigoError" class="text-danger d-none">El código no puede estar vacío y debe ser único.</small>
                        <small id="codigoNumero" class="text-danger d-none">El código debe ser solamente de números y máximo 20 caracteres.</small>
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label"><strong>Nombre:</strong></label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" maxlength="60" oninput="validarCampos()">
                        @error('nombre')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                        <small id="nombreError" class="text-danger d-none">El nombre no puede estar vacío y debe ser único.</small>
                        <small id="nombreNumError" class="text-danger d-none">El nombre solo debe contener letras y un máximo de 60 caracteres.</small>
                    </div>

                    <!-- Descripción -->
                    <div class="col-12">
                        <label for="descripcion" class="form-label"><strong>Descripción:</strong></label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control" maxlength="200" oninput="validarDescripcion(this)">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                        <small id="descripcionError" class="text-danger d-none">La descripción no debe exceder los 200 caracteres.</small>
                    </div>

                    <!-- Imagen -->
                    <div class="col-md-6">
                        <label for="img_path" class="form-label"><strong>Imagen:</strong></label>
                        <input type="file" name="img_path" id="img_path" class="form-control" accept="image/*">
                        @error('img_path')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Marca con botón "+" -->
                    <div class="col-md-6">
                        <label for="marca_id" class="form-label"><strong>Marca:</strong></label>
                        <div class="input-group">
                            <select name="marca_id" id="marca_id" class="form-control show-tick" data-live-search="true" onchange="validarCampos()">
                                <option value="">Seleccione una marca</option>
                                @foreach ($marcas as $item)
                                    <option value="{{ $item->id }}" {{ old('marca_id') == $item->id ? 'selected' : '' }}>{{ $item->nombre }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addMarcaModal">+</button>
                        </div>
                        @error('marca_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                        <small id="marcaError" class="text-danger d-none">Debe seleccionar una marca.</small>
                    </div>

                    <!-- Fabricante con botón "+" -->
                    <div class="col-md-6">
                        <label for="fabricante_id" class="form-label"><strong>Fabricante:</strong></label>
                        <div class="input-group">
                            <select name="fabricante_id" id="fabricante_id" class="form-control show-tick" data-live-search="true" onchange="validarCampos()">
                                <option value="">Seleccione un fabricante</option>
                                @foreach ($fabricantes as $item)
                                    <option value="{{ $item->id }}" {{ old('fabricante_id') == $item->id ? 'selected' : ''}}>{{ $item->nombre }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addFabricanteModal">+</button>
                        </div>
                        @error('fabricante_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                        <small id="fabricanteError" class="text-danger d-none">Debe seleccionar un fabricante.</small>
                    </div>

                    <!-- Categorías con botón "+" (Selección Múltiple) -->
 <!-- Categorías con botón "+" -->
 <div class="col-md-6">
                        <label for="categorias" class="form-label"><strong>Categorías:</strong></label>
                        <div class="input-group">
                        <select name="categorias[]" id="categorias" class="form-control selectpicker" multiple data-live-search="true" onchange="validarCampos()">
    <option value="">Seleccione una categoria</option>
    @foreach ($categorias as $item)
        <option value="{{ $item->id }}" {{ (collect(old('categorias'))->contains($item->id)) ? 'selected' : '' }}>
            {{ $item->nombre }}
        </option>
    @endforeach
</select>

                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addCategoriaModal">+</button>
                        </div>
                        <small id="categoriasError" class="text-danger d-none">Debe seleccionar al menos una categoría.</small>
                    </div>

                </div>

                </div>
            </div>

            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Agregar Marca -->
<div class="modal fade" id="addMarcaModal" tabindex="-1" aria-labelledby="addMarcaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addMarcaForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMarcaModalLabel">Agregar Nueva Marca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="marca_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="marca_nombre" name="nombre" required maxlength="60">
                        <div class="invalid-feedback d-none" id="marca_nombre_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="marca_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="marca_descripcion" name="descripcion" rows="3" maxlength="200"></textarea>
                        <div class="invalid-feedback d-none" id="marca_descripcion_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Agregar Marca</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Agregar Fabricante -->
<div class="modal fade" id="addFabricanteModal" tabindex="-1" aria-labelledby="addFabricanteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addFabricanteForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFabricanteModalLabel">Agregar Nuevo Fabricante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fabricante_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="fabricante_nombre" name="nombre" required maxlength="60">
                        <div class="invalid-feedback d-none" id="fabricante_nombre_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="fabricante_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="fabricante_descripcion" name="descripcion" rows="3" maxlength="200"></textarea>
                        <div class="invalid-feedback d-none" id="fabricante_descripcion_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Agregar Fabricante</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Agregar Categoría -->
<div class="modal fade" id="addCategoriaModal" tabindex="-1" aria-labelledby="addCategoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addCategoriaForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoriaModalLabel">Agregar Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoria_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="categoria_nombre" name="nombre" required maxlength="60">
                        <div class="invalid-feedback d-none" id="categoria_nombre_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="categoria_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="categoria_descripcion" name="descripcion" rows="3" maxlength="200"></textarea>
                        <div class="invalid-feedback d-none" id="categoria_descripcion_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Agregar Categoría</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    function validarCampos() {
        const codigoInput = document.getElementById("codigo");
        const nombreInput = document.getElementById("nombre");
        const marcaInput = document.getElementById("marca_id");
        const fabricanteInput = document.getElementById("fabricante_id");
        const categoriasInput = document.getElementById("categorias");

        const codigoError = document.getElementById("codigoError");
        const codigoNumero = document.getElementById("codigoNumero");
        const nombreError = document.getElementById("nombreError");
        const nombreNumError = document.getElementById("nombreNumError");
        const marcaError = document.getElementById("marcaError");
        const fabricanteError = document.getElementById("fabricanteError");
        const categoriasError = document.getElementById("categoriasError");

        const submitButton = document.getElementById("submitBtn");

        // Expresiones regulares para validaciones
        const regexCodigo = /^[0-9,-]+$/;
        const regexNombre = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/;

        let valid = true;

        // Validación del campo "Código"
        if (codigoInput.value.trim() === '') {
            codigoError.classList.remove("d-none");
            codigoNumero.classList.add("d-none");
            codigoInput.classList.add("is-invalid");
            codigoInput.classList.remove("is-valid");
            valid = false;
        } else if (!regexCodigo.test(codigoInput.value) || codigoInput.value.length > 20) {
            codigoError.classList.add("d-none");
            codigoNumero.classList.remove("d-none");
            codigoInput.classList.add("is-invalid");
            codigoInput.classList.remove("is-valid");
            valid = false;
        } else {
            codigoError.classList.add("d-none");
            codigoNumero.classList.add("d-none");
            codigoInput.classList.remove("is-invalid");
            codigoInput.classList.add("is-valid");
        }

        // Validación del campo "Nombre"
        if (nombreInput.value.trim() === '') {
            nombreError.classList.remove("d-none");
            nombreNumError.classList.add("d-none");
            nombreInput.classList.add("is-invalid");
            nombreInput.classList.remove("is-valid");
            valid = false;
        } else if (!regexNombre.test(nombreInput.value) || nombreInput.value.length > 60) {
            nombreError.classList.add("d-none");
            nombreNumError.classList.remove("d-none");
            nombreInput.classList.add("is-invalid");
            nombreInput.classList.remove("is-valid");
            valid = false;
        } else {
            nombreError.classList.add("d-none");
            nombreNumError.classList.add("d-none");
            nombreInput.classList.remove("is-invalid");
            nombreInput.classList.add("is-valid");
        }

        // Validación de "Marca"
        if (marcaInput.value === '') {
            marcaError.classList.remove("d-none");
            marcaInput.classList.add("is-invalid");
            marcaInput.classList.remove("is-valid");
            valid = false;
        } else {
            marcaError.classList.add("d-none");
            marcaInput.classList.remove("is-invalid");
            marcaInput.classList.add("is-valid");
        }

        // Validación de "Fabricante"
        if (fabricanteInput.value === '') {
            fabricanteError.classList.remove("d-none");
            fabricanteInput.classList.add("is-invalid");
            fabricanteInput.classList.remove("is-valid");
            valid = false;
        } else {
            fabricanteError.classList.add("d-none");
            fabricanteInput.classList.remove("is-invalid");
            fabricanteInput.classList.add("is-valid");
        }

      // Validación de "Categorías"
      if (categoriasInput.value === '') {
            categoriasError.classList.remove("d-none");
            categoriasInput.classList.add("is-invalid");
            categoriasInput.classList.remove("is-valid");
            valid = false;
        } else {
            categoriasError.classList.add("d-none");
            categoriasInput.classList.remove("is-invalid");
            categoriasInput.classList.add("is-valid");
        }

        submitButton.disabled = !valid;
    }

    function validarDescripcion(textarea) {
        const errorMessage = document.getElementById('descripcionError');
        const submitButton = document.getElementById('submitBtn');

        if (textarea.value.length <= 200) {
            errorMessage.classList.add('d-none');
        } else {
            errorMessage.classList.remove('d-none');
            submitButton.disabled = true;
        }
    }

    window.onload = function() {
        validarCampos();
        validarDescripcion(document.getElementById('descripcion'));
    }

    // Función para resetear formularios de los modales
    function resetModalForm(formId) {
        const form = document.getElementById(formId);
        form.reset();
        // Quitar clases de validación
        $(form).find('.is-invalid').removeClass('is-invalid');
        $(form).find('.is-valid').removeClass('is-valid');
        // Ocultar mensajes de error
        $(form).find('.invalid-feedback').text('').addClass('d-none');
        // Remover alertas si las hay
        $(form).find('.alert').remove();
    }

    $(document).ready(function() {
        // Inicializa los selectpickers una sola vez
        $('.selectpicker').selectpicker();

        // Validar campos al cargar la página (especialmente útil para edición)
        validarCampos();
    });

    // Manejar envío del formulario para agregar Marca
    $('#addMarcaForm').on('submit', function(e) {
        e.preventDefault();
        const nombre = $('#marca_nombre').val();
        const descripcion = $('#marca_descripcion').val();

        $.ajax({
            url: "{{ route('marcas.store') }}",
            method: "POST",
            data: {
                nombre: nombre,
                descripcion: descripcion,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Agrega la nueva marca solo si no existe
                if (!$("#marca_id option[value='" + response.id + "']").length) {
                    $('#marca_id').append(new Option(response.nombre, response.id));
                }

                // Elimina duplicados antes de refrescar el selectpicker
                eliminarDuplicados('marca_id');

                // Refresca el selectpicker
                //$('#marca_id').selectpicker('refresh');

                // Cierra el modal y resetea el formulario
                $('#addMarcaModal').modal('hide');
                resetModalForm('addMarcaForm');
                validarCampos();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.nombre) {
                        $('#marca_nombre').addClass('is-invalid');
                        $('#marca_nombre_error').text(errors.nombre[0]).removeClass('d-none');
                    }
                    if (errors.descripcion) {
                        $('#marca_descripcion').addClass('is-invalid');
                        $('#marca_descripcion_error').text(errors.descripcion[0]).removeClass('d-none');
                    }
                } else {
                    // Mostrar mensaje de error dentro del modal
                    if (!$(this).find('.alert').length) {
                        $(this).prepend('<div class="alert alert-danger">Ocurrió un error al agregar la marca.</div>');
                    }
                }
            }
        });
    });

    function eliminarDuplicados(selectId) {
        let valoresUnicos = new Set();
        $(`#${selectId} option`).each(function() {
            if (valoresUnicos.has(this.value)) {
                $(this).remove();
            } else {
                valoresUnicos.add(this.value);
            }
        });
    }


    // Manejar envío del formulario para agregar Fabricante
    $('#addFabricanteForm').on('submit', function(e) {
        e.preventDefault();
        const nombre = $('#fabricante_nombre').val();
        const descripcion = $('#fabricante_descripcion').val();

        $.ajax({
            url: "{{ route('fabricantes.store') }}",
            method: "POST",
            data: {
                nombre: nombre,
                descripcion: descripcion,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Verificar si el fabricante ya existe en el select
                if (!$("#fabricante_id option[value='" + response.id + "']").length) {
                    $('#fabricante_id').append(new Option(response.nombre, response.id));
                }
                eliminarDuplicados('fabricante_id');

                // Refresca el selectpicker
                //$('#fabricante_id').selectpicker('refresh');

                // Cerrar el modal y resetear formulario
                $('#addFabricanteModal').modal('hide');
                resetModalForm('addFabricanteForm');
                validarCampos();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.nombre) {
                        $('#fabricante_nombre').addClass('is-invalid');
                        $('#fabricante_nombre_error').text(errors.nombre[0]).removeClass('d-none');
                    }
                    if (errors.descripcion) {
                        $('#fabricante_descripcion').addClass('is-invalid');
                        $('#fabricante_descripcion_error').text(errors.descripcion[0]).removeClass('d-none');
                    }
                } else {
                    // Mostrar mensaje de error dentro del modal
                    if (!$(this).find('.alert').length) {
                        $(this).prepend('<div class="alert alert-danger">Ocurrió un error al agregar el fabricante.</div>');
                    }
                }
            }
        });
    });

    // Manejar envío del formulario para agregar Categoría
    $('#addCategoriaForm').on('submit', function(e) {
        e.preventDefault();
        const nombre = $('#categoria_nombre').val();
        const descripcion = $('#categoria_descripcion').val();

        $.ajax({
            url: "{{ route('categorias.store') }}",
            method: "POST",
            data: {
                nombre: nombre,
                descripcion: descripcion,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Verificar si la categoría ya existe en el select
                if (!$("#categorias option[value='" + response.id + "']").length) {
                    $('#categorias').append(new Option(response.nombre, response.id));
                }

                eliminarDuplicados('categorias');

                // Refresca el selectpicker
                //$('#categorias').selectpicker('refresh');

                // Cerrar el modal y resetear formulario
                $('#addCategoriaModal').modal('hide');
                resetModalForm('addCategoriaForm');
                validarCampos();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.nombre) {
                        $('#categoria_nombre').addClass('is-invalid');
                        $('#categoria_nombre_error').text(errors.nombre[0]).removeClass('d-none');
                    }
                    if (errors.descripcion) {
                        $('#categoria_descripcion').addClass('is-invalid');
                        $('#categoria_descripcion_error').text(errors.descripcion[0]).removeClass('d-none');
                    }
                } else {
                    // Mostrar mensaje de error dentro del modal
                    if (!$(this).find('.alert').length) {
                        $(this).prepend('<div class="alert alert-danger">Ocurrió un error al agregar la categoría.</div>');
                    }
                }
            }
        });
    });


    // Resetear formularios cuando se cierran los modales
    $('#addMarcaModal, #addFabricanteModal, #addCategoriaModal').on('hidden.bs.modal', function() {
        const formId = $(this).find('form').attr('id');
        resetModalForm(formId);
    });
</script>
@endpush
