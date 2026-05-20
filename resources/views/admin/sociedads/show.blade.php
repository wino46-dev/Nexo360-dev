@extends('layouts.admin')
@section('content')

<div class="card">


    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.sociedad.fields.codigo') }}
                        </th>
                        <td>
                            {{ $sociedad->codigo }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sociedad.fields.nombre') }}
                        </th>
                        <td>
                            {{ $sociedad->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sociedad.fields.imagenes') }}
                        </th>
                        <td>
                            @foreach($sociedad->imagenes as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sociedad.fields.created_at') }}
                        </th>
                        <td>
                            {{ $sociedad->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sociedad.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $sociedad->updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            @includeIf('admin.sociedads.relationships.sociedadEstablecimientos', ['establecimientos' => $sociedad->sociedadEstablecimientos])

            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.sociedads.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
