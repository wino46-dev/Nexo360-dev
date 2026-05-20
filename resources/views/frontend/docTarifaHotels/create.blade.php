@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.docTarifaHotel.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.doc-tarifa-hotels.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="establecimiento_id">{{ trans('cruds.docTarifaHotel.fields.establecimiento') }}</label>
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
                            <span class="help-block">{{ trans('cruds.docTarifaHotel.fields.establecimiento_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="tarifa">{{ trans('cruds.docTarifaHotel.fields.tarifa') }}</label>
                            <input class="form-control" type="text" name="tarifa" id="tarifa" value="{{ old('tarifa', '') }}" required>
                            @if($errors->has('tarifa'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tarifa') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docTarifaHotel.fields.tarifa_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.docTarifaHotel.fields.regimen') }}</label>
                            <select class="form-control" name="regimen" id="regimen">
                                <option value disabled {{ old('regimen', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\DocTarifaHotel::REGIMEN_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('regimen', 'Media Pensión') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('regimen'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('regimen') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docTarifaHotel.fields.regimen_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="habitacion_id">{{ trans('cruds.docTarifaHotel.fields.habitacion') }}</label>
                            <select class="form-control select2" name="habitacion_id" id="habitacion_id">
                                @foreach($habitacions as $id => $entry)
                                    <option value="{{ $id }}" {{ old('habitacion_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('habitacion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('habitacion') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docTarifaHotel.fields.habitacion_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="importe">{{ trans('cruds.docTarifaHotel.fields.importe') }}</label>
                            <input class="form-control" type="number" name="importe" id="importe" value="{{ old('importe', '') }}" step="0.01" required>
                            @if($errors->has('importe'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('importe') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docTarifaHotel.fields.importe_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="fecha">{{ trans('cruds.docTarifaHotel.fields.fecha') }}</label>
                            <input class="form-control date" type="text" name="fecha" id="fecha" value="{{ old('fecha') }}">
                            @if($errors->has('fecha'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fecha') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docTarifaHotel.fields.fecha_helper') }}</span>
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