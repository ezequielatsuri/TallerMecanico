@extends('layouts.app')

@section('title','Crear servicios')

@push('css')
<style>
    #descripcion {
        resize: none;
    }
    .is-invalid {
        border: 2px solid red;
    }
    .is-valid {
        border: 2px solid green;
    }
    .error-message {
        color: red;
        font-size: 0.875rem;
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Servicios</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('servicios.index')}}">Servicios</a></li>
        <li class="breadcrumb-item active">Crear Servicios</li>
    </ol>

    <div class="card">
        <form action="{{ route('servicios.store') }}" method="post" id="crearServicioForm">
            @csrf
            <div class="card-body text-bg-light">
                <div class="row g-4">
                    <!-- Código -->
                    <div class="col-md-6">
                        <label for="codigo" class="form-label"><strong>Código:</strong></label>
                        <input type="text" name="codigo" id="codigo" class="form-control" value="{{ old('codigo') }}" maxlength="10" oninput="validarCampos()">
                        <small id="codigoErrorVacio" class="error-message">El código no puede estar vacío.</small>
                        <small id="codigoErrorFormato" class="error-message">El código debe tener solo números y letras y máximo 10 caracteres.</small>
                        @error('codigo')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label"><strong>Nombre:</strong></label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" maxlength="50" oninput="validarCampos()">
                        <small id="nombreErrorVacio" class="error-message">El nombre no puede estar vacío.</small>
                        <small id="nombreErrorFormato" class="error-message">El nombre solo debe contener letras y un máximo de 50 caracteres.</small>
                        @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="col-12">
                        <label for="descripcion" class="form-label"><strong>Descripción:</strong></label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-- Precio -->
                    <div class="col-md-6">
                        <label for="precio" class="form-label"><strong>Precio de servicio:</strong></label>
                        <input type="number" name="precio" id="precio" class="form-control" step="0.01" value="{{ old('precio') }}" oninput="validarCampos()">
                        <small id="precioErrorVacio" class="error-message">El precio no puede estar vacío.</small>
                        <small id="precioErrorFormato" class="error-message">El precio debe ser un número mayor a 0.</small>
                        @error('precio')
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
<script>
    function validarCampos() {
        const codigo = document.getElementById("codigo");
        const nombre = document.getElementById("nombre");
        const precio = document.getElementById("precio");

        const codigoErrorVacio = document.getElementById("codigoErrorVacio");
        const codigoErrorFormato = document.getElementById("codigoErrorFormato");
        const nombreErrorVacio = document.getElementById("nombreErrorVacio");
        const nombreErrorFormato = document.getElementById("nombreErrorFormato");
        const precioErrorVacio = document.getElementById("precioErrorVacio");
        const precioErrorFormato = document.getElementById("precioErrorFormato");
        const submitButton = document.getElementById("submitBtn");

        const regexAlfaNumerico = /^[a-zA-Z0-9]+$/;
        const regexLetras = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/;
        let valid = true;

        // Validación del campo "Código"
        if (codigo.value.trim() === "") {
            codigoErrorVacio.style.display = "block";
            codigoErrorFormato.style.display = "none";
            codigo.classList.add("is-invalid");
            codigo.classList.remove("is-valid");
            valid = false;
        } else if (!regexAlfaNumerico.test(codigo.value)) {
            codigoErrorVacio.style.display = "none";
            codigoErrorFormato.style.display = "block";
            codigo.classList.add("is-invalid");
            codigo.classList.remove("is-valid");
            valid = false;
        } else {
            codigoErrorVacio.style.display = "none";
            codigoErrorFormato.style.display = "none";
            codigo.classList.add("is-valid");
            codigo.classList.remove("is-invalid");
        }

        // Validación del campo "Nombre"
        if (nombre.value.trim() === "") {
            nombreErrorVacio.style.display = "block";
            nombreErrorFormato.style.display = "none";
            nombre.classList.add("is-invalid");
            nombre.classList.remove("is-valid");
            valid = false;
        } else if (!regexLetras.test(nombre.value)) {
            nombreErrorVacio.style.display = "none";
            nombreErrorFormato.style.display = "block";
            nombre.classList.add("is-invalid");
            nombre.classList.remove("is-valid");
            valid = false;
        } else {
            nombreErrorVacio.style.display = "none";
            nombreErrorFormato.style.display = "none";
            nombre.classList.add("is-valid");
            nombre.classList.remove("is-invalid");
        }

        // Validación del campo "Precio"
        if (precio.value.trim() === "" || isNaN(precio.value)) {
            precioErrorVacio.style.display = "block";
            precioErrorFormato.style.display = "none";
            precio.classList.add("is-invalid");
            precio.classList.remove("is-valid");
            valid = false;
        } else if (parseFloat(precio.value) <= 0) {
            precioErrorVacio.style.display = "none";
            precioErrorFormato.style.display = "block";
            precio.classList.add("is-invalid");
            precio.classList.remove("is-valid");
            valid = false;
        } else {
            precioErrorVacio.style.display = "none";
            precioErrorFormato.style.display = "none";
            precio.classList.add("is-valid");
            precio.classList.remove("is-invalid");
        }

        submitButton.disabled = !valid;
    }

    window.onload = function() {
        validarCampos();
    }
</script>
@endpush
