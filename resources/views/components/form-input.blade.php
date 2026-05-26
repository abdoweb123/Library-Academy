@props([
    'type' => 'text',
    'name',
    'label' => '',
    'value' => '',
    'id' => null,
    'class' => 'form-control',
    'cols' => 'col-md-6',
])

<div class="mb-3 {{  $cols ?? 'col-md-6' }}"
     style="{{ $type === 'hidden' ? 'display: none' : '' }}"
>
    <label for="{{ $name }}" class="form-label">{{ trns($label) }}</label>

    @if($type === 'textarea')
        <textarea
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            class="form-control"
             {{ $required ?? '' }}
        >{{ old($name, $value) }}</textarea>



    @elseif($type === 'select')
        <select name="{{ $name }}" id="{{ $id ?? $name }}" class="form-control {{ $noSelect2 ?? 'select2' }}"
            {{ $required ?? '' }}
        >

            @if(!empty($defaultOption))
                <option
                    value="{{ $defaultOptionValue ?? '' }}"
                    {{ ($defaultOptionValue ?? '') == $value ? 'selected' : '' }}
                >
                    {{ $defaultOption }}
                </option>
            @endif

            @isset($options)
                @foreach($options as $key => $option)
                    @if(is_array($option) || is_object($option))
                        <option value="{{ $option['id'] ?? $option->id }}"
                            {{ ($option['id'] ?? $option->id) == $value ? 'selected' : '' }}>
                            {{ $option['title'] ?? $option->title }}
                        </option>
                    @else
                        <option value="{{ is_string($key) ? $key : $option }}"
                            {{ (is_string($key) ? $key : $option) == $value ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endif
                @endforeach
                @endisset

            </select>

    @else
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            class="{{ $class ?? 'form-control' }}"
            {{ $step ?? '' }}
            {{ $min ?? '' }}
            {{ $max ?? '' }}
            value="{{ old($name, $value) }}"
            {{ $required ?? '' }}
        />
    @endif
</div>
