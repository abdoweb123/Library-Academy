@extends('admin/layouts/master')

@section('title')
    @if (Route::is('dashboard.books.create'))
        {{ trns('add_book') }}
    @elseif (Route::is('dashboard.books.edit'))
        {{ trns('edit_book') }}
    @elseif (Route::is('dashboard.books.show'))
        {{ trns('show_book') }}
    @else
        {{ trns('the_books') }}
    @endif
@endsection

@section('page_name')
    @if (Route::is('dashboard.books.create'))
        {{ trns('add_book') }}
    @elseif (Route::is('dashboard.books.edit'))
        {{ trns('edit_book') }}
    @elseif (Route::is('dashboard.books.show'))
        {{ trns('show_book') }}
    @else
        {{ trns('the_books') }}
    @endif
@endsection

@push('styles')
    <style>
      
    </style>
@endpush


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
                        @if (Route::is('dashboard.books.create'))
                            {{ trns('add_book') }}
                        @elseif (Route::is('dashboard.books.edit'))
                            {{ trns('edit_book') }}
                        @elseif (Route::is('dashboard.books.show'))
                            {{ trns('show_book') }}
                        @else
                            {{ trns('the_books') }}
                        @endif  
                    </h3>
                    <div class="creation">

                    </div>
                </div>
                <div class="card-body">

                    <form method="POST" action="{{ isset($book) 
                      ? route('dashboard.books.update', $book->id) : route('dashboard.books.store') }}">
                        
                        @csrf
                        @if(isset($book))
                            @method('PUT')
                        @endif

                        {{-- 📘 Book Info --}}
                        <div class="row">
                            <x-form-input
                                type="text"
                                name="title"
                                label="Book Title"
                                id="title"
                                :value="old('title', $book->title ?? '')"
                                :required="'required'"
                                :cols="'col-md-4'"
                            />

                            

                            {{-- 🏢 Publisher --}}
                            <div class="col-md-4">
                                <label>{{ trns('publisher') }}</label>
                                <select name="publisher_id" id="publisher_id" class="form-control publisher-select">
                                    @if(isset($book) && $book_copies->first()->publisher)
                                        <option value="{{ $book_copies->first()->publisher?->id }}" selected>
                                            {{ $book_copies->first()->publisher?->name }}
                                        </option>
                                    @endif
                                </select>
                            
                                <button type="button" class="btn btn-sm btn-link mt-1"
                                        data-bs-toggle="modal" data-bs-target="#publisherModal">
                                    + {{ trns('add_new') }}
                                </button>
                            </div>

                            {{-- 📂 Section --}}
                            <div class="col-md-4">
                                <label>{{ trns('section') }}</label>
                            
                                <select name="section_id" id="section_id" class="form-control section-select">
                                    @if(isset($book) && $book->section)
                                        <option value="{{ $book->section->id }}" selected>
                                            {{ $book->section->name }}
                                        </option>
                                    @endif
                                </select>
                            
                                <button type="button" class="btn btn-sm btn-link mt-1"
                                        data-bs-toggle="modal" data-bs-target="#sectionModal">
                                    + {{ trns('add_new') }}
                                </button>
                            </div>

                            <div class="col-md-2 mt-3">
                                <label>{{ trns('authors') }}</label>
                                <select name="authors[]" id="authors" class="form-control authors-select" multiple>
                                    @if(isset($book))
                                        @foreach($book->people->where('pivot.role', 'author') as $author)
                                            <option value="{{ $author->id }}" selected>
                                                {{ $author->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            
                                <button type="button" class="btn btn-sm btn-link mt-1"
                                        data-bs-toggle="modal" data-bs-target="#authorModal">
                                    + {{ trns('add_new') }}
                                </button>
                            </div>

                            <div class="col-md-2 mt-3">
                                <label>{{ trns('translators') }}</label>
                            
                                <select name="translators[]" id="translators" class="form-control translators-select" multiple>
                                    @if(isset($book))
                                        @foreach($book->people->where('pivot.role', 'translator') as $translator)
                                            <option value="{{ $translator->id }}" selected>
                                                {{ $translator->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            
                                <button type="button" class="btn btn-sm btn-link mt-1"
                                        data-bs-toggle="modal" data-bs-target="#translatorModal">
                                    + {{ trns('add_new') }}
                                </button>
                            </div>


                            <div class="col-md-2 mt-3">
                                <label>{{ trns('location') }}</label>
                            
                                <select name="location_id[]" id="location_id" class="form-control location-select" multiple>
                                    @if(isset($book))
                                        <option value="{{ $book->shelf_id }}">
                                            {{ $book->name }}
                                        </option>
                                    @endif
                                </select>
                        
                            </div>
                            
                            <div class="row col-md-4">
                                <x-form-input
                                    type="number"
                                    name="publish_year"
                                    label="Publish Year"
                                    id="publish_year"
                                    :value="old('publish_year',  $book_copies?->first()?->publish_year ?? '')"
                                    :cols="'col-md'"
                                />

                                <x-form-input
                                    type="number"
                                    name="pages"
                                    label="Pages"
                                    id="pages"
                                     :value="old('pages', $book_copies?->first()?->pages ?? '')"
                                    :cols="'col-md'"
                                />

                                <x-form-input
                                    type="text"
                                    name="size"
                                    label="Size"
                                    id="size"
                                    :value="old('size', $book_copies?->first()?->size ?? '')"
                                    :cols="'col-md'"
                                />
                            </div>
                            

                        </div>
                        
                        <hr>

                        {{-- 📚 Copies --}}
                        <h5>{{ trns('add_new') }}</h5>

                        <div id="copies-wrapper">

                            @if(isset($book) && $book->copies->count())
                                @foreach($book->copies as $i => $copy)
                                <div class="row copy-item">
                                    <div class="copy-row row col-md-11 g-2">
                        
                                        <x-form-input
                                            name="copies[{{ $i }}][general_number]"
                                            label="General Number"
                                            :value="$copy->general_number"
                                            :cols="'col-md'"
                                        />
                        
                                        <x-form-input
                                            name="copies[{{ $i }}][classification_number]"
                                            label="Classification"
                                            :value="$copy->classification_number"
                                            :cols="'col-md'"
                                        />
                        
                                        <x-form-input
                                            name="copies[{{ $i }}][shelf_number]"
                                            label="Shelf"
                                            :value="$copy->shelf_number"
                                            :cols="'col-md'"
                                        />
                        
                                        <x-form-input
                                            name="copies[{{ $i }}][book_code]"
                                            label="Code"
                                            :value="$copy->book_code"
                                            :cols="'col-md'"
                                        />
                        
                                        <x-form-input
                                            type="number"
                                            name="copies[{{ $i }}][volumes_number]"
                                            label="Volumes"
                                            :value="$copy->volumes_number"
                                            :cols="'col-md'"
                                        />
                        
                                    </div>
                                    <div class="col m-auto text-center">
                                        <button type="button" class="btn btn-sm btn-danger mt-4"
                                                onclick="removeCopy(this)">
                                            ✖
                                        </button>
                                    </div>
                                </div>
                                    
                                   
                                @endforeach
                            @else
                                {{-- create mode --}}
                                <div class="row copy-item">
                                    <div class="copy-row row col-md-11 g-2">
                                        <x-form-input name="copies[0][general_number]" label="General Number" :cols="'col-md'" />
                                        <x-form-input name="copies[0][classification_number]" label="Classification" :cols="'col-md'" />
                                        <x-form-input name="copies[0][shelf_number]" label="Shelf" :cols="'col-md'" />
                                        <x-form-input name="copies[0][book_code]" label="Code" :cols="'col-md'" />
                                        <x-form-input type="number" name="copies[0][volumes_number]" label="Volumes" :cols="'col-md'" />
                                    </div>
                                    <div class="col m-auto text-center">
                                        <button type="button" class="btn btn-sm btn-danger mt-4"
                                                onclick="removeCopy(this)">
                                            ✖
                                        </button>
                                    </div>
                                </div>
                            @endif
                        
                        </div>

                        <button type="button" onclick="addCopy()" class="btn btn-sm btn-success">
                            + {{ trns('add_new') }}
                        </button>

                        <hr>

                        <button type="submit" class="btn btn-primary">
                            {{ trns('save') }}
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>

 
@include('dashboard.books.modals.publisher')
@include('dashboard.books.modals.section')
@include('dashboard.books.modals.author')
@include('dashboard.books.modals.translator')

@endsection

@push('scripts')

    {{-- select publisher --}}
    <script>
        $(document).ready(function () {
        
            $('.publisher-select').select2({
                placeholder: "{{ trns('select_publisher') }}",
                ajax: {
                    url: "{{ route('dashboard.publishers.search') }}",
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
        
        });
    </script>

    {{-- save publisher --}}
    <script>
        function savePublisher() {
        
            let name = $('#publisher_name').val();
        
            $.ajax({
                url: "{{ route('dashboard.publishers.storeAjax') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name
                },
                success: function (res) {
        
                    let newOption = new Option(res.name, res.id, true, true);
        
                    $('#publisher_id')
                        .append(newOption)
                        .trigger('change');
        
                    $('#publisherModal').modal('hide');
        
                    $('#publisher_name').val('');
                }
            });
        
        }
    </script>


    {{-- select section --}}
    <script>
        $(document).ready(function () {
        
            $('.section-select').select2({
                placeholder: "{{ trns('select_section') }}",
                ajax: {
                    url: "{{ route('dashboard.sections.search') }}",
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
        
        });
    </script>

    {{-- save section --}}
    <script>
        function saveSection() {
        
            let name = $('#section_name').val();
        
            $.ajax({
                url: "{{ route('dashboard.sections.storeAjax') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name
                },
                success: function (res) {
        
                    let newOption = new Option(res.name, res.id, true, true);
        
                    $('#section_id')
                        .append(newOption)
                        .trigger('change');
        
                    $('#sectionModal').modal('hide');
        
                    $('#section_name').val('');
                }
            });
        
        }
    </script>

    {{-- select author --}}
    <script>
        $(document).ready(function () {
        
            $('.authors-select').select2({
                placeholder: "{{ trns('select_authors') }}",
                multiple: true,
                ajax: {
                    url: "{{ route('dashboard.authors.search') }}",
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
        
        });
    </script>

    {{-- save author --}}
    <script>
        function saveAuthor() {
        
            let name = $('#author_name').val();
        
            $.ajax({
                url: "{{ route('dashboard.authors.storeAjax') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name
                },
                success: function (res) {
        
                    let newOption = new Option(res.name, res.id, true, true);
        
                    $('#authors')
                        .append(newOption)
                        .trigger('change');
        
                    $('#authorModal').modal('hide');
        
                    $('#author_name').val('');
                }
            });
        
        }
    </script>

    {{-- select translators --}}
    <script>
        $(document).ready(function () {
        
            $('.translators-select').select2({
                placeholder: "{{ trns('select_translators') }}",
                multiple: true,
                ajax: {
                    url: "{{ route('dashboard.authors.search') }}", // نفس people
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
    </script>

    {{-- save translators --}}
    <script>
        function saveTranslator() {
        
            let name = $('#translator_name').val();
        
            $.ajax({
                url: "{{ route('dashboard.authors.storeAjax') }}", // نفس people
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name
                },
                success: function (res) {
        
                    let newOption = new Option(res.name, res.id, true, true);
        
                    $('#translators')
                        .append(newOption)
                        .trigger('change');
        
                    $('#translatorModal').modal('hide');
        
                    $('#translator_name').val('');
                }
            });
        
        }
    </script>

    {{-- add new copy --}}
    <script>
        let copyIndex = {{ isset($book) ? $book->copies->count() : 1 }};
        
        function addCopy() {
        
            let wrapper = document.getElementById('copies-wrapper');
        
            let first = wrapper.querySelector('.copy-item');
        
            let clone = first.cloneNode(true);
        
            // تنظيف inputs
            clone.querySelectorAll('input').forEach(input => {
        
                input.value = '';
        
                let name = input.getAttribute('name');
        
                // استبدال copies[index]
                let newName = name.replace(/copies\[\d+\]/, `copies[${copyIndex}]`);
        
                input.setAttribute('name', newName);
        
                // تنظيف id لو موجود
                if (input.id) {
                    input.id = input.id.replace(/\d+/, copyIndex);
                }
            });
        
            // تنظيف labels
            clone.querySelectorAll('label').forEach(label => {
        
                let forAttr = label.getAttribute('for');
        
                if (forAttr) {
                    label.setAttribute('for', forAttr.replace(/\d+/, copyIndex));
                }
            });
        
            wrapper.appendChild(clone);
        
            copyIndex++;
        }
    </script>

    {{-- Remove new copy --}}
    <script>
        function removeCopy(btn) {
            let rows = document.querySelectorAll('#copies-wrapper .copy-item');

                if (rows.length <= 1) {
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ trns("Not allowed") }}',
                        text: '{{ trns("You must keep at least one copy") }}',
                    });
                    return;
                }

            btn.closest('.copy-item').remove();

            reindexCopies();
        }
    </script>
   <script>
        function reindexCopies() {

            let rows = document.querySelectorAll('#copies-wrapper .copy-item');

            rows.forEach((row, index) => {

                row.querySelectorAll('input').forEach(input => {

                    let name = input.getAttribute('name');

                    if (!name) return;

                    // name update
                    let newName = name.replace(/copies\[\d+\]/, `copies[${index}]`);
                    input.setAttribute('name', newName);

                    // id update (IMPORTANT FIX)
                    if (input.id) {

                        let newId = input.id.replace(/copies\[\d+\]/, `copies[${index}]`);

                        input.setAttribute('id', newId);
                    }
                });

                // labels
                row.querySelectorAll('label').forEach(label => {

                    let forAttr = label.getAttribute('for');

                    if (forAttr) {
                        label.setAttribute('for',
                            forAttr.replace(/copies\[\d+\]/, `copies[${index}]`)
                        );
                    }
                });

            });

            copyIndex = rows.length;
        }
    </script>



    <script>
        const isShowPage = "{{ Route::is('dashboard.books.show') ? 1 : 0 }}";

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
@endpush
