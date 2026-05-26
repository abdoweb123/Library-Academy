@extends('admin/layouts/master')
@section('title')
    {{ trns('library_structure') }}
@endsection
@section('library_structure')
@endsection

@push('styles')
    <style>
        .inline-input {
            width: 100%;
            border: 1px solid #ddd;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .sector-loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .tree-level{
            padding-inline-start: 25px;
            border-inline-start: 2px dashed #dcdcdc;
            margin-top: 10px;
        }
    </style>
@endpush

@section('content')
    
<div class="text-left mb-2">
    <button class="btn btn-sm btn-red add-sector-btn">
        + {{ trns('sector') }}
    </button>    
</div>

<div id="structure-container" class="row">

    @foreach($sectors as $sector)

        <div class="col-md-4 mb-3 sector-item"
             data-id="{{ $sector->id }}">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    {{-- ================= SECTOR ================= --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <h5 class="mb-0">
                            <i class="fa fa-layer-group toggle-btn"></i> 
                            <i class="fa fa-plus toggle-btn"
                                style="cursor:pointer"
                                data-target="#sector-{{ $sector->id }}-rows">
                            </i>
                            {{ $sector->name }}
                        </h5>

                        <button class="btn btn-sm btn-primary add-row-btn"
                                data-sector-id="{{ $sector->id }}">
                            + {{ trns('row') }}
                        </button>
 
                    </div>

                    <hr>

                    {{-- ================= ROWS ================= --}}
                    <div class="rows-container tree-level "  id="sector-{{ $sector->id }}-rows">

                        @foreach($sector->rows as $row)

                            <div class="row-item ms-2 mb-2"
                                 data-id="{{ $row->id }}">

                                <div class="d-flex justify-content-between align-items-center">

                                    <span>
                                        <i class="fa fa-grip-lines"></i>
                                        <i class="fa fa-plus toggle-btn"
                                            data-target="#row-{{ $row->id }}-cabinets"
                                            style="cursor:pointer">
                                        </i>
                                        {{ $row->name }}  
                                        <span class="text-danger p-2">{{ trns($row->side) }}</span>
                                    </span>  

                                    <div class="d-flex align-items-center gap-2">

                                        <button class="btn btn-sm btn-success add-cabinet-btn"
                                                data-row-id="{{ $row->id }}">
                                          + {{  trns('cabinet') }}
                                        </button>

                                    </div>

                                </div>

                                <hr class="my-2">

                                {{-- ================= CABINETS ================= --}}
                                <div class="cabinets-container tree-level"  id="row-{{ $row->id }}-cabinets">

                                    @foreach($row->cabinets as $cabinet)

                                        <div class="cabinet-item mb-2"
                                             data-id="{{ $cabinet->id }}">

                                            <div class="d-flex justify-content-between align-items-center">

                                                <span> <i class="fa fa-box"></i>
                                                    <i class="fa fa-plus toggle-btn"
                                                        data-target="#cabinet-{{ $cabinet->id }}-shelves"
                                                        style="cursor:pointer">
                                                    </i>
                                                    {{ $cabinet->name }}</span>

                                                <div class="d-flex align-items-center gap-2">

                                                    <button class="btn btn-sm btn-warning add-shelf-btn"
                                                            data-cabinet-id="{{ $cabinet->id }}">
                                                        + {{ trns('shelf') }}
                                                    </button>

                                                </div>

                                            </div>

                                            

                                            {{-- ================= SHELVES ================= --}}
                                            <div class="shelves-container tree-level" id="cabinet-{{ $cabinet->id }}-shelves">

                                                @foreach($cabinet->shelves as $shelf)

                                                    <hr class="my-2">

                                                    <div class="shelf-item  mb-1"
                                                         data-id="{{ $shelf->id }}">

                                                        <span><i class="fa fa-book"></i>
                                                            <i class="fa fa-plus toggle-btn"
                                                                data-target="#shelf-{{ $shelf->id }}-copies"
                                                                style="cursor:pointer">
                                                            </i>
                                                            {{ $shelf->name }}</span>

                                                        {{-- ================= BOOK COPIES ================= --}}
                                                        <div class="book-copies-container tree-level" id="shelf-{{ $shelf->id }}-copies">

                                                           <div class="row">
                                                                @foreach($shelf->bookCopies as $copy)
                                                                    
                                                                    <div class="book-copy-item mb-1 p-2">

                                                                        <span>
                                                                            <i class="fa fa-book-open text-primary"></i>

                                                                            {{ $copy->book->title ?? '-' }}

                                                                            <small class="text-muted">
                                                                                (#{{ $copy->general_number }})
                                                                            </small>
                                                                        </span>

                                                                    </div>

                                                                @endforeach
                                                           </div>

                                                        </div>
                                                    </div>

                                                @endforeach

                                            </div>

                                        </div>
                                        <hr class="my-2">
                                    @endforeach

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

        </div>

    @endforeach

</div>

    @include('dashboard.library_structure.modals.create_sector')
    @include('dashboard.library_structure.modals.create_row')
    @include('dashboard.library_structure.modals.create_cabinet')
    @include('dashboard.library_structure.modals.create_shelf')

@endsection

@push('scripts')

<script src="{{ asset('assets/js/Sortable.min.js') }}"></script>

{{-- create Sector Form --}}
<script>
    $('#createSectorForm').submit(function(e){

        e.preventDefault();

        $.ajax({

            url: "{{ route('dashboard.sectors.store') }}",
            method: "POST",
            data: $(this).serialize(),

            success: function(response){

                location.reload();

            }

        });

    });
</script>

{{-- edit sector name by dblclick --}}
<script>
   $(document).on('dblclick', '.sector-name .text-value', function () {

        let span = $(this);
        let currentText = span.text().trim();
        let sectorWrapper = span.closest('.sector-name');
        let sectorId = sectorWrapper.data('id');

        let input = $('<input type="text" class="form-control inline-input">')
            .val(currentText);

        span.replaceWith(input);
        input.focus();

        let isSaved = false;

        function save() {

            let newName = input.val().trim();

            // 1. validation
            if (newName === '') {
                alert('Name cannot be empty');
                input.focus();
                return;
            }

            // 2. prevent unnecessary request
            if (newName === currentText) {
                input.replaceWith(`<span class="text-value">${currentText}</span>`);
                return;
            }

            // 3. loading UI
            sectorWrapper.addClass('sector-loading');

            $.ajax({
                url: "{{ route('dashboard.sectors.update', ':id') }}".replace(':id', sectorId),
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: newName
                },
                success: function () {

                    let newSpan = $('<span class="text-value"></span>').text(newName);

                    input.replaceWith(newSpan);

                    sectorWrapper.removeClass('sector-loading');

                    isSaved = true;
                },
                error: function () {

                    alert('Error updating sector');

                    sectorWrapper.removeClass('sector-loading');

                    input.focus();
                }
            });
        }

        // ENTER or BLUR save
        input.on('keypress', function (e) {
            if (e.which === 13) {
                save();
            }
        });

        input.on('blur', function () {
            if (!isSaved) save();
        });

        // ESC cancel
        input.on('keydown', function (e) {
            if (e.key === "Escape") {
                input.replaceWith(`<span class="text-value">${currentText}</span>`);
            }
        });

    });
</script>

{{-- open modal when click add-btn --}}
<script>
    $(document).on('click', '.add-sector-btn', function () {

        $('#createSectorModal').modal('show');

    });

    $(document).on('click', '.add-row-btn', function () {

        $('#row_sector_id').val($(this).data('sector-id'));
        $('#rowModal').modal('show');

    });

    $(document).on('click', '.add-cabinet-btn', function () {

        $('#cabinet_row_id').val($(this).data('row-id'));
        $('#cabinetModal').modal('show');

    });

    $(document).on('click', '.add-shelf-btn', function () {

        $('#shelf_cabinet_id').val($(this).data('cabinet-id'));
        $('#shelfModal').modal('show');

    });
</script>

{{-- save modals forms --}}
<script>
    $('#rowForm').submit(function(e){

        e.preventDefault();

        $.ajax({
            url: "{{ route('dashboard.rows.store') }}",
            method: "POST",
            data: $(this).serialize(),

            success: function () {
                location.reload();
            },

            error: function(xhr) {

                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function(key, value) {

                        $('.' + key + '-error').html(value[0]);

                    });
                }
            }

        });

    });


    $('#cabinetForm').submit(function(e){

        e.preventDefault();

        $.ajax({
            url: "{{ route('dashboard.cabinets.store') }}",
            method: "POST",
            data: $(this).serialize(),

            success: function () {
                location.reload();
            },

            error: function(xhr) {

                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function(key, value) {

                        $('.' + key + '-error').html(value[0]);

                    });
                }
            }

        });

    });

    $('#shelfForm').submit(function(e){

        e.preventDefault();

        $.ajax({
            url: "{{ route('dashboard.shelves.store') }}",
            method: "POST",
            data: $(this).serialize(),

            success: function () {
                location.reload();
            },

            error: function(xhr) {

                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function(key, value) {

                        $('.' + key + '-error').html(value[0]);

                    });
                }
            }

        });

    });
</script>


{{-- reorder sectors --}}
<script>
    new Sortable(document.getElementById('structure-container'), {

        animation: 150,
        draggable: '.sector-item',

        onEnd: function (evt) {

            let draggedId = $(evt.item).data('id');

            // نجيب كل العناصر بالترتيب الجديد من DOM بعد السحب
            let orderedIds = [];

            $('.sector-item').each(function () {
                orderedIds.push($(this).data('id'));
            });

            $.ajax({
                url: "{{ route('dashboard.sectors.reorder') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    ordered_ids: orderedIds
                },
                success: function () {
                    // location.reload(); // مهم جدًا
                    $('.sector-item').each(function (index) {
                        $(this).find('.arrange-badge').text('#' + (index + 1));
                    });
                }
            });
        }
    });
</script>


<script>
    $(document).on('click', '.toggle-btn', function () {

        let icon = $(this);
        let target = $(icon.data('target'));

        target.toggle();

        if (target.is(':visible')) {
            icon.removeClass('fa-plus').addClass('fa-minus');
        } else {
            icon.removeClass('fa-minus').addClass('fa-plus');
        }

    });
</script>
@endpush