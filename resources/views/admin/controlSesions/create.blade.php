@extends('layouts.admin')
<style>
    .select2-results__option {
        color: black;
    }

</style>
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.controlSesion.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.control-sesions.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group" style="display: none">
                <label class="required" for="emisor_id">{{ trans('cruds.controlSesion.fields.emisor') }}</label>
                <select class="form-control select2 {{ $errors->has('emisor') ? 'is-invalid' : '' }}" name="emisor_id" id="emisor_id" required>
                    <option value="{{ $emisors }}">{{ $emisors }}</option>
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
                <select class="form-control select2 {{ $errors->has('receptor') ? 'is-invalid' : '' }}" name="receptor_id" id="receptor_id" required>
                    @foreach($receptors as $id => $entry)
                        @php
                            $ocupado = in_array($id,$id_sesions) ? collect($active_sesions)->firstWhere('receptor_id',$id) : null;
                        @endphp
                        <option value="{{ $id }}" {{ old('receptor_id') == $id ? 'selected' : '' }}>
                            &nbsp;&nbsp;&nbsp;{{ $entry }}@if($ocupado) — {{ $ocupado->emisor->name }} conectado desde {{ $ocupado->created_at }} @endif
                        </option>
                    @endforeach
                </select>
                @if($errors->has('receptor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('receptor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.controlSesion.fields.receptor_helper') }}</span>
            </div>
            <div class="form-group" style="display: none">
                <div class="form-check {{ $errors->has('estado_sesion') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="estado_sesion" value="1">
                    <input class="form-check-input" type="checkbox" name="estado_sesion" id="estado_sesion" value="1" {{ old('estado_sesion', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="estado_sesion">{{ trans('cruds.controlSesion.fields.estado_sesion') }}</label>
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



@endsection
