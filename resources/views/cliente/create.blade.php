@extends('layouts.app')

@section('title', 'Crear Cliente')

@push('css')
<style>
    #descripcion {
        resize: none;
    }
    .error-message {
        margin-bottom: 10px;
        display: block;
    }
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
    <h1 class="mt-4 text-center">Crear Cliente</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
        <li class="breadcrumb-item active">Crear cliente</li>
    </ol>

    <div class="card">
        <form action="{{ route('clientes.store') }}" method="post" id="clienteForm">
            @csrf
            <div class="card-body text-bg-light">
                <div class="row g-3">

                    <!-- Tipo de cliente -->
                    <div class="col-md-6">
                        <label for="tipo_persona" class="form-label"><strong>Tipo de cliente:</strong></label>
                        <select class="form-select" name="tipo_persona" id="tipo_persona" onchange="mostrarCamposPersona()">
                            <option value="" selected disabled>Seleccione una opción</option>
                            <option value="fisica" {{ old('tipo_persona') == 'fisica' ? 'selected' : '' }}>Persona Física</option>
                            <option value="moral" {{ old('tipo_persona') == 'moral' ? 'selected' : '' }}>Persona Moral</option>
                        </select>
                        @error('tipo_persona')
                        <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Información para Persona Física -->
                    <div class="col-12" id="fisica" style="display: none;">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" maxlength="20" oninput="validarCampos()">
                        <small id="nombreErrorVacio" class="text-danger d-none error-message">El nombre no puede estar vacío.</small>
                        <small id="nombreErrorFormato" class="text-danger d-none error-message">El nombre solo debe contener letras y un máximo de 20 caracteres.</small>

                        <label for="apellidoP" class="form-label">Apellido Paterno:</label>
                        <input type="text" name="apellidoP" id="apellidoP" class="form-control" value="{{ old('apellidoP') }}" maxlength="20" oninput="validarCampos()">
                        <small id="apellidoPErrorVacio" class="text-danger d-none error-message">El apellido paterno no puede estar vacío.</small>
                        <small id="apellidoPErrorFormato" class="text-danger d-none error-message">El apellido paterno solo debe contener letras y un máximo de 20 caracteres.</small>

                        <label for="apellidoM" class="form-label">Apellido Materno:</label>
                        <input type="text" name="apellidoM" id="apellidoM" class="form-control" value="{{ old('apellidoM') }}" maxlength="20" oninput="validarCampos()">
                        <small id="apellidoMErrorVacio" class="text-danger d-none error-message">El apellido materno no puede estar vacío.</small>
                        <small id="apellidoMErrorFormato" class="text-danger d-none error-message">El apellido materno solo debe contener letras y un máximo de 20 caracteres.</small>
                    </div>

                    <!-- Información para Persona Moral -->
                    <div class="col-12" id="moral" style="display: none;">
                        <label for="razon_social" class="form-label">Nombre de la empresa:</label>
                        <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{ old('razon_social') }}" maxlength="60" oninput="validarCampos()">
                        <small id="razonSocialErrorVacio" class="text-danger d-none error-message">El nombre de la empresa no puede estar vacío.</small>
                        <small id="razonSocialErrorFormato" class="text-danger d-none error-message">El nombre de la empresa solo debe contener letras y un máximo de 60 caracteres.</small>
                    </div>

                    <!-- Dirección -->
                    <div class="col-12">
                        <label for="direccion" class="form-label"><strong>Dirección:</strong></label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion') }}" maxlength="80" oninput="validarCampos()">
                        <small id="direccionError" class="text-danger d-none error-message">La dirección no puede estar vacía.</small>
                    </div>

                    <!-- Tipo de documento -->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label"><strong>Tipo de documento:</strong></label>
                        <select class="form-select" name="documento_id" id="documento_id" onchange="validarCampos()">
                            <option value="" selected disabled>Seleccione una opción</option>
                            @foreach ($documentos as $item)
                            <option value="{{ $item->id }}" {{ old('documento_id') == $item->id ? 'selected' : '' }}>{{ $item->tipo_documento }}</option>
                            @endforeach
                        </select>
                        <small id="documentoError" class="text-danger d-none error-message">Debe seleccionar un tipo de documento.</small>
                    </div>

                    <!-- Número de documento -->
                    <div class="col-md-6">
                        <label for="numero_documento" class="form-label"><strong>Número de documento:</strong></label>
                        <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento') }}" oninput="validarCampos()">
                        <small id="numeroDocumentoError" class="text-danger d-none error-message">El número de documento no puede estar vacío.</small>
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
    document.addEventListener('DOMContentLoaded', function() {
        var tipoPersonaSelect = document.getElementById("tipo_persona");
        var fisicaDiv = document.getElementById("fisica");
        var moralDiv = document.getElementById("moral");
        var nombreInput = document.getElementById("nombre");
        var apellidoPInput = document.getElementById("apellidoP");
        var apellidoMInput = document.getElementById("apellidoM");
        var razonSocialInput = document.getElementById("razon_social");

        tipoPersonaSelect.addEventListener("change", function() {
            var tipoPersona = this.value;

            fisicaDiv.style.display = "none";
            moralDiv.style.display = "none";

            if (tipoPersona === "fisica") {
                fisicaDiv.style.display = "block";
                updateRazonSocial();
            } else if (tipoPersona === "moral") {
                moralDiv.style.display = "block";
            }
        });

        function updateRazonSocial() {
            razonSocialInput.value = nombreInput.value + ' ' + apellidoPInput.value + ' ' + apellidoMInput.value;
        }

        [nombreInput, apellidoPInput, apellidoMInput].forEach(function(input) {
            input.addEventListener('input', function() {
                if (tipoPersonaSelect.value === "fisica") {
                    updateRazonSocial();
                }
            });
        });
    });
</script>
<script>
    function mostrarCamposPersona() {
        const tipoPersona = document.getElementById("tipo_persona").value;
        document.getElementById("fisica").style.display = tipoPersona === "fisica" ? "block" : "none";
        document.getElementById("moral").style.display = tipoPersona === "moral" ? "block" : "none";
        validarCampos();
    }

    function validarCampos() {
        const tipoPersona = document.getElementById("tipo_persona").value;
        const direccion = document.getElementById("direccion");
        const documentoId = document.getElementById("documento_id");
        const numeroDocumento = document.getElementById("numero_documento");

        const nombre = document.getElementById("nombre");
        const apellidoP = document.getElementById("apellidoP");
        const apellidoM = document.getElementById("apellidoM");
        const razonSocial = document.getElementById("razon_social");

        const nombreErrorVacio = document.getElementById("nombreErrorVacio");
        const nombreErrorFormato = document.getElementById("nombreErrorFormato");
        const apellidoPErrorVacio = document.getElementById("apellidoPErrorVacio");
        const apellidoPErrorFormato = document.getElementById("apellidoPErrorFormato");
        const apellidoMErrorVacio = document.getElementById("apellidoMErrorVacio");
        const apellidoMErrorFormato = document.getElementById("apellidoMErrorFormato");
        const razonSocialErrorVacio = document.getElementById("razonSocialErrorVacio");
        const razonSocialErrorFormato = document.getElementById("razonSocialErrorFormato");
        const direccionError = document.getElementById("direccionError");
        const documentoError = document.getElementById("documentoError");
        const numeroDocumentoError = document.getElementById("numeroDocumentoError");
        const submitButton = document.getElementById("submitBtn");

        const regexLetras = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/;
        let valid = true;

        if (tipoPersona === "fisica") {
            applyValidation(nombre, nombreErrorVacio, nombreErrorFormato, nombre.value.trim() !== "", regexLetras.test(nombre.value));
            applyValidation(apellidoP, apellidoPErrorVacio, apellidoPErrorFormato, apellidoP.value.trim() !== "", regexLetras.test(apellidoP.value));
            applyValidation(apellidoM, apellidoMErrorVacio, apellidoMErrorFormato, apellidoM.value.trim() !== "", regexLetras.test(apellidoM.value));
            valid = valid && nombre.classList.contains("is-valid") && apellidoP.classList.contains("is-valid") && apellidoM.classList.contains("is-valid");
        } else if (tipoPersona === "moral") {
            applyValidation(razonSocial, razonSocialErrorVacio, razonSocialErrorFormato, razonSocial.value.trim() !== "", regexLetras.test(razonSocial.value));
            valid = valid && razonSocial.classList.contains("is-valid");
        }

        applyValidation(direccion, direccionError, null, direccion.value.trim() !== "");
        applyValidation(documentoId, documentoError, null, documentoId.value !== "");
        applyValidation(numeroDocumento, numeroDocumentoError, null, numeroDocumento.value.trim() !== "");

        valid = valid && direccion.classList.contains("is-valid") && documentoId.classList.contains("is-valid") && numeroDocumento.classList.contains("is-valid");
        submitButton.disabled = !valid;
    }

    function applyValidation(element, emptyErrorElement, formatErrorElement, isNotEmpty, isValidFormat = true) {
        emptyErrorElement.classList.toggle("d-none", isNotEmpty);
        if (formatErrorElement) formatErrorElement.classList.toggle("d-none", isValidFormat || !isNotEmpty);
        
        if (isNotEmpty && isValidFormat) {
            element.classList.add("is-valid");
            element.classList.remove("is-invalid");
        } else {
            element.classList.add("is-invalid");
            element.classList.remove("is-valid");
        }
    }

    window.onload = function() {
        mostrarCamposPersona();
    }
</script>
@endpush
