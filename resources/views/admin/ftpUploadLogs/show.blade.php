@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-file-alt"></i> Detalle log FTP #{{ $ftpUploadLog->id }}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered table-striped">
                    <tr><th>ID</th><td>{{ $ftpUploadLog->id }}</td></tr>
                    <tr><th>Establecimiento</th><td>{{ optional($ftpUploadLog->establecimiento)->nombre }} (ID: {{ $ftpUploadLog->establecimiento_id }})</td></tr>
                    <tr><th>CheckIn</th><td>{{ $ftpUploadLog->check_in_id }}</td></tr>
                    <tr><th>Disco</th><td>{{ $ftpUploadLog->disk }}</td></tr>
                    <tr><th>Ruta local</th><td><code>{{ $ftpUploadLog->local_path }}</code></td></tr>
                    <tr><th>Ruta remota</th><td><code>{{ $ftpUploadLog->remote_path }}</code></td></tr>
                    <tr><th>Estado</th><td><span class="badge badge-{{ $ftpUploadLog->status === 'success' ? 'success' : ($ftpUploadLog->status === 'skip' ? 'secondary' : 'danger') }}">{{ strtoupper($ftpUploadLog->status) }}</span></td></tr>
                    <tr><th>Intentos</th><td>{{ $ftpUploadLog->attempts }}</td></tr>
                    <tr><th>Tamaño local</th><td>{{ $ftpUploadLog->size_local }}</td></tr>
                    <tr><th>Tamaño remoto</th><td>{{ $ftpUploadLog->size_remote }}</td></tr>
                    <tr><th>Modificación remota</th><td>{{ optional($ftpUploadLog->remote_last_modified)->format('Y-m-d H:i:s') }}</td></tr>
                    <tr><th>Subido en</th><td>{{ optional($ftpUploadLog->uploaded_at)->format('Y-m-d H:i:s') }}</td></tr>
                    <tr><th>Creado</th><td>{{ $ftpUploadLog->created_at }}</td></tr>
                    <tr><th>Actualizado</th><td>{{ $ftpUploadLog->updated_at }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Mensaje</h5>
                <pre class="p-2 bg-light" style="white-space: pre-wrap;">{{ $ftpUploadLog->message }}</pre>
                <h5>Meta</h5>
                <pre class="p-2 bg-light">{{ json_encode($ftpUploadLog->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>

        <a class="btn btn-secondary" href="{{ route('admin.ftp-upload-logs.index') }}">Volver al listado</a>
    </div>
</div>
@endsection
