<!-- Show Modal -->
<div class="modal fade" id="showModal_{{ $model->id }}" tabindex="-1" aria-labelledby="showModalLabel_{{ $model->id }}" aria-hidden="true">
    <div class="modal-dialog" style="{{$variables['modal_dialog_width'] ?? ''}}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showModalLabel_{{ $model->id }}">{{ trns('show') }}</h5>
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
                <form id="showModelForm_{{$model->id}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row {{$variables['main_row'] ?? 'justify-content-center'}}">
                        @foreach ($inputs as $input)
                            @if ($input['show'] == 1)
                                <div class=" row {{ $variables['cols'] ?? 'col-md-12' }} col-sm-12 mt-2">
                                @if (in_array($input['type'], ['text', 'file', 'number', 'email', 'password', 'date', 'datetime-local']))
                                    <label class="p-0" for="{{ $input['name'] }}">{{ $input['label'] }}</label>
                                    <input type="{{$input['type']}}" class="form-control {{ $input['name'] }}" name="{{ $input['name'] }}" value="{{ $input['type'] === 'password' ? '' : $model->{$input['name']} }}" disabled>

                                @elseif ($input['type'] === 'select')
                                    <label class="p-0" for="{{ $input['name'] }}">{{ $input['label'] }}</label>
                                    <select class="form-control" name="{{ $input['name'] }}" disabled>
                                        <option value="">----</option>
                                        @foreach ($input['options'] as $optionValue => $optionLabel)
                                            <option value="{{ $optionValue }}" {{ in_array($optionValue, $input['selected_options']) ? 'selected' : '' }}>{{ $optionLabel }}</option>
                                        @endforeach
                                    </select>

                                @elseif ($input['type'] === 'checkbox')

                                        @php
                                            $checkedValues = $input['checked_values'] ?? [];
                                        @endphp

                                     <h4 class="p-0 col-12 mt-2">{{ $input['label'] }}</h4>
                                    @foreach ($input['options'] as $option)
                                        <div class="form-check {{ $input['inputClass'] ?? '' }} {{ $variables['minCols'] ?? 'col-md-12'  }} col-sm-12 px-4" style="{{ $input['inputStyle'] ?? '' }}">
                                            <input class="form-check-input" type="checkbox" name="{{ $input['name'] }}[]" value="{{ $option['value'] }}" {{ in_array($option['value'], $checkedValues) ? 'checked' : '' }} onclick="return false;">
                                            <label class="form-check-label" for="{{ $input['name'] }}_{{ $option['value'] }}" onclick="return false;">
                                                {{ $option['label'] }}
                                            </label>
                                        </div>
                                    @endforeach

                                @elseif ($input['type'] === 'textarea')
                                    <label class="p-0" for="{{ $input['name'] }}">{{ $input['label'] }}</label>
                                    <textarea class="form-control" name="{{ $input['name'] }}" rows="{{ $input['rows']?? '3' }}" disabled>{{ $model->{$input['name']} }}</textarea>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
