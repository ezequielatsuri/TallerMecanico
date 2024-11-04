@extends('layouts.app')

@section('title','Crear rol')

@push('css')
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Rol</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('roles.index')}}">Roles</a></li>
        <li class="breadcrumb-item active">Crear rol</li>
    </ol>

    <div class="card">
        <div class="card-header">
            <p>Nota: Los roles son un conjunto de permisos</p>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="post">
                @csrf
                <!-- Nombre del rol -->
                <div class="row mb-4">
                    <label for="name" class="col-md-auto col-form-label">Nombre del rol:</label>
                    <div class="col-md-4">
                        <input autocomplete="off" type="text" name="name" id="name" class="form-control" value="{{old('name')}}" required>
                    </div>
                    <div class="col-md-4">
                        @error('name')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>

                <!-- Permisos para el rol -->
                <div class="col-12">
                    <p class="text-muted">Permisos para el rol:</p>
                    <div class="row">
                        <!-- Aquí separamos los permisos por secciones para agruparlos -->
                        <div class="col-md-6 mb-3">
                            <label>Permisos de categoría:</label>
                            <div class="btn-group" role="group">
                                <input type="checkbox" class="btn-check" id="ver-categoria" name="permission[]" value="ver-categoria">
                                <label class="btn btn-outline-primary" for="ver-categoria">Ver</label>

                                <input type="checkbox" class="btn-check" id="crear-categoria" name="permission[]" value="crear-categoria">
                                <label class="btn btn-outline-success" for="crear-categoria">Crear</label>

                                <input type="checkbox" class="btn-check" id="editar-categoria" name="permission[]" value="editar-categoria">
                                <label class="btn btn-outline-warning" for="editar-categoria">Editar</label>

                                <input type="checkbox" class="btn-check" id="eliminar-categoria" name="permission[]" value="eliminar-categoria">
                                <label class="btn btn-outline-danger" for="eliminar-categoria">Eliminar</label>
                            </div>
                        </div>

                        <!-- Otro grupo de permisos, ejemplo para Cliente -->
                        <div class="col-md-6 mb-3">
                            <label>Permisos de cliente:</label>
                            <div class="btn-group" role="group">
                                <input type="checkbox" class="btn-check" id="ver-cliente" name="permission[]" value="ver-cliente">
                                <label class="btn btn-outline-primary" for="ver-cliente">Ver</label>

                                <input type="checkbox" class="btn-check" id="crear-cliente" name="permission[]" value="crear-cliente">
                                <label class="btn btn-outline-success" for="crear-cliente">Crear</label>

                                <input type="checkbox" class="btn-check" id="editar-cliente" name="permission[]" value="editar-cliente">
                                <label class="btn btn-outline-warning" for="editar-cliente">Editar</label>

                                <input type="checkbox" class="btn-check" id="eliminar-cliente" name="permission[]" value="eliminar-cliente">
                                <label class="btn btn-outline-danger" for="eliminar-cliente">Eliminar</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Permisos de Fabricantes:</label>
                            <div class="btn-group" role="group">
                                <input type="checkbox" class="btn-check" id="ver-fabricante" name="permission[]" value="ver-fabricante">
                                <label class="btn btn-outline-primary" for="ver-fabricante">Ver</label>

                                <input type="checkbox" class="btn-check" id="crear-fabricante" name="permission[]" value="crear-fabricante">
                                <label class="btn btn-outline-success" for="crear-fabricante">Crear</label>

                                <input type="checkbox" class="btn-check" id="editar-fabricante" name="permission[]" value="editar-fabricante">
                                <label class="btn btn-outline-warning" for="editar-fabricante">Editar</label>

                                <input type="checkbox" class="btn-check" id="eliminar-fabricante" name="permission[]" value="eliminar-fabricante">
                                <label class="btn btn-outline-danger" for="eliminar-fabricante">Eliminar</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Permisos de Productos:</label>
                            <div class="btn-group" role="group">
                                <input type="checkbox" class="btn-check" id="ver-productos" name="permission[]" value="ver-productos">
                                <label class="btn btn-outline-primary" for="ver-productos">Ver</label>

                                <input type="checkbox" class="btn-check" id="crear-productos" name="permission[]" value="crear-productos">
                                <label class="btn btn-outline-success" for="crear-productos">Crear</label>

                                <input type="checkbox" class="btn-check" id="editar-productos" name="permission[]" value="editar-productos">
                                <label class="btn btn-outline-warning" for="editar-productos">Editar</label>

                                <input type="checkbox" class="btn-check" id="eliminar-productos" name="permission[]" value="eliminar-productos">
                                <label class="btn btn-outline-danger" for="eliminar-productos">Eliminar</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Permisos de Marcas:</label>
                            <div class="btn-group" role="group">
                                <input type="checkbox" class="btn-check" id="ver-marcas" name="permission[]" value="ver-marcas">
                                <label class="btn btn-outline-primary" for="ver-marcas">Ver</label>

                                <input type="checkbox" class="btn-check" id="crear-marcas" name="permission[]" value="crear-marcas">
                                <label class="btn btn-outline-success" for="crear-marcas">Crear</label>

                                <input type="checkbox" class="btn-check" id="editar-marcas" name="permission[]" value="editar-marcas">
                                <label class="btn btn-outline-warning" for="editar-marcas">Editar</label>

                                <input type="checkbox" class="btn-check" id="eliminar-marcas" name="permission[]" value="eliminar-marcas">
                                <label class="btn btn-outline-danger" for="eliminar-marcas">Eliminar</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Permisos de Proveedores:</label>
                            <div class="btn-group" role="group">
                                <input type="checkbox" class="btn-check" id="ver-proveedores" name="permission[]" value="ver-proveedores">
                                <label class="btn btn-outline-primary" for="ver-proveedores">Ver</label>

                                <input type="checkbox" class="btn-check" id="crear-proveedores" name="permission[]" value="crear-proveedores">
                                <label class="btn btn-outline-success" for="crear-proveedores">Crear</label>

                                <input type="checkbox" class="btn-check" id="editar-proveedores" name="permission[]" value="editar-proveedores">
                                <label class="btn btn-outline-warning" for="editar-proveedores">Editar</label>

                                <input type="checkbox" class="btn-check" id="eliminar-proveedores" name="permission[]" value="eliminar-proveedores">
                                <label class="btn btn-outline-danger" for="eliminar-proveedores">Eliminar</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Permisos de Servicios:</label>
                            <div class="btn-group" role="group">
                                <input type="checkbox" class="btn-check" id="ver-servicios" name="permission[]" value="ver-servicios">
                                <label class="btn btn-outline-primary" for="ver-servicios">Ver</label>

                                <input type="checkbox" class="btn-check" id="crear-servicios" name="permission[]" value="crear-servicios">
                                <label class="btn btn-outline-success" for="crear-servicios">Crear</label>

                                <input type="checkbox" class="btn-check" id="editar-servicios" name="permission[]" value="editar-servicios">
                                <label class="btn btn-outline-warning" for="editar-servicios">Editar</label>

                                <input type="checkbox" class="btn-check" id="eliminar-servicios" name="permission[]" value="eliminar-servicios">
                                <label class="btn btn-outline-danger" for="eliminar-servicios">Eliminar</label>
                            </div>
                        </div>
                        <!-- Puedes agregar más grupos de permisos de manera similar -->
                    </div>
                </div>
                @error('permission')
                <small class="text-danger">{{'*'.$message}}</small>
                @enderror

                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
@endpush
