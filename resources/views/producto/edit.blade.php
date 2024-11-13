@extends('layouts.app')

@section('title','Editar Producto')

@push('css')
<style>
    #descripcion {
        resize: none;
    }

    /* Estilos para validación de campos */
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
    <h1 class="mt-4 text-center">Editar Producto</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('productos.index')}}">Productos</a></li>
        <li class="breadcrumb-item active">Editar producto</li>
    </ol>

    <div class="card text-bg-light">
        <form action="{{route('productos.update',['producto'=>$producto])}}" method="post" enctype="multipart/form-data" id="productoForm">
            @method('PATCH')
            @csrf
            <div class="card-body">

                <div class="row g-4">
                    <!-- Código -->
                    <div class="col-md-6">
                        <label for="codigo" class="form-label">Código:</label>
                        <input type="text" name="codigo" id="codigo" class="form-control" maxlength="20" oninput="validarCampos()" value="{{old('codigo',$producto->codigo)}}">
                        @error('codigo')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="codigoErrorVacio" class="text-danger d-none">El código no puede estar vacío.</small>
                        <small id="codigoNumero" class="text-danger d-none">El código debe ser solamente de números y un máximo de 20 caracteres.</small>
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" maxlength="60" oninput="validarCampos()" value="{{old('nombre',$producto->nombre)}}">
                        @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="nombreErrorVacio" class="text-danger d-none">El nombre no puede estar vacío.</small>
                        <small id="nombreNumError" class="text-danger d-none">El nombre solo debe contener letras y un máximo de 60 caracteres.</small>
                    </div>

                    <!-- Descripción -->
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control" maxlength="200" oninput="validarDescripcion()">{{old('descripcion',$producto->descripcion)}}</textarea>
                        @error('descripcion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="descripcionError" class="text-danger d-none">La descripción no debe exceder los 200 caracteres.</small>
                    </div>

                    <!-- Fecha de vencimiento -->
                    <div class="col-md-6">
                        <label for="fecha_vencimiento" class="form-label">Fecha de vencimiento:</label>
                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control" value="{{old('fecha_vencimiento',$producto->fecha_vencimiento)}}">
                        @error('fecha_vencimiento')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-- Imagen -->
                    <div class="col-md-6">
                        <label for="img_path" class="form-label">Imagen:</label>
                        <input type="file" name="img_path" id="img_path" class="form-control" accept="image/*">
                        @error('img_path')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-- Marca -->
                    <div class="col-md-6">
                        <label for="marca_id" class="form-label">Marca:</label>
                        <select name="marca_id" id="marca_id" class="form-control selectpicker show-tick" data-live-search="true" onchange="validarCampos()">
                            <option value="">Seleccione una marca</option>
                            @foreach ($marcas as $item)
                                <option value="{{$item->id}}" {{ old('marca_id', $producto->marca_id) == $item->id ? 'selected' : '' }}>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        <small id="marcaError" class="text-danger d-none">Debe seleccionar una marca.</small>
                    </div>

                    <!-- Fabricante -->
                    <div class="col-md-6">
                        <label for="fabricante_id" class="form-label">Fabricante:</label>
                        <select name="fabricante_id" id="fabricante_id" class="form-control selectpicker show-tick" data-live-search="true" onchange="validarCampos()">
                            <option value="">Seleccione un fabricante</option>
                            @foreach ($fabricantes as $item)
                                <option value="{{$item->id}}" {{ old('fabricante_id', $producto->fabricante_id) == $item->id ? 'selected' : '' }}>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        <small id="fabricanteError" class="text-danger d-none">Debe seleccionar un fabricante.</small>
                    </div>

                    <!-- Categorías -->
                    <div class="col-md-6">
                        <label for="categorias" class="form-label">Categorías:</label>
                        <select name="categorias[]" id="categorias" class="form-control selectpicker show-tick" data-live-search="true" multiple onchange="validarCampos()">
                            @foreach ($categorias as $item)
                                <option value="{{$item->id}}" {{ in_array($item->id, old('categorias', $producto->categorias->pluck('id')->toArray())) ? 'selected' : '' }}>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        <small id="categoriasError" class="text-danger d-none">Debe seleccionar al menos una categoría.</small>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Guardar</button>
                <button type="reset" class="btn btn-secondary">Reiniciar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    function validarCampos() {
        const codigoInput = document.getElementById("codigo");
        const nombreInput = document.getElementById("nombre");
        const marcaInput = document.getElementById("marca_id");
        const fabricanteInput = document.getElementById("fabricante_id");
        const categoriasInput = document.getElementById("categorias");

        const codigoErrorVacio = document.getElementById("codigoErrorVacio");
        const codigoNumero = document.getElementById("codigoNumero");
        const nombreErrorVacio = document.getElementById("nombreErrorVacio");
        const nombreNumError = document.getElementById("nombreNumError");
        const marcaError = document.getElementById("marcaError");
        const fabricanteError = document.getElementById("fabricanteError");
        const categoriasError = document.getElementById("categoriasError");

        const submitButton = document.getElementById("submitBtn");

        const regexCodigo = /^[0-9]+$/;
        const regexNombre = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/;

        let valid = true;

        // Validación del campo "Código"
        if (codigoInput.value.trim() === '') {
            codigoErrorVacio.classList.remove("d-none");
            codigoNumero.classList.add("d-none");
            codigoInput.classList.add("is-invalid");
            codigoInput.classList.remove("is-valid");
            valid = false;
        } else if (!regexCodigo.test(codigoInput.value) || codigoInput.value.length > 20) {
            codigoErrorVacio.classList.add("d-none");
            codigoNumero.classList.remove("d-none");
            codigoInput.classList.add("is-invalid");
            codigoInput.classList.remove("is-valid");
            valid = false;
        } else {
            codigoErrorVacio.classList.add("d-none");
            codigoNumero.classList.add("d-none");
            codigoInput.classList.remove("is-invalid");
            codigoInput.classList.add("is-valid");
        }

        // Validación del campo "Nombre"
        if (nombreInput.value.trim() === '') {
            nombreErrorVacio.classList.remove("d-none");
            nombreNumError.classList.add("d-none");
            nombreInput.classList.add("is-invalid");
            nombreInput.classList.remove("is-valid");
            valid = false;
        } else if (!regexNombre.test(nombreInput.value) || nombreInput.value.length > 60) {
            nombreErrorVacio.classList.add("d-none");
            nombreNumError.classList.remove("d-none");
            nombreInput.classList.add("is-invalid");
            nombreInput.classList.remove("is-valid");
            valid = false;
        } else {
            nombreErrorVacio.classList.add("d-none");
            nombreNumError.classList.add("d-none");
            nombreInput.classList.remove("is-invalid");
            nombreInput.classList.add("is-valid");
        }

        // Validación de "Marca"
        if (marcaInput.value === '') {
            marcaError.classList.remove("d-none");
            marcaInput.classList.add("is-invalid");
            marcaInput.classList.remove("is-valid");
            valid = false;
        } else {
            marcaError.classList.add("d-none");
            marcaInput.classList.remove("is-invalid");
            marcaInput.classList.add("is-valid");
        }

        // Validación de "Fabricante"
        if (fabricanteInput.value === '') {
            fabricanteError.classList.remove("d-none");
            fabricanteInput.classList.add("is-invalid");
            fabricanteInput.classList.remove("is-valid");
            valid = false;
        } else {
            fabricanteError.classList.add("d-none");
            fabricanteInput.classList.remove("is-invalid");
            fabricanteInput.classList.add("is-valid");
        }

        // Validación de "Categorías"
        if (categoriasInput.selectedOptions.length === 0) {
            categoriasError.classList.remove("d-none");
            categoriasInput.classList.add("is-invalid");
            categoriasInput.classList.remove("is-valid");
            valid = false;
        } else {
            categoriasError.classList.add("d-none");
            categoriasInput.classList.remove("is-invalid");
            categoriasInput.classList.add("is-valid");
        }

        submitButton.disabled = !valid;
    }

    function validarDescripcion() {
        const descripcionInput = document.getElementById('descripcion');
        const descripcionError = document.getElementById('descripcionError');
        const submitButton = document.getElementById('submitBtn');

        if (descripcionInput.value.length <= 200) {
            descripcionError.classList.add('d-none');

        } else {
            descripcionError.classList.remove('d-none');

            submitButton.disabled = true;
        }
    }

    // Validación inicial al cargar la página
    window.onload = function() {
        validarCampos();
        validarDescripcion();
    }
</script>
@endpush
