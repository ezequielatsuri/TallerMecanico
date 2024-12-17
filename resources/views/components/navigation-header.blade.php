<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <img src="{{ asset('Recursos/Logo.png') }}" alt="Logo" width="50px">
    
    <a class="navbar-brand ps-3" href="{{ route('panel') }}">Taller Los Leones</a>

    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

    <!-- Menú de Bienvenida centrado -->
    <div class="mx-auto text-center">
        <div class="small" style="font-size: 1.5rem;">Bienvenido {{ auth()->user()->name }}</div>
    </div>

    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <!-- Notificaciones -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="notificationsDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                @forelse(auth()->user()->unreadNotifications as $notification)
                    <li>
                        <a class="dropdown-item" href="#">
                            <strong>Producto:</strong> {{ $notification->data['producto'] }} <br>
                            <strong>Stock actual:</strong> {{ $notification->data['stock_actual'] }}
                        </a>
                    </li>
                @empty
                    <li><a class="dropdown-item text-muted" href="#">No hay notificaciones</a></li>
                @endforelse
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-center" href="{{ route('notifications.read') }}">Marcar todas como leídas</a></li>
            </ul>
        </li>

        <!-- Usuario -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{ route('profile.index') }}">Configuraciones</a></li>
                <li><a class="dropdown-item" href="#!">Registro de actividad</a></li>
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" href="{{ route('logout') }}">Cerrar sesión</a></li>
            </ul>
        </li>
    </ul>
</nav>
