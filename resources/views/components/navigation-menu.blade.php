<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu bg-dark">
            <div class="nav">

                <div class="sb-sidenav-menu-heading">Inicio</div>
                <a class="nav-link {{ request()->routeIs('panel') ? 'active' : '' }}" href="{{ route('panel') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Panel
                </a>

                <div class="sb-sidenav-menu-heading">Modulos</div>

                <!----Compras---->
                @can('ver-compra')
                <a class="nav-link collapsed {{ request()->is('compras*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCompras" aria-expanded="false" aria-controls="collapseCompras">
                 <!--Sb-nav-link-icon es para cambiar el color de los iconos--->                <div class="sb-nav-link-icon"><i class="fa-solid fa-store"></i></div>
                    Compras
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ request()->is('compras*') ? 'show' : '' }}" id="collapseCompras" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @can('ver-compra')
                        <a class="nav-link {{ request()->routeIs('compras.index') ? 'active' : '' }}" href="{{ route('compras.index') }}">Ver</a>
                        @endcan
                        @can('crear-compra')
                        <a class="nav-link {{ request()->routeIs('compras.create') ? 'active' : '' }}" href="{{ route('compras.create') }}">Crear</a>
                        @endcan
                    </nav>
                </div>
                @endcan

                <!----Ventas---->
                @can('ver-venta')
                <a class="nav-link collapsed {{ request()->is('ventas*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVentas" aria-expanded="false" aria-controls="collapseVentas">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                    Ventas
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ request()->is('ventas*') ? 'show' : '' }}" id="collapseVentas" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @can('ver-venta')
                        <a class="nav-link {{ request()->routeIs('ventas.index') ? 'active' : '' }}" href="{{ route('ventas.index') }}">Ver</a>
                        @endcan
                        @can('crear-venta')
                        <a class="nav-link {{ request()->routeIs('ventas.create') ? 'active' : '' }}" href="{{ route('ventas.create') }}">Crear</a>
                        @endcan
                    </nav>
                </div>
                @endcan

                @can('ver-categoria')
                <a class="nav-link {{ request()->is('categorias*') ? 'active' : '' }}" href="{{ route('categorias.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-tag"></i></div>
                    Categorías
                </a>
                @endcan

                @can('ver-fabricante')
                <a class="nav-link {{ request()->is('fabricantes*') ? 'active' : '' }}" href="{{ route('fabricantes.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-box-archive"></i></div>
                    Fabricantes
                </a>
                @endcan

                @can('ver-marca')
                <a class="nav-link {{ request()->is('marcas*') ? 'active' : '' }}" href="{{ route('marcas.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-bullhorn"></i></div>
                    Marcas
                </a>
                @endcan

                @can('ver-producto')
                <a class="nav-link {{ request()->is('productos*') ? 'active' : '' }}" href="{{ route('productos.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-brands fa-shopify"></i></div>
                    Productos
                </a>
                @endcan

                @can('ver-servicio')
                <a class="nav-link {{ request()->is('servicios*') ? 'active' : '' }}" href="{{ route('servicios.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-brands fa-shopify"></i></div>
                    Servicios
                </a>
                @endcan

                @can('ver-cliente')
                <a class="nav-link {{ request()->is('clientes*') ? 'active' : '' }}" href="{{ route('clientes.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    Clientes
                </a>
                @endcan

                @can('ver-proveedore')
                <a class="nav-link {{ request()->is('proveedores*') ? 'active' : '' }}" href="{{ route('proveedores.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-user-group"></i></div>
                    Proveedores
                </a>
                @endcan

                @hasrole('administrador')
                <div class="sb-sidenav-menu-heading">OTROS</div>
                @endhasrole

                @can('ver-user')
                <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-user"></i></div>
                    Usuarios
                </a>
                @endcan

                @can('ver-role')
                <a class="nav-link {{ request()->is('roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-person-circle-plus"></i></div>
                    Roles
                </a>
                @endcan

            </div>
        </div>
    </nav>
</div>

<style>
    /* <----- Cambios realizados aquí: Estilos para el hover redondeado y el estado activo */
    .nav-link {
        border-radius: 0.375rem; /* Bordes redondeados */
        transition: background-color 0.3s ease; /* Transición suave */
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1); /* Color de fondo en hover */
    }

    .nav-link.active {
        background-color: rgba(0, 123, 255, 0.3); /* Color de fondo para el módulo activo */
        color: #fff; /* Color del texto para el módulo activo */
    }

    .nav-link.collapsed:hover {
        background-color: rgba(255, 255, 255, 0.1); /* Color de fondo para enlaces colapsados */
    }
</style>
