<nav id="sidebar" aria-label="Main Navigation">
    <!-- Side Header -->
    <div class="content-header">
        <!-- Logo -->
        <a class="font-semibold text-dual" href="/">
            <span class="smini-visible">
                <i class="fa fa-circle-notch text-primary"></i>
            </span>
            <span class="smini-hide fs-5 tracking-wider">Maq<span class="fw-normal">App</span></span>
        </a>

        <!-- Extra -->
        <div class="d-flex align-items-center gap-1">
            <!-- Dark Mode -->
            <div class="dropdown">
                <button type="button" class="btn btn-sm btn-alt-secondary" id="sidebar-dark-mode-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-fw fa-moon" data-dark-mode-icon></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end smini-hide border-0" aria-labelledby="sidebar-dark-mode-dropdown">
                    <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-toggle="layout" data-action="dark_mode_off" data-dark-mode="off">
                        <i class="far fa-sun fa-fw opacity-50"></i>
                        <span class="fs-sm fw-medium">Light</span>
                    </button>
                    <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-toggle="layout" data-action="dark_mode_on" data-dark-mode="on">
                        <i class="far fa-moon fa-fw opacity-50"></i>
                        <span class="fs-sm fw-medium">Dark</span>
                    </button>
                    <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-toggle="layout" data-action="dark_mode_system" data-dark-mode="system">
                        <i class="fa fa-desktop fa-fw opacity-50"></i>
                        <span class="fs-sm fw-medium">System</span>
                    </button>
                </div>
            </div>

            <!-- Close Sidebar -->
            <a class="d-lg-none btn btn-sm btn-alt-secondary ms-1" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
                <i class="fa fa-fw fa-times"></i>
            </a>
        </div>
    </div>

    <!-- Sidebar Scrolling -->
    <div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side">
            <ul class="nav-main">
                {{-- <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->routeIs('dashboard') ? ' active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="nav-main-link-icon si si-speedometer"></i>
                        <span class="nav-main-link-name">Dashboard</span>
                    </a>
                </li> --}}

                <!-- Merchants Section -->
                <li class="nav-main-heading">{{__('crud.sidebar.customer_managemt')}}</li>
                <li class="nav-main-item{{ request()->is('clients/*') || request()->is('tenants/*') ? ' open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#">
                        <i class="nav-main-link-icon si si-users"></i>
                        <span class="nav-main-link-name">{{__('crud.sidebar.merchants')}}</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('clients/*') ? ' active' : '' }}" href="{{ route('merchants.clients.merchants.index') }}">
                                <span class="nav-main-link-name">{{__('crud.sidebar.clients')}}</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('tenants/*') ? ' active' : '' }}" href="{{ route('merchants.tenants.merchants.index') }}">
                                <span class="nav-main-link-name">{{__('crud.sidebar.tenants')}}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Catalog Section -->
                <li class="nav-main-heading">{{__('crud.sidebar.catalog')}}</li>
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('products*') ? ' active' : '' }}" href="{{ route('products.index') }}">
                        <i class="nav-main-link-icon si si-bag"></i>
                        <span class="nav-main-link-name">{{__('crud.sidebar.products')}}</span>
                    </a>
                </li>

                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('categories*') ? ' active' : '' }}" href="{{ route('categories.index') }}">
                        <i class="nav-main-link-icon si si-grid"></i>
                        <span class="nav-main-link-name">{{__('crud.sidebar.categories')}}</span>
                    </a>
                </li>

                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('services*') ? ' active' : '' }}" href="{{ route('services.index') }}">
                        <i class="nav-main-link-icon si si-wrench"></i>
                        <span class="nav-main-link-name">{{__('crud.sidebar.services')}}</span>
                    </a>
                </li>

                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('aircrafts*') ? ' active' : '' }}" href="{{ route('aircrafts.index') }}">
                        <i class="nav-main-link-icon si si-plane"></i>
                        <span class="nav-main-link-name">{{__('crud.sidebar.aircrafts')}}</span>
                    </a>
                </li>

                <!-- Users Section -->
                <li class="nav-main-heading">{{__('crud.sidebar.admin')}}</li>
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('users*') ? ' active' : '' }}" href="{{ route('users.index') }}">
                        <i class="nav-main-link-icon si si-user"></i>
                        <span class="nav-main-link-name">{{__('crud.sidebar.users')}}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>