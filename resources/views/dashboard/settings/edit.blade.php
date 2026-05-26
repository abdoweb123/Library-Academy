@extends('admin/layouts/master')

@section('title')
    {{ trns('settings') }} @if($type) : {{ trns($type) }} @endif
@endsection

@section('page_name')
    {{ trns('settings') }} @if($type) : {{ trns($type) }} @endif
@endsection



@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="card">
            <div class="card-body modal-body">
                <form action="{{ route('dashboard.admin.settings.update',['id' => 1, 'type' => $type]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        @foreach ($Models as $setting)
                            <div class="col-sm-12">
                                <div class="form-group">
                                    @if($setting->input_type != 'hidden')
                                        <label for="{{ $setting->key }}">{{ trns($setting->key) }}</label>
                                    @endif

                                    @if ($setting->input_type == 'text')
                                        <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                                    @elseif ($setting->input_type == 'number')
                                        <input type="number" name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                                    @elseif ($setting->input_type == 'datetime-local')
                                        <input type="datetime-local" name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                                    @elseif ($setting->input_type == 'textarea')
                                        <textarea name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control">{{ $setting->value }}</textarea>
                                    @elseif ($setting->input_type == 'select' && $setting->type == 'filterDate')
                                        <select name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control">
                                            <option value="on" {{ $setting->value == 'on' ? 'selected' : '' }}>on</option>
                                            <option value="off" {{ $setting->value == 'off' ? 'selected' : '' }}>off</option>
                                        </select>
                                    @elseif ($setting->input_type == 'file')
                                        <input type="file" name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control" accept="image/*" onchange="previewImage(this)">
                                        @if($setting->value)
                                            <div class="text-center my-2" id="preview-{{ $setting->key }} ">
                                                <img src="{{ asset($setting->value) }}" alt="{{ $setting->key }}" class="imagePreview" style="height: 200px;">
                                            </div>
                                        @endif
                                    @elseif ($setting->input_type == 'color')
                                        <input type="color" name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type='submit' class="btn btn-primary mt-2">{{ trns('save') }}</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <!-- Update settings -->
    <script>
        $(document).on('change', '#font', function(){
            $('#fontPreview').css('font-family', $(this).val());
        });
    </script>

    <!-- preview Image -->
    <script>
        function previewImage(event, key) {
            var reader = new FileReader();
            reader.onload = function(){
                var previewDiv = document.getElementById('preview-' + key);
                if (previewDiv) {
                    var imgElement = previewDiv.querySelector('img');
                    if (imgElement) {
                        imgElement.src = reader.result;
                        previewDiv.style.display = 'block';
                    }
                }
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@stop
