<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="{{$variables['modal_dialog_width'] ?? ''}}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTransactionModalLabel">{{ trns('add_new') }}</h5>
                <button type="button" class="close px-3 btn btn-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <!-- Image preview container -->
                    <img class="imagePreview" src="" alt="" style="max-width: 200px; max-height: 200px; display: none;">
                </div>
                <form id="createForm" method="post" action="{{ route($createRoute) }}" enctype="multipart/form-data">
                    <!-- Example select element to be enhanced by Select2 -->
                    @csrf
                    <div class="row {{$variables['main_row'] ?? 'justify-content-center'}}">

                        @php $checkboxRendered = false; @endphp

                        @foreach ($inputs as $input)
                            @if ($input['type'] === 'hidden')
                                <input type="{{$input['type']}}" value="{{$input['value']}}" class="form-control {{ $input['name'] }}" id="{{ $input['name'] }}" name="{{ $input['name'] }}">
                            @else

                                <div class=" row {{ $variables['cols'] ?? 'col-md-12' }} col-sm-12 mt-2">
                                    @if (in_array($input['type'], ['text', 'file', 'number', 'email', 'password', 'date', 'datetime-local']))
                                        <label class="p-0" for="{{ $input['name'] }}">{{ $input['label'] }}</label>
                                        <input type="{{$input['type']}}" class="form-control {{ $input['name'] }}" id="{{ $input['name'] }}" name="{{ $input['name'] }}" onchange="previewImage(this)" >
                                        @if ($input['type'] === 'password')
                                            <i class="fe fe-eye position-absolute toggle-password" style="cursor: pointer; {{ lang('ar') ? 'left: 28px;' : 'right: 28px;' }} top: 57%;  width: 10px;" onclick="togglePasswordVisibility('{{ $input['name'] }}')"></i>
                                        @endif

                                    @elseif ($input['type'] === 'textarea')
                                        <label class="p-0" for="{{ $input['name'] }}">{{ $input['label'] }}</label>
                                        <textarea class="form-control" id="{{ $input['name'] }}" name="{{ $input['name'] }}" rows="{{ $input['rows']?? '3' }}" ></textarea>

                                    @elseif ($input['type'] === 'select')
                                        <label class="p-0" for="{{ $input['name'] }}">{{ $input['label'] }}</label>
                                        <select class="form-control @if(isset($input['select2']) && $input['select2'] == 0) @else select2 @endif"
                                                id="{{ $input['name'] }}" name="{{ $input['name'] }}">
                                            <option value="" selected>----</option>
                                            @foreach ($input['options'] as $optionValue => $optionLabel)
                                                <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
                                            @endforeach
                                        </select>

                                    @elseif ($input['type'] === 'checkbox')

                                        {{-- Show buttons only once --}}
                                        @if (!$checkboxRendered)
                                            <div class="col-12 mb-1 px-0 mt-2">
                                                <button type="button" class="btn btn-sm btn-secondary mr-1" onclick="checkAll('{{ $input['name'] }}',this)">
                                                    {{ trns('check_all') }}
                                                </button>
                                                <button type="button" class="btn btn-sm btn-warning" onclick="uncheckAll('{{ $input['name'] }}',this)">
                                                    {{ trns('uncheck_all') }}
                                                </button>
                                            </div>
                                            @php $checkboxRendered = true; @endphp
                                        @endif

                                        <h4 class="p-0 col-12 mt-2">{{ $input['label'] }}</h4>
                                        @foreach ($input['options'] as $option)
                                            <div class="form-check {{ $input['inputClass'] ?? '' }} {{ $variables['minCols'] ?? 'col-md-12'  }} col-sm-12 px-4" style="{{ $input['inputStyle'] ?? '' }}">
                                                <input class="form-check-input" type="checkbox" id="{{ $input['name'] }}_{{ $option['value'] }}" name="{{ $input['name'] }}[]" value="{{ $option['value'] }}" >
                                                <label class="form-check-label" for="{{ $input['name'] }}_{{ $option['value'] }}" >
                                                    {{ $option['label'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <button type='button' onclick="createModel()" class="btn btn-primary mt-3 {{$variables['save_class'] ?? ''}}">{{ trns('save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>



