@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.eventoHomeTotem.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.evento-home-totems.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="receptor_id">{{ trans('cruds.eventoHomeTotem.fields.receptor') }}</label>
                            <select class="form-control select2" name="receptor_id" id="receptor_id" required>
                                @foreach($receptors as $id => $entry)
                                    <option value="{{ $id }}" {{ old('receptor_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('receptor'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('receptor') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.eventoHomeTotem.fields.receptor_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="tipo_evento_id">{{ trans('cruds.eventoHomeTotem.fields.tipo_evento') }}</label>
                            <select class="form-control select2" name="tipo_evento_id" id="tipo_evento_id" required>
                                @foreach($tipo_eventos as $id => $entry)
                                    <option value="{{ $id }}" {{ old('tipo_evento_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                            <label for="objeto">{{ trans('cruds.eventoHomeTotem.fields.objeto') }}</label>
                            <textarea class="form-control" name="objeto" id="objeto">{{ old('objeto') }}</textarea>
                            @if($errors->has('objeto'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('objeto') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.eventoHomeTotem.fields.objeto_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.eventoHomeTotem.fields.canal_transmision') }}</label>
                            <select class="form-control" name="canal_transmision" id="canal_transmision">
                                <option value disabled {{ old('canal_transmision', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\EventoHomeTotem::CANAL_TRANSMISION_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('canal_transmision', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('canal_transmision'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('canal_transmision') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.eventoHomeTotem.fields.canal_transmision_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection