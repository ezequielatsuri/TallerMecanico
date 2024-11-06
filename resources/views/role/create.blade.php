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
                    <label for="name" class="col-md-auto col-form-label"><strong>Nombre del rol:</strong></label>
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
                                <input type="checkbox" class="btn-check" id="ver-producto" name="permission[]" value="ver-producto">
                                <label class="btn btn-outline-primary" for="ver-producto">Ver</label>

                                <input type="checkbox" class="btn-check" id="crear-producto" name="permission[]" value="crear-producto">
                                <label class="btn btn-outline-success" for="crear-producto">Crear</label>

                                <input type="checkbox" class="btn-check" id="editar-producto" name="permission[]" value="editar-producto">
                                <label class="btn btn-outline-warning" for="editar-producto">Editar</label>

                                <input type="checkbox" class="btn-check" id="eliminar-producto" name="permission[]" value="eliminar-producto">
                                <label class="btn btn-outline-danger" for="eliminar-producto">Eliminar</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Permisos de Marcas:</label>
                            <div class="btn-group" role="group">
                                <input type="checkbox" class="btn-check" id="ver-marca" name="permission[]" value="ver-marca">
                                <label class="btn btn-outline-primary" for="ver-marca">Ver</label>

                                <input type="checkbox" class="btn-check" id="crear-marca" name="permission[]" value="crear-marca">
                                <label class="btn btn-outline-success" for="crear-marca">Crear</label>

                                <input type="checkbox" class="btn-check" id="editar-marca" name="permission[]" value="editar-marca">
                                <label class="btn btn-outline-warning" for="editar-marca">Editar</label>

                                <input type="checkbox" class="btn-check" id="eliminar-marca" name="permission[]" value="eliminar-marca">
                                <label class="btn btn-outline-danger" for="eliminar-marca">Eliminar</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Permisos de Proveedores:</label>
                            <div class="btn-group" role="group">
                                <input type="checkbox" class="btn-check" id="ver-proveedore" name="permission[]" value="ver-proveedore">
                                <label class="btn btn-outline-primary" for="ver-proveedore">Ver</label>

                                <input type="checkbox" class="btn-check" id="crear-proveedore" name="permission[]" value="crear-proveedore">
                                <label class="btn btn-outline-success" for="crear-proveedore">Crear</label>

                                <input type="checkbox" class="btn-check" id="editar-proveedore" name="permission[]" value="editar-proveedore">
                                <label class="btn btn-outline-warning" for="editar-proveedore">Editar</label>

                                <input type="checkbox" class="btn-check" id="eliminar-proveedore" name="permission[]" value="eliminar-proveedore">
                                <label class="btn btn-outline-danger" for="eliminar-proveedore">Eliminar</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Permisos de Servicios:</label>
                            <div class="btn-group" role="group">
                                <input type="checkbox" class="btn-check" id="ver-servicio" name="permission[]" value="ver-servicios">
                                <label class="btn btn-outline-primary" for="ver-servicio">Ver</label>

                                <input type="checkbox" class="btn-check" id="crear-servicio" name="permission[]" value="crear-servicios">
                                <label class="btn btn-outline-success" for="crear-servicio">Crear</label>

                                <input type="checkbox" class="btn-check" id="editar-servicio" name="permission[]" value="editar-servicios">
                                <label class="btn btn-outline-warning" for="editar-servicio">Editar</label>

                                <input type="checkbox" class="btn-check" id="eliminar-servicio" name="permission[]" value="eliminar-servicios">
                                <label class="btn btn-outline-danger" for="eliminar-servicio">Eliminar</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Permisos de Usuarios:</label>
                            <div class="btn-group" role="group">
                                <input type="checkbox" class="btn-check" id="ver-user" name="permission[]" value="ver-cliente">
                                <label class="btn btn-outline-primary" for="ver-user">Ver</label>

                                <input type="checkbox" class="btn-check" id="crear-user" name="permission[]" value="crear-user">
                                <label class="btn btn-outline-success" for="crear-user">Crear</label>

                                <input type="checkbox" class="btn-check" id="editar-user" name="permission[]" value="editar-user">
                                <label class="btn btn-outline-warning" for="editar-user">Editar</label>

                                <input type="checkbox" class="btn-check" id="eliminar-user" name="permission[]" value="eliminar-user">
                                <label class="btn btn-outline-danger" for="eliminar-user">Eliminar</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Permisos de Roles:</label>
                            <div class="btn-group" role="group">
                                <input type="checkbox" class="btn-check" id="ver-role" name="permission[]" value="ver-cliente">
                                <label class="btn btn-outline-primary" for="ver-role">Ver</label>

                                <input type="checkbox" class="btn-check" id="crear-role" name="permission[]" value="crear-role">
                                <label class="btn btn-outline-success" for="crear-role">Crear</label>

                                <input type="checkbox" class="btn-check" id="editar-role" name="permission[]" value="editar-role">
                                <label class="btn btn-outline-warning" for="editar-role">Editar</label>

                                <input type="checkbox" class="btn-check" id="eliminar-role" name="permission[]" value="eliminar-role">
                                <label class="btn btn-outline-danger" for="eliminar-role">Eliminar</label>
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
