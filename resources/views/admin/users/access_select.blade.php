@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Gestionar accesos de usuario (Sociedades y Hoteles)
    </div>
    <div class="card-body">
        <form method="GET" action="" onsubmit="event.preventDefault(); var uid=document.getElementById('user_id').value; if(uid){ window.location='{{ route('admin.users.access', ['user' => 'USER_ID']) }}'.replace('USER_ID', uid); }">
            <div class="form-group">
                <label for="user_id">Seleccionar usuario</label>
                <select class="form-control select2" id="user_id" required>
                    <option value="">-- Seleccione --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} (ID: {{ $u->id }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Gestionar</button>
        </form>
    </div>
</div>
@endsection
