@extends('layouts.app')

@section('title','Crear Producto')

@push('css')
<style>
    #descripcion {
        resize: none;
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

                    <!----Codigo---->
                    <div class="col-md-6">
                        <label for="codigo" class="form-label"><strong>Código:</strong></label>
                        <input type="text" name="codigo" id="codigo" class="form-control" value="{{old('codigo')}}" maxlength="20" oninput="validarCampos()">
                        @error('codigo')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="codigoError" class="text-danger d-none">El código no puede estar vacio y debe ser único.</small>
                        <small id="codigoNumero" class="text-danger d-none">El código debe ser solamente de números y maximo 20 numeros.</small>
                    </div>

                    <!---Nombre---->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label"><strong>Nombre:</strong></label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}" maxlength="60" oninput="validarCampos()">
                        @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="nombreError" class="text-danger d-none">El nombre no puede estar vacio y debe ser único.</small>
                        <small id="nombreNumError" class="text-danger d-none">El nombre solo debe contener letras y un maximo de 60 caracteres.</small>
                    </div>

                    <!---Descripción---->
                    <div class="col-12">
                        <label for="descripcion" class="form-label"><strong>Descripción:</strong></label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control" maxlength="200" oninput="validarDescripcion(this)">{{old('descripcion')}}</textarea>
                        @error('descripcion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="descripcionError" class="text-danger d-none">La descripción no debe exceder los 200 caracteres.</small>
                    </div>

                    <!---Imagen---->
                    <div class="col-md-6">
                        <label for="img_path" class="form-label"><strong>Imagen:</strong></label>
                        <input type="file" name="img_path" id="img_path" class="form-control" accept="image/*">
                        @error('img_path')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!---Marca---->
                    <div class="col-md-6">
                        <label for="marca_id" class="form-label"><strong>Marca:</strong></label>
                        <select data-size="4" title="Seleccione una marca" data-live-search="true" name="marca_id" id="marca_id" class="form-control selectpicker show-tick">
                            @foreach ($marcas as $item)
                            <option value="{{$item->id}}" {{ old('marca_id') == $item->id ? 'selected' : '' }}>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        @error('marca_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!---Fabricante---->
                    <div class="col-md-6">
                        <label for="fabricante_id" class="form-label"><strong>Fabricante:</strong></label>
                        <select data-size="4" title="Seleccione un fabricante" data-live-search="true" name="fabricante_id" id="fabricante_id" class="form-control selectpicker show-tick">
                            @foreach ($fabricantes as $item)
                            <option value="{{$item->id}}" {{ old('fabricante_id') == $item->id ? 'selected' : '' }}>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        @error('fabricante_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!---Categorías---->
                    <div class="col-md-6">
                        <label for="categorias" class="form-label"><strong>Categorías:</strong></label>
                        <select data-size="4" title="Seleccione las categorías" data-live-search="true" name="categorias[]" id="categorias" class="form-control selectpicker show-tick" multiple>
                            @foreach ($categorias as $item)
                            <option value="{{$item->id}}" {{ (in_array($item->id , old('categorias',[]))) ? 'selected' : '' }}>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        @error('categorias')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
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
        const codigoError = document.getElementById("codigoError");
        const codigoNumero = document.getElementById("codigoNumero");
        const nombreError = document.getElementById("nombreError");
        const nombreNumError = document.getElementById("nombreNumError");
        const submitButton = document.getElementById("submitBtn");

        // Expresiones regulares para validaciones
        const regexCodigo = /^[0-9,-]+$/;
        const regexNombre = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/;

        let valid = true;

        // Validación del campo "Código"
        if (codigoInput.value.trim() === '') {
            codigoError.classList.remove("d-none");
            codigoNumero.classList.add("d-none");
            valid = false;
        } else if (!regexCodigo.test(codigoInput.value)) {
            codigoError.classList.add("d-none");
            codigoNumero.classList.remove("d-none");
            valid = false;
        } else {
            codigoError.classList.add("d-none");
            codigoNumero.classList.add("d-none");
        }

        // Validación del campo "Nombre"
        if (nombreInput.value.trim() === '') {
            nombreError.classList.remove("d-none");
            nombreNumError.classList.add("d-none");
            valid = false;
        } else if (!regexNombre.test(nombreInput.value)) {
            nombreError.classList.add("d-none");
            nombreNumError.classList.remove("d-none");
            valid = false;
        } else {
            nombreError.classList.add("d-none");
            nombreNumError.classList.add("d-none");
        }

        // Habilitar o deshabilitar el botón de envío
        submitButton.disabled = !valid;
    }

    function validarDescripcion(textarea) {
        const errorMessage = document.getElementById('descripcionError');
        const submitButton = document.getElementById('submitBtn');

        if (textarea.value.length <= 200) {
            errorMessage.classList.add('d-none'); // Ocultar el mensaje de error
        } else {
            errorMessage.classList.remove('d-none'); // Mostrar el mensaje de error
            submitButton.disabled = true; // Deshabilitar el botón de guardar si la descripción es demasiado larga
        }
    }

    window.onload = function() {
        validarCampos();
        validarDescripcion(document.getElementById('descripcion'));
    }
</script>
@endpush
