@extends('layouts.app')

@section('title','Editar cliente')

@push('css')
<style>
    .error-message {
        color: red;
        font-size: 0.875rem;
        display: none;
    }
    .is-invalid {
        border: 2px solid red;
    }
    .is-valid {
        border: 2px solid green;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Cliente</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index')}}">Clientes</a></li>
        <li class="breadcrumb-item active">Editar cliente</li>
    </ol>

    <div class="card text-bg-light">
        <form action="{{ route('clientes.update',['cliente'=>$cliente]) }}" method="post" id="editClientForm">
            @method('PATCH')
            @csrf
            <div class="card-header">
                <p>Tipo de cliente: <span class="fw-bold">{{ strtoupper($cliente->persona->tipo_persona) }}</span></p>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    
                    <!-- Nombre o Razón social -->
                    <div class="col-12">
                        @if ($cliente->persona->tipo_persona == 'fisica')
                        <label for="razon_social" class="form-label">Nombres y apellidos:</label>
                        @else
                        <label for="razon_social" class="form-label">Nombre de la empresa:</label>
                        @endif
                        <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{ old('razon_social', $cliente->persona->razon_social) }}" maxlength="60" oninput="validarCampos()">
                        <small id="razonSocialErrorVacio" class="error-message">Este campo no puede estar vacío.</small>
                        <small id="razonSocialErrorFormato" class="error-message">Este campo solo debe contener letras y un máximo de 60 caracteres.</small>
                        @error('razon_social')
                        <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Dirección -->
                    <div class="col-12">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion', $cliente->persona->direccion) }}" oninput="validarCampos()">
                        <small id="direccionError" class="error-message">La dirección no puede estar vacía.</small>
                        @error('direccion')
                        <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Tipo de documento -->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label">Tipo de documento:</label>
                        <select class="form-select" name="documento_id" id="documento_id" onchange="validarCampos()">
                            <option value="" selected disabled>Seleccione una opción</option>
                            @foreach ($documentos as $item)
                            <option value="{{ $item->id }}" {{ (old('documento_id', $cliente->persona->documento_id) == $item->id) ? 'selected' : '' }}>{{ $item->tipo_documento }}</option>
                            @endforeach
                        </select>
                        <small id="documentoError" class="error-message">Debe seleccionar un tipo de documento.</small>
                        @error('documento_id')
                        <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Número de documento -->
                    <div class="col-md-6">
                        <label for="numero_documento" class="form-label">Número de documento:</label>
                        <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento', $cliente->persona->numero_documento) }}" oninput="validarCampos()">
                        <small id="numeroDocumentoError" class="error-message">El número de documento no puede estar vacío.</small>
                        @error('numero_documento')
                        <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" id="submitBtn" class="btn btn-primary" disabled>Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    function validarCampos() {
        const tipoPersona = "{{ $cliente->persona->tipo_persona }}";
        const direccion = document.getElementById("direccion");
        const documentoId = document.getElementById("documento_id");
        const numeroDocumento = document.getElementById("numero_documento");

        const razonSocial = document.getElementById("razon_social");
        const razonSocialErrorVacio = document.getElementById("razonSocialErrorVacio");
        const razonSocialErrorFormato = document.getElementById("razonSocialErrorFormato");
        const direccionError = document.getElementById("direccionError");
        const documentoError = document.getElementById("documentoError");
        const numeroDocumentoError = document.getElementById("numeroDocumentoError");
        const submitButton = document.getElementById("submitBtn");

        const regexLetras = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/;
        let valid = true;

        // Validación de "razón social" o "nombres y apellidos"
        applyValidation(razonSocial, razonSocialErrorVacio, razonSocialErrorFormato, razonSocial.value.trim() !== "", regexLetras.test(razonSocial.value));
        valid = valid && razonSocial.classList.contains("is-valid");

        // Validación de dirección
        applyValidation(direccion, direccionError, null, direccion.value.trim() !== "");
        valid = valid && direccion.classList.contains("is-valid");

        // Validación de tipo de documento
        applyValidation(documentoId, documentoError, null, documentoId.value !== "");
        valid = valid && documentoId.classList.contains("is-valid");

        // Validación de número de documento
        applyValidation(numeroDocumento, numeroDocumentoError, null, numeroDocumento.value.trim() !== "");
        valid = valid && numeroDocumento.classList.contains("is-valid");

        // Habilitar o deshabilitar el botón de envío
        submitButton.disabled = !valid;
    }

    function applyValidation(element, emptyErrorElement, formatErrorElement, isNotEmpty, isValidFormat = true) {
        emptyErrorElement.style.display = isNotEmpty ? "none" : "block";
        if (formatErrorElement) formatErrorElement.style.display = isValidFormat ? "none" : "block";
        
        if (isNotEmpty && isValidFormat) {
            element.classList.add("is-valid");
            element.classList.remove("is-invalid");
        } else {
            element.classList.add("is-invalid");
            element.classList.remove("is-valid");
        }
    }

    // Ejecutar la validación inicial cuando se carga la página
    window.onload = function() {
        validarCampos();
    }
</script>
@endpush
