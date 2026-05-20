@extends('layouts.admin')
@section('content')

<div class="card">


    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ciudad.fields.id') }}
                        </th>
                        <td>
                            {{ $ciudad->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ciudad.fields.nombre') }}
                        </th>
                        <td>
                            {{ $ciudad->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ciudad.fields.provincia') }}
                        </th>
                        <td>
                            {{ $ciudad->provincia->nombre ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ciudad.fields.created_at') }}
                        </th>
                        <td>
                            {{ $ciudad->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ciudad.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $ciudad->updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.ciudads.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
