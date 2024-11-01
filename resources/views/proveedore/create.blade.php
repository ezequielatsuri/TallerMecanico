@extends('layouts.app')

@section('title','Crear proveedor')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<style>
    #box-razon-social {
        display: none;
    }
    .error-message {
        color: red;
        font-size: 0.875rem;
    }
</style>
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

                    <!----Tipo de persona----->
                    <div class="col-md-6">
                        <label for="tipo_persona" class="form-label"><strong>Tipo de proveedor:</strong></label>
                        <select class="form-select" name="tipo_persona" id="tipo_persona" required>
                            <option value="" selected disabled>Seleccione una opción</option>
                            <option value="fisica" {{ old('tipo_persona') == 'fisica' ? 'selected' : '' }}>Persona física</option>
                            <option value="moral" {{ old('tipo_persona') == 'moral' ? 'selected' : '' }}>Persona Moral</option>
                        </select>
                        @error('tipo_persona')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-------Razón social------->
                    <div class="col-12" id="box-razon-social">
                        <label id="label-fisica" for="razon_social" class="form-label"><strong>Nombres y apellidos:</strong></label>
                        <label id="label-moral" for="razon_social" class="form-label"><strong>Nombre de la empresa:</strong></label>
                        <input required type="text" name="razon_social" id="razon_social" class="form-control" value="{{ old('razon_social') }}">
                        <small class="error-message" id="razon_social_error"></small>
                        @error('razon_social')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!------Dirección---->
                    <div class="col-12">
                        <label for="direccion" class="form-label"><strong>Dirección:</strong></label>
                        <input required type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion') }}">
                        <small class="error-message" id="direccion_error"></small>
                        @error('direccion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!--------------Documento------->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label"><strong>Tipo de documento:</strong></label>
                        <select class="form-select" name="documento_id" id="documento_id" required>
                            <option value="" selected disabled>Seleccione una opción</option>
                            @foreach ($documentos as $item)
                            <option value="{{$item->id}}" {{ old('documento_id') == $item->id ? 'selected' : '' }}>{{$item->tipo_documento}}</option>
                            @endforeach
                        </select>
                        @error('documento_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="numero_documento" class="form-label"><strong>Número de documento:</strong></label>
                        <input required type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento') }}">
                        <small class="error-message" id="numero_documento_error"></small>
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
   $(document).ready(function() {
    // Función para verificar si hay errores en los campos
    function toggleSubmitButton() {
        const errors = $('.error-message').filter(function() {
            return $(this).text().length > 0;
        });
        $('#submitBtn').prop('disabled', errors.length > 0);
    }

    $('#tipo_persona').on('change', function() {
        let selectValue = $(this).val();
        if (selectValue == 'fisica') {
            $('#label-moral').hide();
            $('#label-fisica').show();
        } else {
            $('#label-fisica').hide();
            $('#label-moral').show();
        }
        $('#box-razon-social').show(); // Asegurarte de que este se muestra
        toggleSubmitButton(); // Verificar el estado del botón
    });

    // Validación en tiempo real para razón social
    $('#razon_social').on('input', function() {
        const razonSocialValue = $(this).val();
        const razonSocialPattern = /^[A-Za-z\s]+$/;

        if (!razonSocialPattern.test(razonSocialValue) || razonSocialValue.length > 60) {
            $('#razon_social_error').text('La razón social solo debe contener letras y un máximo de 60 caracteres.');
        } else {
            $('#razon_social_error').text('');
        }
        toggleSubmitButton(); // Verificar el estado del botón
    });

    // Validación en tiempo real para dirección
    $('#direccion').on('input', function() {
        const direccionValue = $(this).val();
        // Actualizar la expresión regular para permitir caracteres especiales como #
        const direccionPattern = /^[A-Za-z0-9\s#,.]+$/;

        if (!direccionPattern.test(direccionValue) || direccionValue.length > 80) {
            $('#direccion_error').text('La dirección solo debe contener letras, números, espacios y caracteres especiales (#,.).');
        } else {
            $('#direccion_error').text('');
        }
        toggleSubmitButton(); // Verificar el estado del botón
    });

    // Validación en tiempo real para número de documento
    $('#numero_documento').on('input', function() {
        const numeroDocumentoValue = $(this).val();
        const numeroDocumentoPattern = /^[0-9,-]+$/;

        if (!numeroDocumentoPattern.test(numeroDocumentoValue)) {
            $('#numero_documento_error').text('El número de documento solo debe contener números');
        } else {
            $('#numero_documento_error').text('');
        }
        toggleSubmitButton(); // Verificar el estado del botón
    });
});
</script>
@endpush
