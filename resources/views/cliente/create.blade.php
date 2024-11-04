@extends('layouts.app')

@section('title','Crear cliente')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<style>
    #box-razon-social {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Cliente</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index')}}">Clientes</a></li>
        <li class="breadcrumb-item active">Crear cliente</li>
    </ol>

    <div class="card">
        <form action="{{ route('clientes.store') }}" method="post">
            @csrf
            <div class="card-body text-bg-light">

                <div class="row g-3">


                    <!----Tipo de persona----->
                    <div class="col-md-6">
                        <label for="tipo_persona" class="form-label">Tipo de cliente:</label>
                        <select class="form-select" name="tipo_persona" id="tipo_persona">
                            <option value="" selected disabled>Seleccione una opción</option>
                            <option value="natural" {{ old('tipo_persona') == 'natural' ? 'selected' : '' }}>Persona natural</option>
                            <option value="juridica" {{ old('tipo_persona') == 'juridica' ? 'selected' : '' }}>Persona jurídica</option>
                        </select>
                        @error('tipo_persona')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-------Razón social------->
                    <!-------Persona Natural------->
                    <div class="col-12" id="natural" style="display: none;">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input required type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}">
                        <label for="apellidoP" class="form-label">Apellido Materno:</label>
                        <input required type="text" name="apellidoM" id="apellidoM" class="form-control" value="{{old('nombre')}}">
                        <label for="apellidoM" class="form-label">Apellido Paterno:</label>
                        <input required type="text" name="apellidoP" id="apellidoP" class="form-control" value="{{old('nombre')}}">
                        </div>
                    <div class="col-12" id="juridica" style="display: none0;">
                        <label for="razon_social" class="form-label">Nombre de la empresa:</label>
                        <input required type="text" name="razon_social" id="razon_social" class="form-control" value="{{old('razon_social')}}">
                        @error('razon_social')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        </div>
                    <script>
                            document.getElementById("tipo_persona").addEventListener("change", function() {
                                var elemento = this.value;

                                document.getElementById("natural").style.display = "none";
                                document.getElementById("juridica").style.display = "none";
                                if (elemento == "natural") {
                                    document.getElementById("natural").style.display = "block";
                                } else if (elemento == "juridica"){
                                    document.getElementById("juridica").style.display = "block";
                                }
                            });
                        </script>
                    <!------Dirección---->
                    <div class="col-12">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input required type="text" name="direccion" id="direccion" class="form-control" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '');" required value="{{old('direccion')}}">
                        @error('direccion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!--------------Documento------->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label">Tipo de documento:</label>
                        <select class="form-select" name="documento_id" id="documento_id">
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
                        <label for="numero_documento" class="form-label">Numero de documento:</label>
                        <input required type="text" name="numero_documento" id="numero_documento" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required value="{{old('numero_documento')}}">
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
        $('#tipo_persona').on('change', function() {
            let selectValue = $(this).val();
            //natural //juridica
            if (selectValue == 'natural') {
                $('#label-juridica').hide();
                $('#label-natural').show();
            } else {
                $('#label-natural').hide();
                $('#label-juridica').show();
            }

            $('#box-razon-social').show();
        });
    });
</script>
@endpush
