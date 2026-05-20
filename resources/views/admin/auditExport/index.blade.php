@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-file-excel"></i> Exportación de logs a Excel
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.audit-export.store') }}" id="audit-export-form">
            @csrf
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="date_start">Fecha inicio</label>
                    <input type="datetime-local" id="date_start" name="date_start" class="form-control" value="{{ old('date_start') }}" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="date_end">Fecha fin</label>
                    <input type="datetime-local" id="date_end" name="date_end" class="form-control" value="{{ old('date_end') }}" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="dataset">Modelo a exportar</label>
                    <select id="dataset" name="dataset" class="form-control" required>
                        @foreach($datasets as $key => $label)
                            <option value="{{ $key }}" @if(old('dataset')===$key) selected @endif>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="email">Email de destino</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $defaultEmail) }}" required>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success"><i class="fas fa-play"></i> Iniciar exportación</button>
            </div>
        </form>
        <small class="text-muted">La exportación se ejecutará en segundo plano y recibirás un correo con el archivo Excel (CSV) adjunto cuando finalice.</small>
    </div>
</div>
@endsection
