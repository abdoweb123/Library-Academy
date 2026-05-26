@extends('admin/layouts/master')

@section('title')
    {{ trns('students') }}
@endsection
@section('page_name')
    {{ trns('students') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> {{ trns('students') }} </h3>
                    <div class="">
                        @can('create_students')
                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#createModal">
                                {{ trns('add_new') }}
                            </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th>#</th>
                                    <th class="min-w-50px">{{ trns('name') }}</th>
                                    <th class="min-w-50px">{{ trns('email') }}</th>
                                    <th class="min-w-50px">{{ trns('phone') }}</th>
                                    <th class="min-w-50px">{{ trns('date') }}</th>
                                    <th class="min-w-50px rounded-end">{{ trns('actions') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
                    columns: [1, 2, 3, 4]
                },
                title: '{{ trns("students") }}'
            };

            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dashboard.students.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                dom: 'Bfrtip',
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
