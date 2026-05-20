@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.habitacion.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.habitacions.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="establecimiento_id">{{ trans('cruds.habitacion.fields.establecimiento') }}</label>
                            <select class="form-control select2" name="establecimiento_id" id="establecimiento_id" required>
                                @foreach($establecimientos as $id => $entry)
                                    <option value="{{ $id }}" {{ old('establecimiento_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('establecimiento'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('establecimiento') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.habitacion.fields.establecimiento_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="codigo">{{ trans('cruds.habitacion.fields.codigo') }}</label>
                            <input class="form-control" type="text" name="codigo" id="codigo" value="{{ old('codigo', '') }}" required>
                            @if($errors->has('codigo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('codigo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.habitacion.fields.codigo_helper') }}</span>
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