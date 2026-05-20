@extends('layouts.admin')
@section('content')

<div class="card">


    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.permission.fields.title') }}
                        </th>
                        <td>
                            {{ $permission->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.permission.fields.created_at') }}
                        </th>
                        <td>
                            {{ $permission->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.permission.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $permission->updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.permissions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
