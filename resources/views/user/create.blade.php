@extends('layouts.app')

@section('title','Crear usuario')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<style>
    .is-invalid {
        border: 2px solid red;
    }
    .is-valid {
        border: 2px solid green;
    }
    .error-message {
        color: red;
        font-size: 0.875rem;
        display: none;
    }
</style>
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
                <p>Nota: Los usuarios son los que pueden ingresar al sistema</p>
            </div>
            <div class="card-body">

                <!---Nombre---->
                <div class="row mb-4">
                    <label for="name" class="col-lg-2 col-form-label"><strong>Nombres:</strong></label>
                    <div class="col-lg-4">
                        <input autocomplete="off" type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios.">
                        <small id="nameError" class="error-message">El nombre solo debe contener letras y espacios.</small>
                    </div>
                </div>

                <!---Email---->
                <div class="row mb-4">
                    <label for="email" class="col-lg-2 col-form-label"><strong>Email:</strong></label>
                    <div class="col-lg-4">
                        <input autocomplete="off" type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                        <small id="emailError" class="error-message">Ingrese un correo electrónico válido.</small>
                    </div>
                </div>

                <!---Password---->
                <div class="row mb-4">
                    <label for="password" class="col-lg-2 col-form-label"><strong>Contraseña:</strong></label>
                    <div class="col-lg-4">
                        <input type="password" name="password" id="password" class="form-control" required minlength="8" pattern="(?=.*[0-9])(?=.*[A-Za-z]).{8,}" title="La contraseña debe tener al menos 8 caracteres, incluyendo letras y números.">
                        <small id="passwordError" class="error-message">La contraseña debe tener al menos 8 caracteres e incluir letras y números.</small>
                    </div>
                </div>

                <!---Confirm_Password---->
                <div class="row mb-4">
                    <label for="password_confirm" class="col-lg-2 col-form-label"><strong>Confirmar:</strong></label>
                    <div class="col-lg-4">
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control" required minlength="8">
                        <small id="passwordConfirmError" class="error-message">Las contraseñas no coinciden.</small>
                    </div>
                </div>

                <!---Roles---->
                <div class="row mb-4">
                    <label for="role" class="col-lg-2 col-form-label"><strong>Rol:</strong></label>
                    <div class="col-lg-4">
                        <select name="role" id="role" class="form-select" required>
                            <option value="" selected disabled>Seleccione:</option>
                            @foreach ($roles as $item)
                            <option value="{{ $item->name }}" @selected(old('role') == $item->name)>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <small id="roleError" class="error-message">Seleccione un rol.</small>
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
        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');
        const role = document.getElementById('role');

        // Validación en tiempo real para nombre
        name.addEventListener('input', function() {
            const namePattern = /^[A-Za-z\s]+$/;
            if (namePattern.test(name.value)) {
                name.classList.add('is-valid');
                name.classList.remove('is-invalid');
                document.getElementById('nameError').style.display = 'none';
            } else {
                name.classList.add('is-invalid');
                name.classList.remove('is-valid');
                document.getElementById('nameError').style.display = 'block';
            }
        });

        // Validación en tiempo real para email
        email.addEventListener('input', function() {
            if (email.checkValidity()) {
                email.classList.add('is-valid');
                email.classList.remove('is-invalid');
                document.getElementById('emailError').style.display = 'none';
            } else {
                email.classList.add('is-invalid');
                email.classList.remove('is-valid');
                document.getElementById('emailError').style.display = 'block';
            }
        });

        // Validación en tiempo real para contraseña
        password.addEventListener('input', function() {
            const passwordPattern = /^(?=.*[0-9])(?=.*[A-Za-z]).{8,}$/;
            if (passwordPattern.test(password.value)) {
                password.classList.add('is-valid');
                password.classList.remove('is-invalid');
                document.getElementById('passwordError').style.display = 'none';
            } else {
                password.classList.add('is-invalid');
                password.classList.remove('is-valid');
                document.getElementById('passwordError').style.display = 'block';
            }
            validatePasswordMatch();
        });

        // Validación en tiempo real para confirmar contraseña
        passwordConfirm.addEventListener('input', validatePasswordMatch);

        function validatePasswordMatch() {
            if (password.value === passwordConfirm.value && passwordConfirm.value !== "") {
                passwordConfirm.classList.add('is-valid');
                passwordConfirm.classList.remove('is-invalid');
                document.getElementById('passwordConfirmError').style.display = 'none';
            } else {
                passwordConfirm.classList.add('is-invalid');
                passwordConfirm.classList.remove('is-valid');
                document.getElementById('passwordConfirmError').style.display = 'block';
            }
        }

        // Validación en tiempo real para el rol
        role.addEventListener('change', function() {
            if (role.value !== "") {
                role.classList.add('is-valid');
                role.classList.remove('is-invalid');
                document.getElementById('roleError').style.display = 'none';
            } else {
                role.classList.add('is-invalid');
                role.classList.remove('is-valid');
                document.getElementById('roleError').style.display = 'block';
            }
        });
    });
</script>
@endpush
