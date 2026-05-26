<!-- Edit Modal -->
<div class="modal fade" id="editModal_{{ $model->id }}" tabindex="-1" aria-labelledby="editModalLabel_{{ $model->id }}" aria-hidden="true">
    <div class="modal-dialog" style="{{$variables['modal_dialog_width'] ?? ''}}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel_{{ $model->id }}">{{ trns('edit') }}</h5>
                <button type="button" class="close px-3 btn btn-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(array_key_exists('image', $model->getAttributes()))
                    <div class="d-flex justify-content-center">
                        <!-- Image preview container -->
                        <img class="imagePreview" src="{{ getModelImage($model->image) }}" alt="" style="max-width: 200px; max-height: 200px;">
                    </div>
                @endif
                <!-- Edit form -->
                <form id="editModelForm_{{$model->id}}" action="{{ route($editRoute, $model->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @php $checkboxRendered = false; @endphp

                    <div class="row {{$variables['main_row'] ?? 'justify-content-center'}}">
                        @foreach ($inputs as $input)

                            @if ($input['type'] === 'hidden')
                                <input type="{{$input['type']}}" value="{{$input['value']}}" class="form-control {{ $input['name'] }}" id="{{ $input['name'] }}_{{ $model->id }}" name="{{ $input['name'] }}">
                            @else

                                <div class=" row {{ $variables['cols'] ?? 'col-md-12' }} col-sm-12 mt-2">
                                    @if (in_array($input['type'], ['text', 'file', 'number', 'email', 'password', 'date', 'datetime-local']))
                                        <label class="p-0" for="{{ $input['name'] }}">{{ $input['label'] }}</label>
                                        <input type="{{$input['type']}}" class="form-control {{ $input['name'] }}" id="{{ $input['name'] }}_{{ $model->id }}" name="{{ $input['name'] }}" value="{{ $input['type'] === 'password' ? '' : $model->{$input['name']} }}" onchange="previewImage(this)">
                                        @if ($input['type'] === 'password')
                                            <i class="fas fa-eye position-absolute toggle-password" style="cursor: pointer; {{ lang('ar') ? 'left: 28px;' : 'right: 28px;' }}  top: 57%;  width: 10px;" onclick="togglePasswordVisibility('{{ $input['name'] }}')"></i>
                                        @endif

                                    @elseif ($input['type'] === 'select')
                                        <label class="p-0" for="{{ $input['name'] }}">{{ $input['label'] }}</label>
                                        <select class="form-control @if(isset($input['select2']) && $input['select2'] == 0) @else select2 @endif"
                                                id="{{ $input['name'] }}_{{ $model->id }}" name="{{ $input['name'] }}">
                                            <option value="">----</option>
                                            @foreach ($input['options'] as $optionValue => $optionLabel)
                                                <option value="{{ $optionValue }}" {{ in_array($optionValue, $input['selected_options']) ? 'selected' : '' }}>{{ $optionLabel }}</option>
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

                                        @php
                                            $checkedValues = $input['checked_values'] ?? [];
                                        @endphp

                                        <h4 class="p-0 col-12 mt-2">{{ $input['label'] }}</h4>
                                        @foreach ($input['options'] as $option)
                                            <div class="form-check {{ $input['inputClass'] ?? '' }} {{ $variables['minCols'] ?? 'col-md-12'  }} col-sm-12 px-4" style="{{ $input['inputStyle'] ?? '' }}">
                                                <input class="form-check-input" type="checkbox" id="{{ $input['name'] }}_{{ $option['value'] }}_{{ $model->id }}" name="{{ $input['name'] }}[]" value="{{ $option['value'] }}" {{ in_array($option['value'], $checkedValues) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="{{ $input['name'] }}_{{ $option['value'] }}" onclick="return false;">
                                                    {{ $option['label'] }}
                                                </label>
                                            </div>
                                        @endforeach

                                    @elseif ($input['type'] === 'textarea')
                                        <label class="p-0" for="{{ $input['name'] }}">{{ $input['label'] }}</label>
                                        <textarea class="form-control" id="{{ $input['name'] }}_{{ $model->id }}" name="{{ $input['name'] }}" rows="{{ $input['rows']?? '3' }}">{{ $model->{$input['name']} }}</textarea>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <button type='button' onclick='updateModel({{$model->id}})' class="btn btn-primary mt-3  {{$variables['save_class'] ?? ''}}">{{ trns('save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
