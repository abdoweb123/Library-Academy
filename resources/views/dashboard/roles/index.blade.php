@extends('admin/layouts/master')

@section('title')
    {{ trns('roles') }}
@endsection
@section('page_name')
    {{ trns('roles') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> {{ trns('roles') }} </h3>
                    <div class="">
                        <!-- add new groups -->
                        @can('create_roles')
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
                                    <th> {{ trns('name') }}</th>
                                    <th> {{ trns('permissions') }}</th>
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
<div id="modalsContainer"></div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

             const commonExportOptions = {
                exportOptions: {
                    columns: [1, 2]
                },
                title: '{{ trns("roles") }}'
            };

            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dashboard.admin.roles.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'name', name: 'name' },
                    { data: 'permissions', name: 'permissions' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                dom: 'Bfrtip',
                // buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
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
@endpush
