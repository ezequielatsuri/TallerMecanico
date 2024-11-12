<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Inicio de sesión del sistema" />
    <meta name="author" content="SakCode" />
    <title>Sistema de ventas - Login</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e6fff5;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-card {
            display: flex;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            padding: 20px;
            max-width: 800px;
            width: 100%;
        }

        .login-left {
            width: 45%;
            background-color: #e6fff5;
            text-align: center;
            border-radius: 20px 0 0 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .login-left img.logo {
            max-width: 80%;
        }

        .login-left img.car {
            max-width: 100%;
            bottom: 500px;
            padding-right: 70px;
        }

        .login-right {
            width: 55%;
            padding: 50px;
            border-radius: 0 20px 20px 0;
        }

        .login-right h3 {
            color: #1d3557;
            font-size: 30px;
            font-weight: bold;
            text-align: center;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #1d3557;
            border: none;
            width: 100%;
            padding: 10px;
            font-size: 18px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #457b9d;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Sección izquierda con el logo y coche -->
            <div class="login-left">
                <img src="Recursos/Logo.png" alt="Logo Taller Los Leones" class="logo">
                <img src="Recursos/Coche.png" alt="Carro" class="car">
            </div>
            <!-- Sección derecha con el formulario -->
            <div class="login-right">
                <h3>¡Bienvenido!</h3>
                @if ($errors->any())
                @foreach ($errors->all() as $item)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{$item}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endforeach
                @endif
                <form action="/login" method="post">
                    @csrf
                    <div class="form-floating mb-3">
                        <input autofocus autocomplete="off" value="invitado@gmail.com" class="form-control" name="email" id="inputEmail" type="email" placeholder="name@example.com" />
                        <label for="inputEmail">Correo electrónico</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="password" value="12345678" id="inputPassword" type="password" placeholder="Password" />
                        <label for="inputPassword">Contraseña</label>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button class="btn btn-primary" type="submit">Iniciar sesión</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script>

document.getElementById("inputEmail").addEventListener("input", function () {
    const emailInput = this;
    const emailPattern = /^[a-zA-Z0-9._%+-]+@(gmail|hotmail|outlook|yahoo|icloud)\.com$/;

    if (emailPattern.test(emailInput.value)) {
        emailInput.setCustomValidity(""); // Formato válido
        emailInput.style.borderColor = "green";
    } else {
        emailInput.setCustomValidity("Por favor, introduce un correo electrónico válido de los dominios permitidos.");
        emailInput.style.borderColor = "red";
    }
});
        // Validación para la contraseña
document.getElementById("inputPassword").addEventListener("input", function () {
    const passwordInput = this;

    if (passwordInput.value.trim() === "") {
        passwordInput.setCustomValidity("La contraseña no puede estar vacía."); // Mensaje personalizado
        passwordInput.style.borderColor = "red"; // Cambiar borde a rojo
    } else {
        passwordInput.setCustomValidity(""); // Formato válido
        passwordInput.style.borderColor = ""; // Reiniciar el borde
    }
});


</script>
</body>

</html>
