@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.grabacionTarjetum.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.grabacion-tarjeta.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="emisor_id">{{ trans('cruds.grabacionTarjetum.fields.emisor') }}</label>
                <select class="form-control select2 {{ $errors->has('emisor') ? 'is-invalid' : '' }}" name="emisor_id" id="emisor_id" required>
                    @foreach($emisors as $id => $entry)
                        <option value="{{ $id }}" {{ old('emisor_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('emisor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('emisor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.emisor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="receptor_id">{{ trans('cruds.grabacionTarjetum.fields.receptor') }}</label>
                <select class="form-control select2 {{ $errors->has('receptor') ? 'is-invalid' : '' }}" name="receptor_id" id="receptor_id" required>
                    @foreach($receptors as $id => $entry)
                        <option value="{{ $id }}" {{ old('receptor_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('receptor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('receptor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.receptor_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sesion_id">{{ trans('cruds.grabacionTarjetum.fields.sesion') }}</label>
                <select class="form-control select2 {{ $errors->has('sesion') ? 'is-invalid' : '' }}" name="sesion_id" id="sesion_id">
                    @foreach($sesions as $id => $entry)
                        <option value="{{ $id }}" {{ old('sesion_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('sesion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sesion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.sesion_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="date_in">{{ trans('cruds.grabacionTarjetum.fields.date_in') }}</label>
                <input class="form-control date {{ $errors->has('date_in') ? 'is-invalid' : '' }}" type="text" name="date_in" id="date_in" value="{{ old('date_in') }}" required>
                @if($errors->has('date_in'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date_in') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.date_in_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="time_in">{{ trans('cruds.grabacionTarjetum.fields.time_in') }}</label>
                <input class="form-control timepicker {{ $errors->has('time_in') ? 'is-invalid' : '' }}" type="text" name="time_in" id="time_in" value="{{ old('time_in') }}" required>
                @if($errors->has('time_in'))
                    <div class="invalid-feedback">
                        {{ $errors->first('time_in') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.time_in_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="date_out">{{ trans('cruds.grabacionTarjetum.fields.date_out') }}</label>
                <input class="form-control date {{ $errors->has('date_out') ? 'is-invalid' : '' }}" type="text" name="date_out" id="date_out" value="{{ old('date_out') }}" required>
                @if($errors->has('date_out'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date_out') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.date_out_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="time_out">{{ trans('cruds.grabacionTarjetum.fields.time_out') }}</label>
                <input class="form-control timepicker {{ $errors->has('time_out') ? 'is-invalid' : '' }}" type="text" name="time_out" id="time_out" value="{{ old('time_out') }}" required>
                @if($errors->has('time_out'))
                    <div class="invalid-feedback">
                        {{ $errors->first('time_out') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.time_out_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="room_no">{{ trans('cruds.grabacionTarjetum.fields.room_no') }}</label>
                <input class="form-control {{ $errors->has('room_no') ? 'is-invalid' : '' }}" type="number" name="room_no" id="room_no" value="{{ old('room_no', '') }}" step="1" required>
                @if($errors->has('room_no'))
                    <div class="invalid-feedback">
                        {{ $errors->first('room_no') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.room_no_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="room_no_2">{{ trans('cruds.grabacionTarjetum.fields.room_no_2') }}</label>
                <input class="form-control {{ $errors->has('room_no_2') ? 'is-invalid' : '' }}" type="number" name="room_no_2" id="room_no_2" value="{{ old('room_no_2', '') }}" step="1">
                @if($errors->has('room_no_2'))
                    <div class="invalid-feedback">
                        {{ $errors->first('room_no_2') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.room_no_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="room_no_3">{{ trans('cruds.grabacionTarjetum.fields.room_no_3') }}</label>
                <input class="form-control {{ $errors->has('room_no_3') ? 'is-invalid' : '' }}" type="number" name="room_no_3" id="room_no_3" value="{{ old('room_no_3', '') }}" step="1">
                @if($errors->has('room_no_3'))
                    <div class="invalid-feedback">
                        {{ $errors->first('room_no_3') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.room_no_3_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="safe_box">{{ trans('cruds.grabacionTarjetum.fields.safe_box') }}</label>
                <input class="form-control {{ $errors->has('safe_box') ? 'is-invalid' : '' }}" type="text" name="safe_box" id="safe_box" value="{{ old('safe_box', '0') }}" required>
                @if($errors->has('safe_box'))
                    <div class="invalid-feedback">
                        {{ $errors->first('safe_box') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.safe_box_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="common_doors">{{ trans('cruds.grabacionTarjetum.fields.common_doors') }}</label>
                <textarea class="form-control {{ $errors->has('common_doors') ? 'is-invalid' : '' }}" name="common_doors" id="common_doors">{{ old('common_doors') }}</textarea>
                @if($errors->has('common_doors'))
                    <div class="invalid-feedback">
                        {{ $errors->first('common_doors') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.common_doors_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="card_qty">{{ trans('cruds.grabacionTarjetum.fields.card_qty') }}</label>
                <input class="form-control {{ $errors->has('card_qty') ? 'is-invalid' : '' }}" type="number" name="card_qty" id="card_qty" value="{{ old('card_qty', '1') }}" step="1" required>
                @if($errors->has('card_qty'))
                    <div class="invalid-feedback">
                        {{ $errors->first('card_qty') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.card_qty_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="uid_card">{{ trans('cruds.grabacionTarjetum.fields.uid_card') }}</label>
                <input class="form-control {{ $errors->has('uid_card') ? 'is-invalid' : '' }}" type="text" name="uid_card" id="uid_card" value="{{ old('uid_card', '') }}" required>
                @if($errors->has('uid_card'))
                    <div class="invalid-feedback">
                        {{ $errors->first('uid_card') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.grabacionTarjetum.fields.uid_card_helper') }}</span>
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