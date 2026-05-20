@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.configuracionGrabador.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.configuracion-grabadors.update", [$configuracionGrabador->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="totem_id">{{ trans('cruds.configuracionGrabador.fields.totem') }}</label>
                <select class="form-control select2 {{ $errors->has('totem') ? 'is-invalid' : '' }}" name="totem_id" id="totem_id" required>
                    @foreach($totems as $id => $entry)
                        <option value="{{ $id }}" {{ (old('totem_id') ? old('totem_id') : $configuracionGrabador->totem->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('totem'))
                    <div class="invalid-feedback">
                        {{ $errors->first('totem') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.totem_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.configuracionGrabador.fields.software_gestion') }}</label>
                <select class="form-control {{ $errors->has('software_gestion') ? 'is-invalid' : '' }}" name="software_gestion" id="software_gestion" required>
                    <option value disabled {{ old('software_gestion', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ConfiguracionGrabador::SOFTWARE_GESTION_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('software_gestion', $configuracionGrabador->software_gestion) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('software_gestion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('software_gestion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.software_gestion_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="reader_no">{{ trans('cruds.configuracionGrabador.fields.reader_no') }}</label>
                <input class="form-control {{ $errors->has('reader_no') ? 'is-invalid' : '' }}" type="number" name="reader_no" id="reader_no" value="{{ old('reader_no', $configuracionGrabador->reader_no) }}" step="1">
                @if($errors->has('reader_no'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reader_no') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.reader_no_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="track_2">{{ trans('cruds.configuracionGrabador.fields.track_2') }}</label>
                <input class="form-control {{ $errors->has('track_2') ? 'is-invalid' : '' }}" type="text" name="track_2" id="track_2" value="{{ old('track_2', $configuracionGrabador->track_2) }}">
                @if($errors->has('track_2'))
                    <div class="invalid-feedback">
                        {{ $errors->first('track_2') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.track_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="seq_mode">{{ trans('cruds.configuracionGrabador.fields.seq_mode') }}</label>
                <input class="form-control {{ $errors->has('seq_mode') ? 'is-invalid' : '' }}" type="text" name="seq_mode" id="seq_mode" value="{{ old('seq_mode', $configuracionGrabador->seq_mode) }}" required>
                @if($errors->has('seq_mode'))
                    <div class="invalid-feedback">
                        {{ $errors->first('seq_mode') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.seq_mode_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="show_message">{{ trans('cruds.configuracionGrabador.fields.show_message') }}</label>
                <input class="form-control {{ $errors->has('show_message') ? 'is-invalid' : '' }}" type="text" name="show_message" id="show_message" value="{{ old('show_message', $configuracionGrabador->show_message) }}" required>
                @if($errors->has('show_message'))
                    <div class="invalid-feedback">
                        {{ $errors->first('show_message') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.show_message_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="user_host">{{ trans('cruds.configuracionGrabador.fields.user_host') }}</label>
                <input class="form-control {{ $errors->has('user_host') ? 'is-invalid' : '' }}" type="text" name="user_host" id="user_host" value="{{ old('user_host', $configuracionGrabador->user_host) }}" required>
                @if($errors->has('user_host'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user_host') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.configuracionGrabador.fields.user_host_helper') }}</span>
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