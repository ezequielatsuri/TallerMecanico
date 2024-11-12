@extends('layouts.app')

@section('title','Editar Producto')

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
                    <!----Codigo---->
                    <div class="col-md-6">
                        <label for="codigo" class="form-label">Código:</label>
                        <input type="text" name="codigo" id="codigo" class="form-control" oninput="validarCampos()" required value="{{old('codigo',$producto->codigo)}}">
                        @error('codigo')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="codigoErrorVacio" class="text-danger d-none">El código no puede estar vacío.</small>
                        <small id="codigoNumero" class="text-danger d-none">El código debe ser solamente de números.</small>
                    </div>

                    <!---Nombre---->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" oninput="validarCampos()" required value="{{old('nombre',$producto->nombre)}}">
                        @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="nombreErrorVacio" class="text-danger d-none">El nombre no puede estar vacío.</small>
                        <small id="nombreNumError" class="text-danger d-none">El nombre no debe contener números.</small>
                    </div>

                    <!---Descripción---->
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control" oninput="validarDescripcion()">{{old('descripcion',$producto->descripcion)}}</textarea>
                        @error('descripcion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="descripcionError" class="text-danger d-none">La descripción no debe exceder los 255 caracteres.</small>
                    </div>

                    <!---Fecha de vencimiento---->
                    <div class="col-md-6">
                        <label for="fecha_vencimiento" class="form-label">Fecha de vencimiento:</label>
                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control" value="{{old('fecha_vencimiento',$producto->fecha_vencimiento)}}">
                        @error('fecha_vencimiento')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!---Imagen---->
                    <div class="col-md-6">
                        <label for="img_path" class="form-label">Imagen:</label>
                        <input type="file" name="img_path" id="img_path" class="form-control" accept="image/*">
                        @error('img_path')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!---Marca---->
                    <div class="col-md-6">
                        <label for="marca_id" class="form-label">Marca:</label>
                        <select data-size="4" title="Seleccione una marca" data-live-search="true" name="marca_id" id="marca_id" class="form-control selectpicker show-tick">
                            @foreach ($marcas as $item)
                            <option value="{{$item->id}}" {{ old('marca_id', $producto->marca_id) == $item->id ? 'selected' : '' }}>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        @error('marca_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!---Fabricante---->
                    <div class="col-md-6">
                        <label for="fabricante_id" class="form-label">Fabricante:</label>
                        <select data-size="4" title="Seleccione un fabricante" data-live-search="true" name="fabricante_id" id="fabricante_id" class="form-control selectpicker show-tick">
                            @foreach ($fabricantes as $item)
                            <option value="{{$item->id}}" {{ old('fabricante_id', $producto->fabricante_id) == $item->id ? 'selected' : '' }}>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        @error('fabricante_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!---Categorías---->
                    <div class="col-md-6">
                        <label for="categorias" class="form-label">Categorías:</label>
                        <select data-size="4" title="Seleccione las categorías" data-live-search="true" name="categorias[]" id="categorias" class="form-control selectpicker show-tick" multiple>
                            @foreach ($categorias as $item)
                            <option value="{{$item->id}}" {{ in_array($item->id, old('categorias', $producto->categorias->pluck('id')->toArray())) ? 'selected' : '' }}>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        @error('categorias')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
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
        const codigoErrorVacio = document.getElementById("codigoErrorVacio");
        const codigoNumero = document.getElementById("codigoNumero");
        const nombreErrorVacio = document.getElementById("nombreErrorVacio");
        const nombreNumError = document.getElementById("nombreNumError");
        const submitButton = document.getElementById("submitBtn");

        // Expresiones regulares para validaciones
        const regexCodigo = /^[0-9]+$/;
        const regexNombre = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/;

        let valid = true;

        // Validación del campo "Código"
        if (codigoInput.value.trim() === '') {
            codigoErrorVacio.classList.remove("d-none"); // Mostrar mensaje de campo vacío
            codigoNumero.classList.add("d-none");   // Ocultar mensaje de solo números
            valid = false;
        } else if (!regexCodigo.test(codigoInput.value)) {
            codigoErrorVacio.classList.add("d-none");     // Ocultar mensaje de campo vacío
            codigoNumero.classList.remove("d-none"); // Mostrar mensaje de solo números
            valid = false;
        } else {
            codigoErrorVacio.classList.add("d-none");
            codigoNumero.classList.add("d-none");
        }

        // Validación del campo "Nombre"
        if (nombreInput.value.trim() === '') {
            nombreErrorVacio.classList.remove("d-none");   // Mostrar mensaje de campo vacío
            nombreNumError.classList.add("d-none");   // Ocultar mensaje de no contener números
            valid = false;
        } else if (!regexNombre.test(nombreInput.value)) {
            nombreErrorVacio.classList.add("d-none");      // Ocultar mensaje de campo vacío
            nombreNumError.classList.remove("d-none"); // Mostrar mensaje de no contener números
            valid = false;
        } else {
            nombreErrorVacio.classList.add("d-none");
            nombreNumError.classList.add("d-none");
        }

        // Habilitar o deshabilitar el botón de envío
        submitButton.disabled = !valid;
    }

    function validarDescripcion() {
        const descripcionInput = document.getElementById('descripcion');
        const descripcionError = document.getElementById('descripcionError');
        const submitButton = document.getElementById('submitBtn');

        if (descripcionInput.value.length <= 255) {
            descripcionError.classList.add('d-none'); // Ocultar el mensaje de error
        } else {
            descripcionError.classList.remove('d-none'); // Mostrar el mensaje de error
            submitButton.disabled = true; // Deshabilitar el botón de guardar si la descripción es demasiado larga
        }
    }

    // Validación inicial al cargar la página
    window.onload = function() {
        validarCampos();
        validarDescripcion();
    }
</script>
@endpush
