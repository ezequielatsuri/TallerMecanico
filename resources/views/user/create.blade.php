@extends('layouts.app')

@section('title','Crear usuario')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Usuario</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index')}}">Usuarios</a></li>
        <li class="breadcrumb-item active">Crear Usuario</li>
    </ol>

    <div class="card text-bg-light">
        <form action="{{ route('users.store') }}" method="post" id="createUserForm">
            @csrf
            <div class="card-header">
                <p class="">Nota: Los usuarios son los que pueden ingresar al sistema</p>
            </div>
            <div class="card-body">

                <!---Nombre---->
                <div class="row mb-4">
                    <label for="name" class="col-lg-2 col-form-label" oninput="this.value = this.value.toUpperCase();" pattern="[A-Za-z\s]+" required><strong>Nombres:</strong></label>
                    <div class="col-lg-4">
                        <input autocomplete="off" type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios.">
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="nameHelpBlock">
                            Escriba un solo nombre
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('name')
                        <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                </div>

                <!---Email---->
                <div class="row mb-4">
                    <label for="email" class="col-lg-2 col-form-label"><strong>Email:</strong></label>
                    <div class="col-lg-4">
                        <input autocomplete="off" type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="emailHelpBlock">
                            Dirección de correo electrónico
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('email')
                        <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                </div>

                <!---Password---->
                <div class="row mb-4">
                    <label for="password" class="col-lg-2 col-form-label"><strong>Contraseña:</strong></label>
                    <div class="col-lg-4">
                        <input type="password" name="password" id="password" class="form-control" required minlength="8" pattern="(?=.*[0-9])(?=.*[A-Za-z]).{8,}" title="La contraseña debe tener al menos 8 caracteres, incluyendo letras y números.">
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="passwordHelpBlock">
                            Escriba una contraseña segura. Debe incluir números y tener al menos 8 caracteres.
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('password')
                        <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                </div>

                <!---Confirm_Password---->
                <div class="row mb-4">
                    <label for="password_confirm" class="col-lg-2 col-form-label"><strong>Confirmar:</strong></label>
                    <div class="col-lg-4">
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control" required minlength="8">
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="passwordConfirmHelpBlock">
                            Vuelva a escribir su contraseña.
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('password_confirm')
                        <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                </div>

                <!---Roles---->
                <div class="row mb-4">
                    <label for="role" class="col-lg-2 col-form-label"><strong>Rol:</strong></label>
                    <div class="col-lg-4">
                        <select name="role" id="role" class="form-select" required>
                            <option value="" selected disabled><strong>Seleccione:</strong></option>
                            @foreach ($roles as $item)
                            <option value="{{ $item->name }}" @selected(old('role') == $item->name)>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="rolHelpBlock">
                            Escoja un rol para el usuario.
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('role')
                        <small class="text-danger">{{ '*' . $message }}</small>
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
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('createUserForm');

        form.addEventListener('submit', function (event) {
            let valid = true;
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;

            // Validación de coincidencia de contraseñas
            if (password !== passwordConfirm) {
                valid = false;
                alert('Las contraseñas no coinciden.');
            }

            // Validación de nombre (solo letras y espacios)
            const namePattern = /^[A-Za-z\s]+$/;
            if (!namePattern.test(name)) {
                valid = false;
                alert('El nombre solo debe contener letras y espacios.');
            }

            // Validación de email (ya incluido por el atributo type="email")

            // Si no es válido, prevenir el envío del formulario
            if (!valid) {
                event.preventDefault();
            }
        });
    });
</script>
@endpush
