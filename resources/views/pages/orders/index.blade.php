@extends('layouts.backend')
@push('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
@endpush

@push('scripts')
    @include('partials.datatables-js')
    <!-- Bootstrap Datepicker JS -->
    <script src="{{ asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>

    {{ $dataTable->scripts() }}

    <script>
        function initStatusFilter() {
            var table = $('#order-table').DataTable();

            $('#status-filter').on('change', function() {
                var status = $(this).val();
                table.column(6).search(status).draw(); // Status column is index 6
            });
        }

        function initDateRangeFilter() {
            var table = $('#order-table').DataTable();

            // Initialize bootstrap datepickers
            $('#start-date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                language: 'es'
            }).on('changeDate', function() {
                applyDateFilter();
            });

            $('#end-date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                language: 'es'
            }).on('changeDate', function() {
                applyDateFilter();
            });

            function applyDateFilter() {
                var startDate = $('#start-date').val();
                var endDate = $('#end-date').val();

                if (startDate && endDate) {
                    var dateRange = startDate + ' - ' + endDate;
                    table.column(4).search(dateRange).draw();
                } else if (!startDate && !endDate) {
                    table.column(4).search('').draw();
                }
            }
        }

        function clearAllFilters() {
            var table = $('#order-table').DataTable();

            // Clear status filter
            $('#status-filter').val('all');

            // Clear date filters
            $('#start-date').datepicker('clearDates');
            $('#end-date').datepicker('clearDates');

            // Clear search columns and redraw
            table.columns().search('').draw();
        }

        // Initialize filters when DataTable is ready
        $(document).ready(function() {
            // Add a small delay to ensure DataTable is fully initialized
            setTimeout(function() {
                initStatusFilter();
                initDateRangeFilter();
            }, 100);
        });
    </script>
@endpush

@section('content')
    <x-hero
        :title="__('crud.orders.plural')"
    ></x-hero>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    {{ __('crud.orders.orders_list') }}
                </h3>
                <div class="block-options">
                    @can('orders.create')
                        <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary p-2 rounded-pill text-white">
                            <i class="fa fa-plus me-1"></i>
                            {{ __('crud.orders.add') }}
                        </a>
                    @endcan
                </div>
            </div>
            <div class="block-content">
                <!-- Filters Form -->
                <form class="mb-2">
                    <div class="row items-push">
                        <div class="col-md-4">
                            <label class="form-label" for="status-filter">Estado</label>
                            <select id="status-filter" class="form-select">
                                <option value="all">Todos los Estados</option>
                                <option value="pending">Pendiente</option>
                                <option value="in_progress">En Progreso</option>
                                <option value="completed">Completada</option>
                                <option value="canceled">Cancelada</option>
                            </select>
                        </div>
                        <div class="col-md-3">

                            <label class="form-label" for="start-date">Fecha Inicio</label>
                            <input type="text" id="start-date" class="form-control" placeholder="dd/mm/aaaa" readonly>

                        </div>
                        <div class="col-md-3">

                            <label class="form-label" for="end-date">Fecha Fin</label>
                            <input type="text" id="end-date" class="form-control" placeholder="dd/mm/aaaa" readonly>

                        </div>
                        <div class="col-md-2">

                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-secondary" onclick="clearAllFilters()">
                                    <i class="fa fa-times me-1"></i>
                                    Limpiar
                                </button>
                            </div>

                        </div>
                    </div>
                </form>

                <!-- DataTable -->
                <div class="table-responsive">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection
