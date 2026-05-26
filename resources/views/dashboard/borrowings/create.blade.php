@extends('admin/layouts/master')

@section('title')
    {{ trns('add_borrowing') }}
@endsection

@section('page_name')
    {{ trns('add_borrowing') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-header d-flex">
                    <h3 class="card-title">{{ trns('add_borrowing') }}</h3>
                </div>
                <div class="card-body">
                    @php
                        $maxBorrowDays = (int) (setting('max_borrow_days') ?: 14);
                        $defaultBorrowDate = \Carbon\Carbon::today()->format('Y-m-d');
                        $defaultDueDate = \Carbon\Carbon::today()->addDays($maxBorrowDays)->format('Y-m-d');
                    @endphp
                    <form method="POST" action="{{ route('dashboard.borrowings.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <label>{{ trns('student') }}</label>
                                <select name="student_id" id="student_id" class="form-control student-select">
                                    @if(old('student_id'))
                                        <option value="{{ old('student_id') }}" selected>{{ old('student_id') }}</option>
                                    @endif
                                </select>

                                <button type="button" class="btn btn-sm btn-link mt-1"
                                        data-bs-toggle="modal" data-bs-target="#studentModal">
                                    + {{ trns('add_new') }}
                                </button>
                            </div>

                            <div class="col-md-6">
                                <label>{{ trns('book') }}</label>
                                <select name="book_id" id="book_id" class="form-control select2">
                                    <option value="">----</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                            {{ $book->title }} ({{ $book->available_copies_count }} {{ trns('available_copies') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <x-form-input
                                type="date"
                                name="borrow_date"
                                label="borrow_date"
                                :value="old('borrow_date', $defaultBorrowDate)"
                                :required="'required'"
                                :cols="'col-md-3'"
                            />

                            <x-form-input
                                type="date"
                                name="due_date"
                                label="due_date"
                                :value="old('due_date', $defaultDueDate)"
                                :required="'required'"
                                :cols="'col-md-3'"
                            />

                            <x-form-input
                                type="date"
                                name="return_date"
                                label="return_date"
                                :value="old('return_date')"
                                :cols="'col-md-3'"
                            />

                            <div class="col-md-3 mt-2">
                                <label>{{ trns('status') }}</label>
                                <select name="status" class="form-control select2">
                                    <option value="borrowed" {{ old('status', 'borrowed') == 'borrowed' ? 'selected' : '' }}>{{ trns('borrowed') }}</option>
                                    <option value="returned" {{ old('status') == 'returned' ? 'selected' : '' }}>{{ trns('returned') }}</option>
                                    <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>{{ trns('late') }}</option>
                                </select>
                            </div>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-primary">
                            {{ trns('save') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.borrowings.modals.student')
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.student-select').select2({
                placeholder: "{{ trns('select_student') }}",
                ajax: {
                    url: "{{ route('dashboard.students.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: item.name
                            }))
                        };
                    }
                }
            });
        });

        function saveStudent() {
            let name = $('#student_name').val();
            let email = $('#student_email').val();
            let phone = $('#student_phone').val();
            let password = $('#student_password').val();

            $.ajax({
                url: "{{ route('dashboard.students.storeAjax') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name,
                    email: email,
                    phone: phone,
                    password: password
                },
                success: function (res) {
                    let newOption = new Option(res.name, res.id, true, true);

                    $('#student_id')
                        .append(newOption)
                        .trigger('change');

                    $('#studentModal').modal('hide');

                    $('#student_name').val('');
                    $('#student_email').val('');
                    $('#student_phone').val('');
                    $('#student_password').val('');
                }
            });
        }
    </script>
@endpush
