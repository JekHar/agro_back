<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>MaqApp</title>

    <meta name="description" content="Sistema de gestion para ventas mayoristas">
    <meta name="author" content="AFANTEC">
    <meta name="robots" content="index, follow">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">


    <!-- Modules -->
    @vite(['resources/sass/main.scss'])
    <link rel="stylesheet" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}">
    @livewireStyles
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}">
</head>

<body>
    <!-- Page Container -->
    <!--
  Available classes for #page-container:

  SIDEBAR and SIDE OVERLAY

    'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
    'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
    'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
    'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
    'sidebar-dark'                              Dark themed sidebar

    'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
    'side-overlay-o'                            Visible Side Overlay by default

    'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

    'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

  HEADER

    ''                                          Static Header if no class is added
    'page-header-fixed'                         Fixed Header

  HEADER STYLE

    ''                                          Light themed Header
    'page-header-dark'                          Dark themed Header

  MAIN CONTENT LAYOUT

    ''                                          Full width Main Content if no class is added
    'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
    'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)
-->
    <div id="page-container" class="sidebar-o enable-page-overlay sidebar-dark side-scroll page-header-fixed main-content-narrow">
        <!-- Side Overlay-->
        <aside id="side-overlay" class="fs-sm">
            <!-- Side Header -->
            <div class="content-header border-bottom">
                <!-- User Avatar -->
                <a class="img-link me-1" href="javascript:void(0)">
                    <img class="img-avatar img-avatar32" src="{{ asset('media/avatars/avatar10.jpg') }}" alt="">
                </a>
                <!-- END User Avatar -->

                <!-- User Info -->
                <div class="ms-2">
                    <a class="text-dark fw-semibold fs-sm" href="javascript:void(0)">{{ auth()->user()->name }}</a>
                </div>
                <!-- END User Info -->

                <!-- Close Side Overlay -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <a class="ms-auto btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_close">
                    <i class="fa fa-fw fa-times"></i>
                </a>
                <!-- END Close Side Overlay -->
            </div>
            <!-- END Side Header -->

            <!-- Side Content -->
            <div class="content-side">
                <p>
                    Content..
                </p>
            </div>
            <!-- END Side Content -->
        </aside>
        <!-- END Side Overlay -->

        @include("layouts.sidebar")

        <!-- Header -->
        <header id="page-header">
            <!-- Header Content -->
            <div class="content-header">
                <!-- Left Section -->
                <div class="d-flex align-items-center">
                    <!-- Toggle Sidebar -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                    <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                    <!-- END Toggle Sidebar -->

                    <!-- Open Search Section (visible on smaller screens) -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <!-- <button type="button" class="btn btn-sm btn-alt-secondary d-md-none" data-toggle="layout" data-action="header_search_on">
                    <i class="fa fa-fw fa-search"></i>
                </button> -->
                    <!-- END Open Search Section -->

                    <!-- Search Form (visible on larger screens) -->
                    <!-- <form class="d-none d-md-inline-block" action="/dashboard" method="POST">
                    @csrf
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control form-control-alt" placeholder="Search.." id="page-header-search-input2" name="page-header-search-input2">
                        <span class="input-group-text border-0">
                <i class="fa fa-fw fa-search"></i>
              </span>
                    </div>
                </form> -->
                    <!-- END Search Form -->
                </div>
                <!-- END Left Section -->

                <!-- Right Section -->
                <div class="d-flex align-items-center">
                    <!-- User Dropdown -->
                    <div class="dropdown d-inline-block ms-2">
                        <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle" src="{{ asset('media/avatars/avatar10.jpg') }}" alt="Header Avatar" style="width: 21px;">
                            <span class="d-none d-sm-inline-block ms-2">{{ auth()->user()->name }}</span>
                            <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ms-1 mt-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0" aria-labelledby="page-header-user-dropdown">
                            <div class="p-3 text-center bg-body-light border-bottom rounded-top">
                                <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ asset('media/avatars/avatar10.jpg') }}" alt="">
                                <p class="mt-2 mb-0 fw-medium">{{ auth()->user()->name }}</p>
                                <p class="mb-0 text-muted fs-sm fw-medium">{{ auth()->user()->email }}</p>
                            </div>
                            {{-- <div class="p-2">--}}
                            {{-- <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('profile') }}">--}}
                            {{-- <span class="fs-sm fw-medium">Perfil</span>--}}
                            {{-- </a>--}}
                            {{-- </div>--}}
                            <div role="separator" class="dropdown-divider m-0"></div>
                            <div class="p-2">

                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                    <span class="fs-sm fw-medium">Cerrar sesión</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- END User Dropdown -->

                    <!-- Notifications Dropdown -->
                    {{-- <div class="dropdown d-inline-block ms-2">--}}
                    {{-- <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                    {{-- <i class="fa fa-fw fa-bell"></i>--}}
                    {{-- <span class="text-primary">•</span>--}}
                    {{-- </button>--}}
                    {{-- <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0 fs-sm" aria-labelledby="page-header-notifications-dropdown">--}}
                    {{-- <div class="p-2 bg-body-light border-bottom text-center rounded-top">--}}
                    {{-- <h5 class="dropdown-header text-uppercase">Notifications</h5>--}}
                    {{-- </div>--}}
                    {{-- <ul class="nav-items mb-0">--}}
                    {{-- <li>--}}
                    {{-- <a class="text-dark d-flex py-2" href="javascript:void(0)">--}}
                    {{-- <div class="flex-shrink-0 me-2 ms-3">--}}
                    {{-- <i class="fa fa-fw fa-check-circle text-success"></i>--}}
                    {{-- </div>--}}
                    {{-- <div class="flex-grow-1 pe-2">--}}
                    {{-- <div class="fw-semibold">You have a new follower</div>--}}
                    {{-- <span class="fw-medium text-muted">15 min ago</span>--}}
                    {{-- </div>--}}
                    {{-- </a>--}}
                    {{-- </li>--}}
                    {{-- <li>--}}
                    {{-- <a class="text-dark d-flex py-2" href="javascript:void(0)">--}}
                    {{-- <div class="flex-shrink-0 me-2 ms-3">--}}
                    {{-- <i class="fa fa-fw fa-plus-circle text-primary"></i>--}}
                    {{-- </div>--}}
                    {{-- <div class="flex-grow-1 pe-2">--}}
                    {{-- <div class="fw-semibold">1 new sale, keep it up</div>--}}
                    {{-- <span class="fw-medium text-muted">22 min ago</span>--}}
                    {{-- </div>--}}
                    {{-- </a>--}}
                    {{-- </li>--}}
                    {{-- <li>--}}
                    {{-- <a class="text-dark d-flex py-2" href="javascript:void(0)">--}}
                    {{-- <div class="flex-shrink-0 me-2 ms-3">--}}
                    {{-- <i class="fa fa-fw fa-times-circle text-danger"></i>--}}
                    {{-- </div>--}}
                    {{-- <div class="flex-grow-1 pe-2">--}}
                    {{-- <div class="fw-semibold">Update failed, restart server</div>--}}
                    {{-- <span class="fw-medium text-muted">26 min ago</span>--}}
                    {{-- </div>--}}
                    {{-- </a>--}}
                    {{-- </li>--}}
                    {{-- <li>--}}
                    {{-- <a class="text-dark d-flex py-2" href="javascript:void(0)">--}}
                    {{-- <div class="flex-shrink-0 me-2 ms-3">--}}
                    {{-- <i class="fa fa-fw fa-plus-circle text-primary"></i>--}}
                    {{-- </div>--}}
                    {{-- <div class="flex-grow-1 pe-2">--}}
                    {{-- <div class="fw-semibold">2 new sales, keep it up</div>--}}
                    {{-- <span class="fw-medium text-muted">33 min ago</span>--}}
                    {{-- </div>--}}
                    {{-- </a>--}}
                    {{-- </li>--}}
                    {{-- <li>--}}
                    {{-- <a class="text-dark d-flex py-2" href="javascript:void(0)">--}}
                    {{-- <div class="flex-shrink-0 me-2 ms-3">--}}
                    {{-- <i class="fa fa-fw fa-user-plus text-success"></i>--}}
                    {{-- </div>--}}
                    {{-- <div class="flex-grow-1 pe-2">--}}
                    {{-- <div class="fw-semibold">You have a new subscriber</div>--}}
                    {{-- <span class="fw-medium text-muted">41 min ago</span>--}}
                    {{-- </div>--}}
                    {{-- </a>--}}
                    {{-- </li>--}}
                    {{-- <li>--}}
                    {{-- <a class="text-dark d-flex py-2" href="javascript:void(0)">--}}
                    {{-- <div class="flex-shrink-0 me-2 ms-3">--}}
                    {{-- <i class="fa fa-fw fa-check-circle text-success"></i>--}}
                    {{-- </div>--}}
                    {{-- <div class="flex-grow-1 pe-2">--}}
                    {{-- <div class="fw-semibold">You have a new follower</div>--}}
                    {{-- <span class="fw-medium text-muted">42 min ago</span>--}}
                    {{-- </div>--}}
                    {{-- </a>--}}
                    {{-- </li>--}}
                    {{-- </ul>--}}
                    {{-- <div class="p-2 border-top text-center">--}}
                    {{-- <a class="d-inline-block fw-medium" href="javascript:void(0)">--}}
                    {{-- <i class="fa fa-fw fa-arrow-down me-1 opacity-50"></i> Load More..--}}
                    {{-- </a>--}}
                    {{-- </div>--}}
                    {{-- </div>--}}
                    {{-- </div>--}}
                    <!-- END Notifications Dropdown -->

                    <!-- Toggle Side Overlay -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <!-- <button type="button" class="btn btn-sm btn-alt-secondary ms-2" data-toggle="layout" data-action="side_overlay_toggle">
                    <i class="fa fa-fw fa-list-ul fa-flip-horizontal"></i>
                </button> -->
                    <!-- END Toggle Side Overlay -->
                </div>
                <!-- END Right Section -->
            </div>
            <!-- END Header Content -->

            <!-- Header Search -->
            <div id="page-header-search" class="overlay-header bg-body-extra-light">
                <div class="content-header">
                    <form class="w-100" action="/dashboard" method="POST">
                        @csrf
                        <div class="input-group">
                            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                            <button type="button" class="btn btn-alt-danger" data-toggle="layout" data-action="header_search_off">
                                <i class="fa fa-fw fa-times-circle"></i>
                            </button>
                            <input type="text" class="form-control" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Header Search -->

            <!-- Header Loader -->
            <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
            <div id="page-header-loader" class="overlay-header bg-body-extra-light">
                <div class="content-header">
                    <div class="w-100 text-center">
                        <i class="fa fa-fw fa-circle-notch fa-spin"></i>
                    </div>
                </div>
            </div>
            <!-- END Header Loader -->
        </header>
        <!-- END Header -->

        <!-- Main Container -->
        <main id="main-container">
            @yield('content')
        </main>
        <!-- END Main Container -->

        <!-- Footer -->
        <footer id="page-footer" class="bg-body-light">
            <div class="content py-3">
                <div class="row fs-sm">
                    <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-end">
                        @include('partials.copyright')
                    </div>
                    <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-start">
                        <b>{{ config('app.name') }}</b> &copy; <span data-toggle="year-copy"></span>
                    </div>
                </div>
            </div>
        </footer>
        <!-- END Footer -->
    </div>
    <!-- END Page Container -->


    @vite(['resources/js/oneui/app.js'])
    @livewireScripts
    <script src="{{ asset('js/setTheme.js') }}"></script>
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/customs/delete-confirmation.js') }}"></script>
    @stack('scripts')

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal', (data) => {
                Swal.fire({
                    title: data[0].title,
                    text: data[0].message,
                    icon: data[0].icon,
                });

                if (data[0].redirect) {
                    setTimeout(() => {
                        window.location.href = data[0].redirect;
                    }, 2000);
                }
            });
        });
    </script>
    @if (session('swal'))
    <script>
        Swal.fire({
            title: "{{ session('swal.title') }}",
            text: "{{ session('swal.message') }}",
            icon: "{{ session('swal.icon') }}",
            confirmButtonText: 'OK'
        });
    </script>
    @endif


</body>

</html>