@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.ciudad.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.ciudads.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="nombre">{{ trans('cruds.ciudad.fields.nombre') }}</label>
                            <input class="form-control" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                            @if($errors->has('nombre'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nombre') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.ciudad.fields.nombre_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="provincia_id">{{ trans('cruds.ciudad.fields.provincia') }}</label>
                            <select class="form-control select2" name="provincia_id" id="provincia_id" required>
                                @foreach($provincias as $id => $entry)
                                    <option value="{{ $id }}" {{ old('provincia_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('provincia'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('provincia') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.ciudad.fields.provincia_helper') }}</span>
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