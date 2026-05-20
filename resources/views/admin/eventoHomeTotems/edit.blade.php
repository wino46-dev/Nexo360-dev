@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.eventoHomeTotem.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.evento-home-totems.update", [$eventoHomeTotem->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="tipo_evento_id">{{ trans('cruds.eventoHomeTotem.fields.tipo_evento') }}</label>
                <select class="form-control select2 {{ $errors->has('tipo_evento') ? 'is-invalid' : '' }}" name="tipo_evento_id" id="tipo_evento_id" required>
                    @foreach($tipo_eventos as $id => $entry)
                        <option value="{{ $id }}" {{ (old('tipo_evento_id') ? old('tipo_evento_id') : $eventoHomeTotem->tipo_evento->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('tipo_evento'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo_evento') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.eventoHomeTotem.fields.tipo_evento_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection