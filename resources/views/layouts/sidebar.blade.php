<nav id="sidebar" aria-label="Main Navigation">
    <!-- Side Header -->
    <div class="content-header">
        <!-- Logo -->
        <a class="font-semibold text-dual" href="/">
            <span class="smini-visible">
                <i class="fa fa-circle-notch text-primary"></i>
            </span>
            <div>
                <span class="smini-hide w-100 d-block">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-100 h-auto" style="max-width: 200px;">
                </span>
            </div>

        </a>

        <!-- Extra -->
        <div class="d-flex align-items-center gap-1">
            <!-- Dark Mode -->
            <div class="dropdown">
                <button type="button" class="btn btn-sm btn-alt-secondary" id="sidebar-dark-mode-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-fw fa-moon" data-dark-mode-icon></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end smini-hide border-0"
                    aria-labelledby="sidebar-dark-mode-dropdown">
                    <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-toggle="layout"
                        data-action="dark_mode_off" data-dark-mode="off">
                        <i class="far fa-sun fa-fw opacity-50"></i>
                        <span class="fs-sm fw-medium">Light</span>
                    </button>
                    <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-toggle="layout"
                        data-action="dark_mode_on" data-dark-mode="on">
                        <i class="far fa-moon fa-fw opacity-50"></i>
                        <span class="fs-sm fw-medium">Dark</span>
                    </button>
                    <button type="button" class="dropdown-item d-flex align-items-center gap-2" data-toggle="layout"
                        data-action="dark_mode_system" data-dark-mode="system">
                        <i class="fa fa-desktop fa-fw opacity-50"></i>
                        <span class="fs-sm fw-medium">System</span>
                    </button>
                </div>
            </div>

            <!-- Close Sidebar -->
            <a class="d-lg-none btn btn-sm btn-alt-secondary ms-1" data-toggle="layout" data-action="sidebar_close"
                href="javascript:void(0)">
                <i class="fa fa-fw fa-times"></i>
            </a>
        </div>
    </div>

    <!-- Sidebar Scrolling -->
    <div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side">
            <ul class="nav-main">
                <!-- GESTIÓN -->
                <li class="nav-main-heading">GESTIÓN</li>
                @can('clients.merchants.index')
                <li class="nav-main-item{{ request()->is('clients/*') || request()->is('lots/*') ? ' open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu{{ request()->is('clients/*') || request()->is('lots/*') ? ' active' : '' }}"
                       data-toggle="submenu" aria-haspopup="true" aria-expanded="true"
                       href="#">
                        <i class="nav-main-link-icon fa fa-building-user"></i>
                        <span class="nav-main-link-name">{{ __('crud.sidebar.clients') }}</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('clients/*') ? ' active' : '' }}"
                               href="{{ route('clients.merchants.index') }}">
                               <i class="nav-main-link-icon fa fa-building-user"></i>
                                <span class="nav-main-link-name">{{ __('crud.sidebar.clients') }}</span>
                            </a>
                        </li>
                        @can('tenants.merchants.index')
                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('tenants/*') ? ' active' : '' }}"
                               href="{{ route('tenants.merchants.index') }}">
                               <i class="nav-main-link-icon fa fa-building-wheat"></i>
                                <span class="nav-main-link-name">{{ __('crud.sidebar.tenants') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('lots.index')
                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('lots*') ? ' active' : '' }}"
                               href="{{ route('lots.index') }}">
                               <i class="nav-main-link-icon fa fa-map-location-dot"></i>
                                <span class="nav-main-link-name">{{ __('crud.sidebar.lots') }}</span>
                            </a>
                        </li>
                        @endcan

                    </ul>
                </li>
                @endcan
                {{-- @can('lots.index')
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('lots*') ? ' active' : '' }}"
                        href="{{ route('lots.index') }}">
                        <i class="nav-main-link-icon fa fa-map-location-dot"></i>
                        <span class="nav-main-link-name">{{ __('crud.sidebar.lots') }}</span>
                    </a>
                </li>
                @endcan --}}
                @can('orders.index')
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('orders*') ? ' active' : '' }}"
                        href="{{ route('orders.index') }}">
                        <i class="nav-main-link-icon fa fa-clipboard"></i>
                        <span class="nav-main-link-name">{{ __('crud.sidebar.orders') }}</span>
                    </a>
                </li>
                @endcan
            
                <!-- ADMINISTRACIÓN -->
                <li class="nav-main-heading">ADMINISTRACIÓN</li>
                @can('users.index')
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('users*') ? ' active' : '' }}"
                        href="{{ route('users.index') }}">
                        <i class="nav-main-link-icon si si-user"></i>
                        <span class="nav-main-link-name">{{ __('crud.sidebar.users') }}</span>
                    </a>
                </li>
                @endcan
                @can('services.index')
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('services*') ? ' active' : '' }}"
                        href="{{ route('services.index') }}">
                        <i class="nav-main-link-icon si si-wrench"></i>
                        <span class="nav-main-link-name">{{ __('crud.sidebar.services') }}</span>
                    </a>
                </li>
                @endcan
                @can('products.index')
                <li class="nav-main-item{{ request()->is('products*') || request()->is('categories*') ? ' open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu{{ request()->is('products*') || request()->is('categories*') ? ' active' : '' }}"
                       data-toggle="submenu" aria-haspopup="true" aria-expanded="true"
                       href="#">
                        <i class="nav-main-link-icon fa fa-bottle-water"></i>
                        <span class="nav-main-link-name">{{ __('crud.sidebar.products') }}</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('products*') ? ' active' : '' }}"
                               href="{{ route('products.index') }}">
                                <i class="nav-main-link-icon fa fa-bottle-water"></i>
                                <span class="nav-main-link-name">{{ __('crud.sidebar.products') }}</span>
                            </a>
                        </li>
                        @can('categories.index')
                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('categories*') ? ' active' : '' }}"
                               href="{{ route('categories.index') }}">
                                <i class="nav-main-link-icon si si-grid"></i>
                                <span class="nav-main-link-name">{{ __('crud.sidebar.categories') }}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('aircrafts.index')
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('aircrafts*') ? ' active' : '' }}"
                        href="{{ route('aircrafts.index') }}">
                        <i class="nav-main-link-icon si si-plane"></i>
                        <span class="nav-main-link-name">{{ __('crud.sidebar.aircrafts') }}</span>
                    </a>
                </li>
                @endcan
            </ul>
        </div>
    </div>
</nav>
