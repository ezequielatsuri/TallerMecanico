@extends('layouts.app')

@section('title','Crear Producto')

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
    <h1 class="mt-4 text-center">Crear Producto</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('productos.index')}}">Productos</a></li>
        <li class="breadcrumb-item active">Crear producto</li>
    </ol>

    <div class="card">
        <form action="{{ route('productos.store') }}" method="post" enctype="multipart/form-data" id="productoForm">
            @csrf
            <div class="card-body text-bg-light">

                <div class="row g-4">

                    <!----Codigo---->
                    <div class="col-md-6">
                        <label for="codigo" class="form-label"><strong>Código:</strong></label>
                        <input type="text" name="codigo" id="codigo" class="form-control" value="{{old('codigo')}}" oninput="validarCodigo(this)">
                        @error('codigo')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="codigoError" class="text-danger d-none">El código es requerido y debe ser único.</small>
                        <small id="codigoNumero" class="text-danger d-none">El código debe ser solamente de números.</small>
                    </div>

                    <!---Nombre---->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label"><strong>Nombre:</strong></label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}" oninput="validarNombre(this)">
                        @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="nombreError" class="text-danger d-none">El nombre es requerido y debe ser único.</small>
                        <small id="nombreNumError" class="text-danger d-none">El nombre no debe contener números.</small>
                    </div>

                    <!---Descripción---->
                    <div class="col-12">
                        <label for="descripcion" class="form-label"><strong>Descripción:</strong></label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control" oninput="validarDescripcion(this)">{{old('descripcion')}}</textarea>
                        @error('descripcion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small id="descripcionError" class="text-danger d-none">La descripción no debe exceder los 255 caracteres.</small>
                    </div>

                    <!---Imagen---->
                    <div class="col-md-6">
                        <label for="img_path" class="form-label"><strong>Imagen:</strong></label>
                        <input type="file" name="img_path" id="img_path" class="form-control" accept="image/*">
                        @error('img_path')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-- Botón para abrir el modal de creación de Marca -->
                    <div class="col-md-6">
                        <label for="marca_id" class="form-label"><strong>Marca:</strong></label>
                        <div class="input-group">
                            <select data-size="4" title="Seleccione una marca" data-live-search="true" name="marca_id" id="marca_id" class="form-control selectpicker show-tick">
                                @foreach ($marcas as $item)
                                <option value="{{$item->id}}" {{ old('marca_id') == $item->id ? 'selected' : '' }}>{{$item->nombre}}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalCrearMarca">+</button>
                        </div>
                        @error('marca_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-- Modal de creación de Marca -->
                    <div class="modal fade" id="modalCrearMarca" tabindex="-1" aria-labelledby="modalCrearMarcaLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="crearMarcaForm">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalCrearMarcaLabel">Crear Marca</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="nombreMarca" class="form-label">Nombre de la Marca</label>
                                            <input type="text" class="form-control" id="nombreMarca" name="nombre" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="descripcionMarca" class="form-label">Descripción</label>
                                            <textarea class="form-control" id="descripcionMarca" name="descripcion" rows="3" maxlength="255"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Marca</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('#crearMarcaForm').on('submit', function(e) {
                                e.preventDefault();

                                $.ajax({
                                    url: "{{ route('marcas.store') }}", // Ruta del controlador de marcas
                                    method: "POST",
                                    data: $(this).serialize(),
                                    success: function(response) {
                                        $('#modalCrearMarca').modal('hide');
                                        $('#marca_id').append(new Option(response.nombre, response.id));
                                        $('#marca_id').selectpicker('refresh'); // Actualizar el select
                                    },
                                    error: function(error) {
                                        console.log(error);
                                        alert('Error al crear la marca. Intenta nuevamente.');
                                    }
                                });
                            });
                        });
                    </script>

                    <!---Fabricante---->
                    <div class="col-md-6">
                        <label for="fabricante_id" class="form-label"><strong>Fabricante:</strong></label>
                        <div class="input-group">
                            <select data-size="4" title="Seleccione un fabricante" data-live-search="true" name="fabricante_id" id="fabricante_id" class="form-control selectpicker show-tick">
                                @foreach ($fabricantes as $item)
                                <option value="{{$item->id}}" {{ old('fabricante_id') == $item->id ? 'selected' : '' }}>{{$item->nombre}}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#crearFabricanteModal">+</button>
                        </div>>
                        @error('fabricante_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                    <!-- Modal para Crear Fabricante -->
                    <div class="modal fade" id="crearFabricanteModal" tabindex="-1" aria-labelledby="crearFabricanteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('fabricantes.store') }}" method="POST" id="crearFabricanteForm">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="crearFabricanteModalLabel">Crear Fabricante</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="nombreFabricante" class="form-label">Nombre</label>
                                            <input type="text" name="nombre" id="nombreFabricante" class="form-control" maxlength="60" required oninput="validarNombreFabricante(this)">
                                            <small id="nombreFabricanteError" class="text-danger d-none">El nombre solo debe contener letras y un máximo de 60 caracteres.</small>
                                        </div>
                                        <div class="mb-3">
                                            <label for="descripcionFabricante" class="form-label">Descripción</label>
                                            <textarea name="descripcion" id="descripcionFabricante" class="form-control" rows="3" maxlength="255"></textarea>
                                            <small id="descripcionFabricanteError" class="text-danger d-none">La descripción no debe exceder los 255 caracteres.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary" id="guardarFabricanteBtn" disabled>Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!---Categorías---->
                    <div class="col-md-6">
                        <label for="categorias" class="form-label"><strong>Categorías:</strong></label>
                        <div class="input-group">
                            <select data-size="4" title="Seleccione las categorías" data-live-search="true" name="categorias[]" id="categorias" class="form-control selectpicker show-tick" multiple>
                                @foreach ($categorias as $item)
                                <option value="{{$item->id}}" {{ (in_array($item->id , old('categorias',[]))) ? 'selected' : '' }}>{{$item->nombre}}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#crearCategoriaModal">+</button>
                        </div>
                        @error('categorias')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                </div>
            </div>
            <!-- Modal para Crear Categoría -->
            <div class="modal fade" id="crearCategoriaModal" tabindex="-1" aria-labelledby="crearCategoriaModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('categorias.store') }}" method="POST" id="crearCategoriaForm">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="crearCategoriaModalLabel">Crear Categoría</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nombreCategoria" class="form-label">Nombre</label>
                                    <input type="text" name="nombre" id="nombreCategoria" class="form-control" maxlength="30" required oninput="validarNombreCategoria(this)">
                                    <small id="nombreCategoriaError" class="text-danger d-none">El nombre solo debe contener letras y un máximo de 30 caracteres.</small>
                                </div>
                                <div class="mb-3">
                                    <label for="descripcionCategoria" class="form-label">Descripción</label>
                                    <textarea name="descripcion" id="descripcionCategoria" class="form-control" rows="3" maxlength="80"></textarea>
                                    <small id="descripcionCategoriaError" class="text-danger d-none">La descripción debe contener un máximo de 80 caracteres.</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary" id="guardarCategoriaBtn" disabled>Guardar</button>
                            </div>
                        </form>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js">
    function validarCodigo(input) {
        const errorMessage = document.getElementById('codigoError');
        const submitButton = document.getElementById('submitBtn');
        const errorNumero = document.getElementById('codigoNumero');


        const regex = /^[0-9]+$/;


        // El código debe ser único y no puede estar vacío
        if (input.value.trim() == '') {
            errorMessage.classList.remove('d-none'); // Ocultar el mensaje de error
            errorNumero.classList.add('d-none');
            submitButton.disabled = true; // Habilitar el botón de guardar
        } else if (!regex.test(input.value)) {
            errorMessage.classList.add('d-none');
            errorNumero.classList.remove('d-none');
            submitButton.disabled = true;
        } else {
            errorNumero.classList.add('d-none')
            errorMessage.classList.add('d-none'); // Mostrar el mensaje de error
            submitButton.disabled = false; // Deshabilitar el botón de guardar
        }
    }

    function validarNombre(input) {
        const errorMessage = document.getElementById('nombreError');
        const errorNumMessage = document.getElementById('nombreNumError');
        const submitButton = document.getElementById('submitBtn');

        // Expresión regular para permitir solo letras (incluyendo letras acentuadas)
        const regex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/;

        if (input.value.trim() === '') {
            errorMessage.classList.remove('d-none'); // Mostrar el mensaje de error
            errorNumMessage.classList.add('d-none'); // Ocultar mensaje de números
            submitButton.disabled = true; // Deshabilitar el botón de guardar
        } else if (!regex.test(input.value)) {
            errorNumMessage.classList.remove('d-none'); // Mostrar mensaje de números
            errorMessage.classList.add('d-none'); // Ocultar mensaje de error
            submitButton.disabled = true; // Deshabilitar el botón de guardar
        } else {
            errorMessage.classList.add('d-none'); // Ocultar el mensaje de error
            errorNumMessage.classList.add('d-none'); // Ocultar mensaje de números
            submitButton.disabled = false; // Habilitar el botón de guardar
        }
    }

    function validarDescripcion(textarea) {
        const errorMessage = document.getElementById('descripcionError');
        const submitButton = document.getElementById('submitBtn');

        if (textarea.value.length <= 255) {
            errorMessage.classList.add('d-none'); // Ocultar el mensaje de error
            submitButton.disabled = false; // Habilitar el botón de guardar
        } else {
            errorMessage.classList.remove('d-none'); // Mostrar el mensaje de error
            submitButton.disabled = true; // Deshabilitar el botón de guardar
        }
    }

    // Validación inicial al cargar la página
    window.onload = function() {
        validarCodigo(document.getElementById('codigo'));
        validarNombre(document.getElementById('nombre'));
        validarDescripcion(document.getElementById('descripcion'));
    }
</script>
@endpush
