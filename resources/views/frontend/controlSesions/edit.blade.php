@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.controlSesion.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.control-sesions.update", [$controlSesion->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="emisor_id">{{ trans('cruds.controlSesion.fields.emisor') }}</label>
                            <select class="form-control select2" name="emisor_id" id="emisor_id" required>
                                @foreach($emisors as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('emisor_id') ? old('emisor_id') : $controlSesion->emisor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('emisor'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('emisor') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.controlSesion.fields.emisor_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="receptor_id">{{ trans('cruds.controlSesion.fields.receptor') }}</label>
                            <select class="form-control select2" name="receptor_id" id="receptor_id" required>
                                @foreach($receptors as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('receptor_id') ? old('receptor_id') : $controlSesion->receptor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('receptor'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('receptor') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.controlSesion.fields.receptor_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="estado_sesion" value="0">
                                <input type="checkbox" name="estado_sesion" id="estado_sesion" value="1" {{ $controlSesion->estado_sesion || old('estado_sesion', 0) === 1 ? 'checked' : '' }}>
                                <label for="estado_sesion">{{ trans('cruds.controlSesion.fields.estado_sesion') }}</label>
                            </div>
                            @if($errors->has('estado_sesion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('estado_sesion') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.controlSesion.fields.estado_sesion_helper') }}</span>
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