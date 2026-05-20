@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.docMetodoPagoHotel.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.doc-metodo-pago-hotels.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="establecimiento_id">{{ trans('cruds.docMetodoPagoHotel.fields.establecimiento') }}</label>
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
                            <span class="help-block">{{ trans('cruds.docMetodoPagoHotel.fields.establecimiento_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nombre">{{ trans('cruds.docMetodoPagoHotel.fields.nombre') }}</label>
                            <input class="form-control" type="text" name="nombre" id="nombre" value="{{ old('nombre', '') }}">
                            @if($errors->has('nombre'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nombre') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docMetodoPagoHotel.fields.nombre_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.docMetodoPagoHotel.fields.tipo') }}</label>
                            <select class="form-control" name="tipo" id="tipo">
                                <option value disabled {{ old('tipo', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\DocMetodoPagoHotel::TIPO_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('tipo', 'Efectivo') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tipo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tipo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docMetodoPagoHotel.fields.tipo_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="activo" value="0">
                                <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', 0) == 1 ? 'checked' : '' }}>
                                <label for="activo">{{ trans('cruds.docMetodoPagoHotel.fields.activo') }}</label>
                            </div>
                            @if($errors->has('activo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('activo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docMetodoPagoHotel.fields.activo_helper') }}</span>
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