@extends('admin/layouts/master')

@section('title')
    {{ trns('publishers') }}
@endsection
@section('page_name')
    {{ trns('publishers') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> {{ trns('publishers') }} </h3>
                    <div class="">
                        @can('create_publishers')
                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#createModal">
                                {{ trns('add_new') }}
                            </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th>#</th>
                                    <th class="min-w-50px">{{ trns('name') }}</th>
                                    <th class="min-w-50px">{{ trns('activity') }}</th>
                                    <th class="min-w-50px rounded-end">{{ trns('actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

@include('dashboard.modals.create')
<!-- Include modals for show, edit, and delete -->
<div id="modalsContainer"></div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            const commonExportOptions = {
                exportOptions: {
                    columns: [1]
                },
                title: '{{ trns("publishers") }}'
            };

            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dashboard.publishers.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'active', name: 'active', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                dom: 'Bfrtip',
                // buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                buttons: [
                    { extend: 'copy', ...commonExportOptions },
                    { extend: 'csv', ...commonExportOptions },
                    { extend: 'excel', ...commonExportOptions },
                    { extend: 'pdf', ...commonExportOptions },
                    { extend: 'print', ...commonExportOptions }
                ]

            });
        });
    </script>


    <script>

        $(document).on('change', '.toggle-active', function() {
            var $this = $(this);
            var id = $this.data('id');

            $.ajax({
                url: '/dashboard/publishers/set-active/' + id,
                type: 'POST',
                data: {
                    active: 1,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        text: "{{ trns('updated_successfully') }}",
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });           
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        text: "{{ trns('error_occurred') }}"
                    });
                    $this.prop('checked', false);
                }
            });
        });

    </script>
@endpush
