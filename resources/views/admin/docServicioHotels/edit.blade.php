@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.docServicioHotel.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.doc-servicio-hotels.update", [$docServicioHotel->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="hotel_id">{{ trans('cruds.docServicioHotel.fields.hotel') }}</label>
                <select class="form-control select2 {{ $errors->has('hotel') ? 'is-invalid' : '' }}" name="hotel_id" id="hotel_id" required>
                    @foreach($hotels as $id => $entry)
                        <option value="{{ $id }}" {{ (old('hotel_id') ? old('hotel_id') : $docServicioHotel->hotel->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('hotel'))
                    <div class="invalid-feedback">
                        {{ $errors->first('hotel') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docServicioHotel.fields.hotel_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nombre">{{ trans('cruds.docServicioHotel.fields.nombre') }}</label>
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" type="text" name="nombre" id="nombre" value="{{ old('nombre', $docServicioHotel->nombre) }}" required>
                @if($errors->has('nombre'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docServicioHotel.fields.nombre_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="codigo">{{ trans('cruds.docServicioHotel.fields.codigo') }}</label>
                <input class="form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }}" type="text" name="codigo" id="codigo" value="{{ old('codigo', $docServicioHotel->codigo) }}">
                @if($errors->has('codigo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('codigo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docServicioHotel.fields.codigo_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="precio">{{ trans('cruds.docServicioHotel.fields.precio') }}</label>
                <input class="form-control {{ $errors->has('precio') ? 'is-invalid' : '' }}" type="number" name="precio" id="precio" value="{{ old('precio', $docServicioHotel->precio) }}" step="0.01">
                @if($errors->has('precio'))
                    <div class="invalid-feedback">
                        {{ $errors->first('precio') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docServicioHotel.fields.precio_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('por_persona') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="por_persona" value="0">
                    <input class="form-check-input" type="checkbox" name="por_persona" id="por_persona" value="1" {{ $docServicioHotel->por_persona || old('por_persona', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="por_persona">{{ trans('cruds.docServicioHotel.fields.por_persona') }}</label>
                </div>
                @if($errors->has('por_persona'))
                    <div class="invalid-feedback">
                        {{ $errors->first('por_persona') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docServicioHotel.fields.por_persona_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('por_dia') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="por_dia" value="0">
                    <input class="form-check-input" type="checkbox" name="por_dia" id="por_dia" value="1" {{ $docServicioHotel->por_dia || old('por_dia', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="por_dia">{{ trans('cruds.docServicioHotel.fields.por_dia') }}</label>
                </div>
                @if($errors->has('por_dia'))
                    <div class="invalid-feedback">
                        {{ $errors->first('por_dia') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docServicioHotel.fields.por_dia_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.docServicioHotel.fields.tipo') }}</label>
                <select class="form-control {{ $errors->has('tipo') ? 'is-invalid' : '' }}" name="tipo" id="tipo">
                    <option value disabled {{ old('tipo', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\DocServicioHotel::TIPO_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('tipo', $docServicioHotel->tipo) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('tipo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tipo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.docServicioHotel.fields.tipo_helper') }}</span>
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