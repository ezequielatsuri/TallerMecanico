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
                        <input type="text" name="codigo" id="codigo" class="form-control" value="{{old('codigo')}}" oninput="validarCodigo(this)">
                        @error('codigo')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="codigoError" class="text-danger d-none">El código es requerido y debe ser único.</small>
                    </div>

                    <!---Nombre---->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label"><strong>Nombre:</strong></label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}" oninput="validarNombre(this)">
                        @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="nombreError" class="text-danger d-none">El nombre es requerido y debe ser único.</small>
                        <small id="nombreNumError" class="text-danger d-none">El nombre no debe contener números.</small>
                    </div>

                    <!---Descripción---->
                    <div class="col-12">
                        <label for="descripcion" class="form-label"><strong>Descripción:</strong></label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control" oninput="validarDescripcion(this)">{{old('descripcion')}}</textarea>
                        @error('descripcion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="descripcionError" class="text-danger d-none">La descripción no debe exceder los 255 caracteres.</small>
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
    function validarCodigo(input) {
        const errorMessage = document.getElementById('codigoError');
        const submitButton = document.getElementById('submitBtn');

        // El código debe ser único y no puede estar vacío
        if (input.value.trim() !== '') {
            errorMessage.classList.add('d-none'); // Ocultar el mensaje de error
            submitButton.disabled = false; // Habilitar el botón de guardar
        } else {
            errorMessage.classList.remove('d-none'); // Mostrar el mensaje de error
            submitButton.disabled = true; // Deshabilitar el botón de guardar
        }
    }

    function validarNombre(input) {
        const errorMessage = document.getElementById('nombreError');
        const errorNumMessage = document.getElementById('nombreNumError');
        const submitButton = document.getElementById('submitBtn');

        // Expresión regular para permitir solo letras (incluyendo letras acentuadas)
        const regex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/;

        if (input.value.trim() === '') {
            errorMessage.classList.remove('d-none'); // Mostrar el mensaje de error
            errorNumMessage.classList.add('d-none'); // Ocultar mensaje de números
            submitButton.disabled = true; // Deshabilitar el botón de guardar
        } else if (!regex.test(input.value)) {
            errorNumMessage.classList.remove('d-none'); // Mostrar mensaje de números
            errorMessage.classList.add('d-none'); // Ocultar mensaje de error
            submitButton.disabled = true; // Deshabilitar el botón de guardar
        } else {
            errorMessage.classList.add('d-none'); // Ocultar el mensaje de error
            errorNumMessage.classList.add('d-none'); // Ocultar mensaje de números
            submitButton.disabled = false; // Habilitar el botón de guardar
        }
    }

    function validarDescripcion(textarea) {
        const errorMessage = document.getElementById('descripcionError');
        const submitButton = document.getElementById('submitBtn');

        if (textarea.value.length <= 255) {
            errorMessage.classList.add('d-none'); // Ocultar el mensaje de error
            submitButton.disabled = false; // Habilitar el botón de guardar
        } else {
            errorMessage.classList.remove('d-none'); // Mostrar el mensaje de error
            submitButton.disabled = true; // Deshabilitar el botón de guardar
        }
    }

    // Validación inicial al cargar la página
    window.onload = function() {
        validarCodigo(document.getElementById('codigo'));
        validarNombre(document.getElementById('nombre'));
        validarDescripcion(document.getElementById('descripcion'));
    }
</script>
@endpush
