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

    <div class="card">
        <form action="{{ route('productos.store') }}" method="post" enctype="multipart/form-data" id="productoForm">
            @csrf
            <div class="card-body text-bg-light">

                <div class="row g-4">

                    <!-- Código -->
                    <div class="col-md-6">
                        <label for="codigo" class="form-label"><strong>Código:</strong></label>
                        <input type="text" name="codigo" id="codigo" class="form-control" value="{{old('codigo')}}" maxlength="20" oninput="validarCampos()">
                        @error('codigo')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="codigoError" class="text-danger d-none">El código no puede estar vacío y debe ser único.</small>
                        <small id="codigoNumero" class="text-danger d-none">El código debe ser solamente de números y máximo 20 caracteres.</small>
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label"><strong>Nombre:</strong></label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}" maxlength="60" oninput="validarCampos()">
                        @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="nombreError" class="text-danger d-none">El nombre no puede estar vacío y debe ser único.</small>
                        <small id="nombreNumError" class="text-danger d-none">El nombre solo debe contener letras y un máximo de 60 caracteres.</small>
                    </div>

                    <!-- Descripción -->
                    <div class="col-12">
                        <label for="descripcion" class="form-label"><strong>Descripción:</strong></label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control" maxlength="200" oninput="validarDescripcion(this)">{{old('descripcion')}}</textarea>
                        @error('descripcion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="descripcionError" class="text-danger d-none">La descripción no debe exceder los 200 caracteres.</small>
                    </div>

                    <!-- Imagen -->
                    <div class="col-md-6">
                        <label for="img_path" class="form-label"><strong>Imagen:</strong></label>
                        <input type="file" name="img_path" id="img_path" class="form-control" accept="image/*">
                        @error('img_path')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-- Marca -->
                    <div class="col-md-6">
                        <label for="marca_id" class="form-label"><strong>Marca:</strong></label>
                        <select name="marca_id" id="marca_id" class="form-control selectpicker show-tick" data-live-search="true" onchange="validarCampos()">
                            <option value="">Seleccione una marca</option>
                            @foreach ($marcas as $item)
                                <option value="{{$item->id}}" {{ old('marca_id') == $item->id ? 'selected' : '' }}>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        <small id="marcaError" class="text-danger d-none">Debe seleccionar una marca.</small>
                    </div>

                    <!-- Fabricante -->
                    <div class="col-md-6">
                        <label for="fabricante_id" class="form-label"><strong>Fabricante:</strong></label>
                        <select name="fabricante_id" id="fabricante_id" class="form-control selectpicker show-tick" data-live-search="true" onchange="validarCampos()">
                            <option value="">Seleccione un fabricante</option>
                            @foreach ($fabricantes as $item)
                                <option value="{{$item->id}}" {{ old('fabricante_id') == $item->id ? 'selected' : '' }}>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        <small id="fabricanteError" class="text-danger d-none">Debe seleccionar un fabricante.</small>
                    </div>

                    <!-- Categorías -->
                    <div class="col-md-6">
                        <label for="categorias" class="form-label"><strong>Categorías:</strong></label>
                        <select name="categorias[]" id="categorias" class="form-control selectpicker show-tick" data-live-search="true" multiple onchange="validarCampos()">
                            @foreach ($categorias as $item)
                                <option value="{{$item->id}}" {{ (in_array($item->id , old('categorias',[]))) ? 'selected' : '' }}>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        <small id="categoriasError" class="text-danger d-none">Debe seleccionar al menos una categoría.</small>
                    </div>

                </div>
            </div>

            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Guardar</button>
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
        if (categoriasInput.selectedOptions.length === 0) {
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
</script>
@endpush
