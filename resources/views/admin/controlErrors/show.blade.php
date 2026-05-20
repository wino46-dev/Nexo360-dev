@extends('layouts.admin')
@section('content')

<div class="card">


    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.controlError.fields.origen') }}
                        </th>
                        <td>
                            {{ $controlError->origen }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.controlError.fields.tipo') }}
                        </th>
                        <td>
                            {{ $controlError->tipo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.controlError.fields.mensaje') }}
                        </th>
                        <td>
                            {{ $controlError->mensaje }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.controlError.fields.descripcion') }}
                        </th>
                        <td>
                            {{ $controlError->descripcion }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.controlError.fields.created_at') }}
                        </th>
                        <td>
                            {{ $controlError->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Usuario
                        </th>
                        <td>
                            {{ $controlError->user?->name }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.control-errors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
