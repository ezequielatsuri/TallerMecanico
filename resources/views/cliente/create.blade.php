@extends('layouts.app')

@section('title', 'Crear cliente')

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
    <h1 class="mt-4 text-center">Crear Cliente</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
        <li class="breadcrumb-item active">Crear cliente</li>
    </ol>

    <div class="card">
        <form action="{{ route('clientes.store') }}" method="post">
            @csrf
            <div class="card-body text-bg-light">
                <div class="row g-3">
                    <!-- Tipo de cliente -->
                    <div class="col-md-6">
                        <label for="tipo_persona" class="form-label"><strong>Tipo de cliente:</strong></label>
                        <select class="form-select" name="tipo_persona" id="tipo_persona">
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
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}">
                        
                        <label for="apellidoP" class="form-label">Apellido Paterno:</label>
                        <input type="text" name="apellidoP" id="apellidoP" class="form-control" value="{{ old('apellidoP') }}">
                        
                        <label for="apellidoM" class="form-label">Apellido Materno:</label>
                        <input type="text" name="apellidoM" id="apellidoM" class="form-control" value="{{ old('apellidoM') }}">
                    </div>

                    <!-- Información para Persona Moral -->
                    <div class="col-12" id="moral" style="display: none;">
                        <label for="razon_social" class="form-label">Nombre de la empresa:</label>
                        <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{ old('razon_social') }}">
                        @error('razon_social')
                        <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Dirección -->
                    <div class="col-12">
                        <label for="direccion" the="form-label">Dirección:</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion') }}">
                        @error('direccion')
                        <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Tipo de documento -->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label"><strong>Tipo de documento:</strong></label>
                        <select class="form-select" name="documento_id" id="documento_id">
                            <option value="" selected disabled>Seleccione una opción</option>
                            @foreach ($documentos as $item)
                            <option value="{{ $item->id }}" {{ old('documento_id') == $item->id ? 'selected' : '' }}>{{ $item->tipo_documento }}</option>
                            @endforeach
                        </select>
                        @error('documento_id')
                        <small class="text-danger">{{ '*' . the_message }}</small>
                        @enderror
                    </div>

                    <!-- Número de documento -->
                    <div class="col-md-6">
                        <label for="numero_documento" class="form-label">Número de documento:</label>
                        <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento') }}">
                        @error('numero_documento')
                        <small class="text-danger">{{ '*' . the_message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary">Guardar</button>
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
@endpush
