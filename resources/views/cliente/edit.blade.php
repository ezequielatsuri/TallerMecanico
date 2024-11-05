@extends('layouts.app')

@section('title','Editar cliente')

@push('css')
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
        <form action="{{ route('clientes.update',['cliente'=>$cliente]) }}" method="post">
            @method('PATCH')
            @csrf
            <div class="card-header">
                <p>Tipo de cliente: <span class="fw-bold">{{ strtoupper($cliente->persona->tipo_persona)}}</span></p>
            </div>
            <div class="card-body">

                <div class="row g-3">

                    <!-------Razón social o Nombre de empresa ------->
                    <div class="col-12">
                        @if ($cliente->persona->tipo_persona == 'fisica')
                        <label id="label-fisica" for="razon_social" class="form-label">Nombres y apellidos:</label>
                        @else
                        <label id="label-moral" for="razon_social" class="form-label">Nombre de la empresa:</label>
                        @endif

                        <input required type="text" name="razon_social" id="razon_social" class="form-control" value="{{old('razon_social',$cliente->persona->razon_social)}}">

                        @error('razon_social')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!------Dirección---->
                    <div class="col-12">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input required type="text" name="direccion" id="direccion" class="form-control" value="{{old('direccion',$cliente->persona->direccion)}}">
                        @error('direccion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!--------------Documento------->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label">Tipo de documento:</label>
                        <select class="form-select" name="documento_id" id="documento_id">
                            @foreach ($documentos as $item)
                            @if ($cliente->persona->documento_id == $item->id)
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
                        <label for="numero_documento" class="form-label">Numero de documento:</label>
                        <input required type="text" name="numero_documento" id="numero_documento" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required value="{{old('numero_documento',$cliente->persona->numero_documento)}}">
                        @error('numero_documento')
                        <small class="text-danger">{{'*'.$message}}</small>
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
    $(document).ready(function() {
        console.log("jQuery está funcionando correctamente.");
        
        // Mostrar/Ocultar los labels según el tipo de cliente seleccionado
        $('#tipo_persona').on('change', function() {
            let selectValue = $(this).val();
            if (selectValue == 'fisica') {
                $('#label-moral').hide();
                $('#label-fisica').show();
            } else {
                $('#label-fisica').hide();
                $('#label-moral').show();
            }
        }).trigger('change'); // Ejecutar al cargar la página para mostrar el label adecuado

        // Validación de Razón Social / Nombres en tiempo real
        $('#razon_social').on('input', function() {
            let value = $(this).val();
            let isValid = /^[a-zA-Z\s]+$/.test(value); // Solo letras y espacios

            if (!isValid) {
                $(this).addClass('is-invalid');
                $('#razon_social-error').remove();
                $(this).after('<small id="razon_social-error" class="text-danger">Solo se permiten letras y espacios.</small>');
            } else {
                $(this).removeClass('is-invalid');
                $('#razon_social-error').remove();
            }
        });

        // Validación de Dirección en tiempo real
        $('#direccion').on('input', function() {
            if ($(this).val().length < 10) {
                $(this).addClass('is-invalid');
                $('#direccion-error').remove();
                $(this).after('<small id="direccion-error" class="text-danger">La dirección debe tener al menos 10 caracteres.</small>');
            } else {
                $(this).removeClass('is-invalid');
                $('#direccion-error').remove();
            }
        });

        // Validación del Número de Documento en tiempo real (solo números)
        $('#numero_documento').on('input', function() {
            let value = $(this).val();
            if (!/^\d+$/.test(value)) {
                $(this).addClass('is-invalid');
                $('#numero_documento-error').remove();
                $(this).after('<small id="numero_documento-error" class="text-danger">Solo se permiten números.</small>');
            } else {
                $(this).removeClass('is-invalid');
                $('#numero_documento-error').remove();
            }
        });

        // Validación al cambiar Tipo de Documento
        $('#documento_id').on('change', function() {
            if ($(this).val() === "") {
                $(this).addClass('is-invalid');
                $('#documento_id-error').remove();
                $(this).after('<small id="documento_id-error" class="text-danger">Debe seleccionar un tipo de documento.</small>');
            } else {
                $(this).removeClass('is-invalid');
                $('#documento_id-error').remove();
            }
        });

       
    });
</script>
@endpush

