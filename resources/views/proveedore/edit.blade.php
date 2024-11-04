@extends('layouts.app')

@section('title','Editar proveedor')

@push('css')

@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Proveedor</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('proveedores.index')}}">Proveedores</a></li>
        <li class="breadcrumb-item active">Editar proveedor</li>
    </ol>

    <div class="card text-bg-light">
        <form action="{{ route('proveedores.update',['proveedore'=>$proveedore]) }}" method="post">
            @method('PATCH')
            @csrf
            <div class="card-header">
                <p>Tipo de proveedor: <span class="fw-bold">{{ strtoupper($proveedore->persona->tipo_persona)}}</span></p>
            </div>
            <div class="card-body">

                <div class="row g-3">

                    <!-------Razón social------->
                    <div class="col-12">
                        @if ($proveedore->persona->tipo_persona == 'natural')
                        <label id="label-natural" for="razon_social" class="form-label">Nombres y apellidos:</label>
                        @else
                        <label id="label-juridica" for="razon_social" class="form-label">Nombre de la empresa:</label>
                        @endif

                        <input required type="text" name="razon_social" id="razon_social" class="form-control" value="{{old('razon_social',$proveedore->persona->razon_social)}}">
                        @error('razon_social')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!------Dirección---->
                    <div class="col-12">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input required type="text" name="direccion" id="direccion" class="form-control" value="{{old('direccion',$proveedore->persona->direccion)}}">
                        @error('direccion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!--------------Documento------->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label">Tipo de documento:</label>
                        <select class="form-select" name="documento_id" id="documento_id">
                            @foreach ($documentos as $item)
                            @if ($proveedore->persona->documento_id == $item->id)
                            <option selected value="{{$item->id}}" {{ old('documento_id') == $item->id ? 'selected' : '' }}>{{$item->tipo_documento}}</option>
                            @else
                            <option value="{{$item->id}}" {{ old('documento_id') == $item->id ? 'selected' : '' }}>{{$item->tipo_documento}}</option>
                            @endif
                            @endforeach
                        </select>
                        @error('documento_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="numero_documento" class="form-label">Número de documento:</label>
                        <input required type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{old('numero_documento',$proveedore->persona->numero_documento)}}">
                        @error('numero_documento')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>

            </div>
            <div class="card-footer text-center">
                <button type="submit" id="submit-button" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const razonSocialInput = document.getElementById("razon_social");
        const direccionInput = document.getElementById("direccion");
        const numeroDocumentoInput = document.getElementById("numero_documento");
        const submitButton = document.getElementById("submit-button");

        const razonSocialError = document.createElement("small");
        const direccionError = document.createElement("small");
        const numeroDocumentoError = document.createElement("small");

        razonSocialError.classList.add("text-danger");
        direccionError.classList.add("text-danger");
        numeroDocumentoError.classList.add("text-danger");

        // Función para verificar si hay errores
        function checkErrors() {
            const hasErrors = razonSocialInput.classList.contains("is-invalid") ||
                              direccionInput.classList.contains("is-invalid") ||
                              numeroDocumentoInput.classList.contains("is-invalid");
            submitButton.disabled = hasErrors;
        }

        // Validar campo de razón social
        razonSocialInput.addEventListener("input", function() {
            const regex = /^[A-Za-z\s]+$/; // Solo letras y espacios
            if (!regex.test(razonSocialInput.value) || razonSocialInput.value.length > 60) {
                razonSocialError.textContent = "La razón social solo debe contener letras y un máximo de 60 caracteres.";
                razonSocialInput.classList.add("is-invalid");
                razonSocialInput.parentNode.appendChild(razonSocialError);
            } else {
                razonSocialInput.classList.remove("is-invalid");
                razonSocialError.textContent = "";
            }
            checkErrors(); // Verifica errores al validar
        });

        // Validar campo de dirección
        direccionInput.addEventListener("input", function() {
            const regex = /^[A-Za-z0-9\s#,.]+$/; // Permitir letras, números, espacios y algunos símbolos
            if (!regex.test(direccionInput.value) || direccionInput.value.length > 80) {
                direccionError.textContent = "La dirección solo debe contener letras, números, espacios y caracteres especiales (#,.).";
                direccionInput.classList.add("is-invalid");
                direccionInput.parentNode.appendChild(direccionError);
            } else {
                direccionInput.classList.remove("is-invalid");
                direccionError.textContent = "";
            }
            checkErrors(); // Verifica errores al validar
        });

        // Validar campo de número de documento
        numeroDocumentoInput.addEventListener("input", function() {
            const regex = /^[0-9A-Za-z-]+$/; // Permitir letras, números y guiones
            if (!regex.test(numeroDocumentoInput.value)) {
                numeroDocumentoError.textContent = "El número de documento solo debe contener letras, números y guiones.";
                numeroDocumentoInput.classList.add("is-invalid");
                numeroDocumentoInput.parentNode.appendChild(numeroDocumentoError);
            } else {
                numeroDocumentoInput.classList.remove("is-invalid");
                numeroDocumentoError.textContent = "";
            }
            checkErrors(); // Verifica errores al validar
        });

        // Inicializar el estado del botón al cargar la página
        checkErrors();
    });
</script>
@endpush
