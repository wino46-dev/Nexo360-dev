@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.respuestaEventoHomeTotem.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.respuesta-evento-home-totems.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="evento_id">{{ trans('cruds.respuestaEventoHomeTotem.fields.evento') }}</label>
                <select class="form-control select2 {{ $errors->has('evento') ? 'is-invalid' : '' }}" name="evento_id" id="evento_id" required>
                    @foreach($eventos as $id => $entry)
                        <option value="{{ $id }}" {{ old('evento_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('evento'))
                    <div class="invalid-feedback">
                        {{ $errors->first('evento') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaEventoHomeTotem.fields.evento_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="respuesta">{{ trans('cruds.respuestaEventoHomeTotem.fields.respuesta') }}</label>
                <textarea class="form-control {{ $errors->has('respuesta') ? 'is-invalid' : '' }}" name="respuesta" id="respuesta" required>{{ old('respuesta') }}</textarea>
                @if($errors->has('respuesta'))
                    <div class="invalid-feedback">
                        {{ $errors->first('respuesta') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaEventoHomeTotem.fields.respuesta_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.respuestaEventoHomeTotem.fields.estado') }}</label>
                <select class="form-control {{ $errors->has('estado') ? 'is-invalid' : '' }}" name="estado" id="estado" required>
                    <option value disabled {{ old('estado', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\RespuestaEventoHomeTotem::ESTADO_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('estado', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('estado'))
                    <div class="invalid-feedback">
                        {{ $errors->first('estado') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.respuestaEventoHomeTotem.fields.estado_helper') }}</span>
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