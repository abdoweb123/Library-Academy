@extends('admin/layouts/master')

@section('title')
    {{ trns('borrowings') }}
@endsection
@section('page_name')
    {{ trns('borrowings') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> {{ trns('borrowings') }} </h3>
                    <div class="">
                        @can('create_borrowings')
                            <a href="{{ route('dashboard.borrowings.create') }}" class="btn btn-primary mb-2">
                                {{ trns('add_new') }}
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th>#</th>
                                    <th class="min-w-50px">{{ trns('student') }}</th>
                                    <th class="min-w-50px">{{ trns('book_copy') }}</th>
                                    <th class="min-w-50px">{{ trns('borrow_date') }}</th>
                                    <th class="min-w-50px">{{ trns('due_date') }}</th>
                                    <th class="min-w-50px">{{ trns('return_date') }}</th>
                                    <th class="min-w-50px">{{ trns('status') }}</th>
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

    <div id="modalsContainer"></div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            const commonExportOptions = {
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6]
                },
                title: '{{ trns("borrowings") }}'
            };

            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,

                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'student', name: 'student.name' },
                    { data: 'book_copy', name: 'bookCopy.book_code' },
                    { data: 'borrow_date', name: 'borrow_date' },
                    { data: 'due_date', name: 'due_date' },
                    { data: 'return_date', name: 'return_date' },
                    { data: 'status', name: 'status' },
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
