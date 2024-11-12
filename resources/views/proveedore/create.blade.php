@extends('layouts.app')

@section('title','Crear Proveedor')

@push('css')
<style>
    #box-razon-social {
        display: none;
    }
    .error-message {
        color: red;
        font-size: 0.875rem;
        display: none;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Proveedor</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('proveedores.index')}}">Proveedor</a></li>
        <li class="breadcrumb-item active">Crear proveedor</li>
    </ol>

    <div class="card text-bg-light">
        <form action="{{ route('proveedores.store') }}" method="post" id="createProviderForm">
            @csrf
            <div class="card-body">
                <div class="row g-3">

                    <!-- Tipo de persona -->
                    <div class="col-md-6">
                        <label for="tipo_persona" class="form-label"><strong>Tipo de proveedor:</strong></label>
                        <select class="form-select" name="tipo_persona" id="tipo_persona" onchange="mostrarCamposPersona()" required>
                            <option value="" selected disabled>Seleccione una opción</option>
                            <option value="fisica" {{ old('tipo_persona') == 'fisica' ? 'selected' : '' }}>Persona física</option>
                            <option value="moral" {{ old('tipo_persona') == 'moral' ? 'selected' : '' }}>Persona Moral</option>
                        </select>
                        @error('tipo_persona')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-- Razón social o Nombres y apellidos -->
                    <div class="col-12" id="box-razon-social">
                        <label id="label-fisica" for="razon_social" class="form-label"><strong>Nombres y apellidos:</strong></label>
                        <label id="label-moral" for="razon_social" class="form-label"><strong>Nombre de la empresa:</strong></label>
                        <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{ old('razon_social') }}" maxlength="60" oninput="validarCampos()">
                        <small class="error-message" id="razonSocialErrorVacio">El campo no puede estar vacío.</small>
                        <small class="error-message" id="razonSocialErrorFormato">Este campo solo debe contener letras y un maximo de 60 caracteres.</small>
                        @error('razon_social')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-- Dirección -->
                    <div class="col-12">
                        <label for="direccion" class="form-label"><strong>Dirección:</strong></label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion') }}" maxlength="80" oninput="validarCampos()">
                        <small class="error-message" id="direccionError">La dirección no puede estar vacía.</small>
                        @error('direccion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-- Tipo de documento -->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label"><strong>Tipo de documento:</strong></label>
                        <select class="form-select" name="documento_id" id="documento_id" onchange="validarCampos()" required>
                            <option value="" selected disabled>Seleccione una opción</option>
                            @foreach ($documentos as $item)
                            <option value="{{ $item->id }}" {{ old('documento_id') == $item->id ? 'selected' : '' }}>{{ $item->tipo_documento }}</option>
                            @endforeach
                        </select>
                        <small class="error-message" id="documentoError">Debe seleccionar un tipo de documento.</small>
                        @error('documento_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-- Número de documento -->
                    <div class="col-md-6">
                        <label for="numero_documento" class="form-label"><strong>Número de documento:</strong></label>
                        <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento') }}" oninput="validarCampos()">
                        <small class="error-message" id="numeroDocumentoError">El número de documento no puede estar vacío.</small>
                        @error('numero_documento')
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
    function mostrarCamposPersona() {
        const tipoPersona = document.getElementById("tipo_persona").value;
        document.getElementById("box-razon-social").style.display = tipoPersona ? "block" : "none";
        document.getElementById("label-fisica").style.display = tipoPersona === "fisica" ? "block" : "none";
        document.getElementById("label-moral").style.display = tipoPersona === "moral" ? "block" : "none";
        validarCampos();
    }

    function validarCampos() {
        const tipoPersona = document.getElementById("tipo_persona").value;
        const direccion = document.getElementById("direccion").value.trim();
        const documentoId = document.getElementById("documento_id").value;
        const numeroDocumento = document.getElementById("numero_documento").value.trim();

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
        if (razonSocial.value.trim() === "") {
            razonSocialErrorVacio.style.display = "block";
            razonSocialErrorFormato.style.display = "none";
            valid = false;
        } else if (!regexLetras.test(razonSocial.value)) {
            razonSocialErrorVacio.style.display = "none";
            razonSocialErrorFormato.style.display = "block";
            valid = false;
        } else {
            razonSocialErrorVacio.style.display = "none";
            razonSocialErrorFormato.style.display = "none";
        }

        // Validación de dirección
        direccionError.style.display = direccion === "" ? "block" : "none";
        valid = valid && direccion !== "";

        // Validación de tipo de documento
        documentoError.style.display = documentoId === "" ? "block" : "none";
        valid = valid && documentoId !== "";

        // Validación de número de documento
        numeroDocumentoError.style.display = numeroDocumento === "" ? "block" : "none";
        valid = valid && numeroDocumento !== "";

        // Habilitar o deshabilitar el botón de envío
        submitButton.disabled = !valid;
    }

    window.onload = function() {
        mostrarCamposPersona();
    }
</script>
@endpush
