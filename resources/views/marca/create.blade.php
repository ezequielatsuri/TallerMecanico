@extends('layouts.app')

@section('title','Crear marca')

@push('css')
<style>
    #descripcion {
        resize: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Marca</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('marcas.index')}}">Marcas</a></li>
        <li class="breadcrumb-item active">Crear Marca</li>
    </ol>

    <div class="card">
        <form action="{{ route('marcas.store') }}" method="post" id="marcaForm">
            @csrf
            <div class="card-body text-bg-light">

                <div class="row g-4">

                    <div class="col-md-6">
                        <label for="nombre" class="form-label"><strong>Nombre:</strong></label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" maxlength="60" oninput="validarNombre(this)">
                        @error('nombre')
                        <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                        <small id="nombreError" class="text-danger d-none">El nombre solo debe contener letras y un máximo de 60 caracteres.</small>
                    </div>

                    <div class="col-7">
                        <label for="descripcion" class="form-label"><strong>Descripción:</strong></label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control" maxlength="255" oninput="validarDescripcion(this)">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                        <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                        <small id="descripcionError" class="text-danger d-none">La descripción no debe exceder los 255 caracteres.</small>
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
<script>
    function validarNombre(input) {
        const errorMessage = document.getElementById('nombreError');
        const submitButton = document.getElementById('submitBtn');

        // Expresión regular para permitir solo letras (incluyendo letras acentuadas)
        const regex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/;

        if (input.value.length <= 60 && regex.test(input.value)) { // Cambiar 60 a 10
            errorMessage.classList.add('d-none'); // Ocultar el mensaje de error
            submitButton.disabled = false; // Habilitar el botón de guardar
        } else {
            errorMessage.classList.remove('d-none'); // Mostrar el mensaje de error
            submitButton.disabled = true; // Deshabilitar el botón de guardar
        }
    }

    function validarDescripcion(textarea) {
        const errorMessage = document.getElementById('descripcionError');
        const submitButton = document.getElementById('submitBtn');

        if (textarea.value.length <= 255) {
            errorMessage.classList.add('d-none'); // Ocultar el mensaje de error
            // Verificar el campo nombre
            if (document.getElementById('nombre').value.length <= 10 && /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/.test(document.getElementById('nombre').value)) {
                submitButton.disabled = false; // Habilitar el botón de guardar
            }
        } else {
            errorMessage.classList.remove('d-none'); // Mostrar el mensaje de error
            submitButton.disabled = true; // Deshabilitar el botón de guardar
        }
    }

    // Validación inicial al cargar la página
    window.onload = function() {
        validarNombre(document.getElementById('nombre'));
        validarDescripcion(document.getElementById('descripcion'));
    }
</script>
@endpush
