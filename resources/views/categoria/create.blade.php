@extends('layouts.app')

@section('title','Crear categoría')

@push('css')
<style>
    #descripcion {
        resize: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Categoría</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('categorias.index')}}">Categorías</a></li>
        <li class="breadcrumb-item active">Crear categoría</li>
    </ol>

    <div class="card text-bg-light">
        <form action="{{ route('categorias.store') }}" method="post" id="categoriaForm">
            @csrf
            <div class="card-body">
                <div class="row g-4">

                    <div class="col-md-6">
                        <label for="nombre" class="form-label"><strong>Nombre:</strong></label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}" maxlength="30" oninput="validarNombre(this)">
                        @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="nombreError" class="text-danger d-none">El nombre solo debe contener letras y un máximo de 30 caracteres.</small>
                    </div>

                    <div class="col-7">
                        <label for="descripcion" class="form-label"><strong>Descripción:</strong></label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control" maxlength="80">{{old('descripcion')}}</textarea>
                        @error('descripcion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="descripcionError" class="text-danger d-none">La descripción debe contener un máximo de 80 caracteres.</small>
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

        if (input.value.length <= 30 && regex.test(input.value)) {
            errorMessage.classList.add('d-none'); // Ocultar el mensaje de error
            submitButton.disabled = false; // Habilitar el botón de guardar
        } else {
            errorMessage.classList.remove('d-none'); // Mostrar el mensaje de error
            submitButton.disabled = true; // Deshabilitar el botón de guardar
        }
    }

    // Validar la longitud de la descripción (hasta 80 caracteres)
    document.getElementById('descripcion').addEventListener('input', function() {
        const descripcionError = document.getElementById('descripcionError');
        const submitButton = document.getElementById('submitBtn');

        if (this.value.length <= 80) {
            descripcionError.classList.add('d-none'); // Ocultar el mensaje de error
            if (document.getElementById('nombre').value.length <= 30 && /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/.test(document.getElementById('nombre').value)) {
                submitButton.disabled = false; // Habilitar el botón de guardar
            }
        } else {
            descripcionError.classList.remove('d-none'); // Mostrar el mensaje de error
            submitButton.disabled = true; // Deshabilitar el botón de guardar
        }
    });
</script>
@endpush
