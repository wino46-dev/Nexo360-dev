@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.session.title_singular') }}
    </div>

    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.session.fields.user') }}
                        </th>
                        <td>
                            {{ $session->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.session.fields.ip_address') }}
                        </th>
                        <td>
                            {{ $session->ip_address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.session.fields.user_agent') }}
                        </th>
                        <td>
                            {{ $session->user_agent }}
                        </td>
                    </tr>

                    <tr>
                        <th>
                            {{ trans('cruds.session.fields.last_activity') }}
                        </th>
                        <td>
                            {{ $session->last_activity }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.sessions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
