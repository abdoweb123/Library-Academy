@extends('admin/layouts/master')

@section('title')
    @if (Route::is('dashboard.borrowings.create'))
        {{ trns('add_borrowing') }}
    @elseif (Route::is('dashboard.borrowings.edit'))
        {{ trns('edit_borrowing') }}
    @elseif (Route::is('dashboard.borrowings.show'))
        {{ trns('show_borrowing') }}
    @endif
@endsection

@section('page_name')
    @if (Route::is('dashboard.borrowings.create'))
        {{ trns('add_borrowing') }}
    @elseif (Route::is('dashboard.borrowings.edit'))
        {{ trns('edit_borrowing') }}
    @elseif (Route::is('dashboard.borrowings.show'))
        {{ trns('show_borrowing') }}
    @endif
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
                    <h3 class="card-title">
                        @if (Route::is('dashboard.borrowings.create'))
                            {{ trns('add_borrowing') }}
                        @elseif (Route::is('dashboard.borrowings.edit'))
                            {{ trns('edit_borrowing') }}
                        @elseif (Route::is('dashboard.borrowings.show'))
                            {{ trns('show_borrowing') }}
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $isShow   = Route::is('dashboard.borrowings.show');
                        $isEdit   = Route::is('dashboard.borrowings.edit');
                        $isCreate = Route::is('dashboard.borrowings.create');

                        $maxBorrowDays     = (int) (setting('max_borrow_days') ?: 14);
                        $defaultBorrowDate = \Carbon\Carbon::today()->format('Y-m-d');
                        $defaultDueDate    = \Carbon\Carbon::today()->addDays($maxBorrowDays)->format('Y-m-d');

                        $action = $isEdit
                            ? route('dashboard.borrowings.update', $borrowing->id)
                            : route('dashboard.borrowings.store');
                    @endphp
                    <form method="POST" action="{{ $isShow ? '#' : $action }}">
                        @csrf
                        @if($isEdit) @method('PUT') @endif

                        <div class="row">

                            {{-- Student --}}
                            <div class="col-md-6">
                                <label>{{ trns('student') }}</label>
                                <select name="student_id" id="student_id"
                                        class="form-control student-select">
                                    @if(!$isCreate)
                                        <option value="{{ $borrowing->student_id }}" selected>
                                            {{ $borrowing->student->name }}
                                        </option>
                                    @endif
                                </select>

                                @if($isCreate)
                                    <button type="button" class="btn btn-sm btn-link mt-1"
                                            data-bs-toggle="modal" data-bs-target="#studentModal">
                                        + {{ trns('add_new') }}
                                    </button>
                                @endif
                            </div>

                            {{-- Book --}}
                            <div class="col-md-6">
                                <label>{{ trns('book') }}</label>
                                <select name="book_id" id="book_id"
                                        class="form-control select2">
                                    <option value="">----</option>
                                
                                    @php
                                        $selectedBook = $borrowing->bookCopy->book_id ?? null;
                                    @endphp

                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}"
                                            {{ old('book_id', $selectedBook) == $book->id ? 'selected' : '' }}>

                                            {{ $book->title }}

                                            {{-- ({{ $book->available_copies_count }} {{ trns('available_copies') }}) --}}

                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Borrow Date --}}
                            <x-form-input
                                type="date"
                                name="borrow_date"
                                label="borrow_date"
                                :value="old('borrow_date', $borrowing->borrow_date ?? $defaultBorrowDate)"
                                :required="'required'"
                                :cols="'col-md-3'"
                            />

                            {{-- Due Date --}}
                            <x-form-input
                                type="date"
                                name="due_date"
                                label="due_date"
                                :value="old('due_date', $borrowing->due_date ?? $defaultDueDate)"
                                :required="'required'"
                                :cols="'col-md-3'"
                            />
                            

                            @if(!$isCreate)
                                {{-- Return Date --}}
                                <x-form-input
                                    type="date"
                                    name="return_date"
                                    label="return_date"
                                    :value="old('return_date', $borrowing->return_date ?? '')"
                                    :cols="'col-md-3'"
                                />
                            
                                {{-- Status --}}
                                <div class="col-md-3 mt-2">
                                    <label>{{ trns('status') }}</label>
                                    <select name="status" class="form-control select2">
                                        @foreach(['borrowed', 'returned' ] as $s)
                                            <option value="{{ $s }}"
                                                {{ old('status', $borrowing->status ?? 'borrowed') == $s ? 'selected' : '' }}>
                                                {{ trns($s) }}
                                            </option>
                                        @endforeach
                                    </select>
                                
                                </div>

                                @if($lateMessage)
                                    <div class="alert alert-danger mt-2 mx-3">
                                        {{ $lateMessage }}
                                    </div>
                                @endif
                            @endif

                        </div>{{-- /.row --}}


                        @isset($borrowing->bookCopy)
                            <hr>

                            <div class="card shadow-sm border-0 mb-4">

                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        {{ trns('book_copy_details') }}
                                    </h5>
                                </div>
                            
                                <div class="card-body">
                            
                                    <div class="row g-3">
                            
                                        <div class="col-md-6">
                                            <strong>{{ trns('book_title') }}:</strong>
                                            <span class="text-muted">
                                                {{ $borrowing->bookCopy->book->title ?? '' }}
                                            </span>
                                        </div>
                            
                                        <div class="col-md-6">
                                            <strong>{{ trns('book_code') }}:</strong>
                                            <span class="text-muted">
                                                {{ $borrowing->bookCopy->book_code }}
                                            </span>
                                        </div>
                            
                                        <div class="col-md-6">
                                            <strong>{{ trns('general_number') }}:</strong>
                                            <span class="text-muted">
                                                {{ $borrowing->bookCopy->general_number }}
                                            </span>
                                        </div>
                            
                                        <div class="col-md-6">
                                            <strong>{{ trns('classification_number') }}:</strong>
                                            <span class="text-muted">
                                                {{ $borrowing->bookCopy->classification_number }}
                                            </span>
                                        </div>
                            
                                        <div class="col-md-6">
                                            <strong>{{ trns('shelf_number') }}:</strong>
                                            <span class="text-muted">
                                                {{ $borrowing->bookCopy->shelf_number }}
                                            </span>
                                        </div>
                            
                                        <div class="col-md-6">
                                            <strong>{{ trns('publish_year') }}:</strong>
                                            <span class="text-muted">
                                                {{ $borrowing->bookCopy->publish_year }}
                                            </span>
                                        </div>
                            
                                    </div>
                            
                                </div>


                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        {{ trns('student_course_details') }}
                                    </h5>
                                </div>
                                <div class="card-body">
                            
                                    <div class="row g-3">
                            
                                        <div class="col-md-6">
                                            <strong>{{ trns('course_name') }}:</strong>
                                            <span class="text-muted">
                                                {{ $borrowing->student->course->name ?? '' }}
                                            </span>
                                        </div>
                            
                                        <div class="col-md-6">
                                            <strong>{{ trns('supervisor_name') }}:</strong>
                                            <span class="text-muted">
                                                {{ $borrowing->student->course->supervisor_name ?? '' }}
                                            </span>
                                        </div>

                                        <div class="col-md-6">
                                            <strong>{{ trns('supervisor_phone') }}:</strong>
                                            <span class="text-muted">
                                                {{ $borrowing->student->course->supervisor_phone ?? '' }}
                                            </span>
                                        </div>
                            
                                    </div>
                            
                                </div>
                            
                            </div>
                        @endisset
                        

                        <hr>

                        @if(!$isShow)
                            <button type="submit" class="btn btn-primary">
                                {{ trns('save') }}
                            </button>
                        @endif

                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($isCreate)
        @include('dashboard.borrowings.modals.student')
    @endif

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

                $('.course-select').select2({
                    width: '100%',
                    placeholder: "{{ trns('select_course') }}",
                    ajax: {
                        url: "{{ route('dashboard.courses.search') }}",
                        dataType: 'json',
                        delay: 250,

                        data: function (params) {
                            return {
                                q: params.term
                            };
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
                $('.course-select').next('.select2-container').css('margin-top', '7px');
                
            });
            

            @if(Route::is('dashboard.borrowings.create'))
                function saveStudent() {
                    let name     = $('#student_name').val();
                    let email    = $('#student_email').val();
                    let phone    = $('#student_phone').val();
                    let password = $('#student_password').val();
                    let course_id = $('#student_course_id').val();

                    $.ajax({
                        url: "{{ route('dashboard.students.storeAjax') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            name: name,
                            email: email,
                            phone: phone,
                            password: password,
                            course_id: course_id
                        },
                        success: function (res) {
                            let newOption = new Option(res.name, res.id, true, true);
                            $('#student_id').append(newOption).trigger('change');
                            $('#studentModal').modal('hide');
                            $('#student_name, #student_email, #student_phone, #student_password').val('');
                            $('#student_course_id').val('').trigger('change');
                        }
                    });
                }
            @endif
        </script>
    
        <script>
            const isShowPage = "{{ Route::is('dashboard.borrowings.show') ? 1 : 0 }}";

            if (isShowPage == 1) {
                document.addEventListener('DOMContentLoaded', function () {

                    const form = document.querySelector('form');

                    if (!form) return;

                    // inputs → readonly
                    form.querySelectorAll('input, textarea').forEach(el => {
                        el.readOnly = true;
                    });

                    // select → disabled
                    form.querySelectorAll('select').forEach(el => {
                        el.disabled = true;
                    });

                    // 🔥 حذف كل الأزرار داخل الفورم
                    form.querySelectorAll('button, input[type="button"], input[type="submit"]').forEach(btn => {
                        btn.remove();
                    });

                });
            }
        </script>


        <script>
            const isEditPage = "{{ Route::is('dashboard.borrowings.edit') ? 1 : 0 }}";


            document.addEventListener('DOMContentLoaded', function () {

                if (isEditPage == 1) {

                    const form = document.querySelector('form');
                    if (!form) return;

                    form.querySelectorAll('input, select, textarea').forEach(el => {
                        const name = el.getAttribute('name');

                        // استثناءات
                        if (
                            name === 'return_date' ||
                            name === 'status' ||
                            name === '_method' ||
                            name === '_token'
                        ) {
                            el.disabled = false;
                            return;
                        }

                        el.disabled = true;
                    });
                }
            });
        </script>
@endpush
