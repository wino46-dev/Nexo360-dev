<form method="POST" id="{{ $config['id'] }}_form" enctype="multipart/form-data">
    @csrf
    @foreach ($config['form_fields'] as $k => $col)
        @php
            $isRequired = isset($col['required']) && $col['required'] === true;
            $labelClass = $isRequired ? 'required' : '';
            $requiredAttribute = $isRequired ? 'required' : '';
        @endphp

        <div class="form-group">
            <label class="{{ $labelClass }}" for="{{ $k }}">
                {{ trans('cruds.' . $config['id'] . '.fields.' . $k) }}
            </label>
            <input class="form-control" type="text" name="{{ $k }}" id="{{ $k }}" value=""
                {{ $requiredAttribute }}>            
        </div>
    @endforeach

</form>
