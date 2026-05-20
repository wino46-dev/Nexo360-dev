@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.totem.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.totems.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.totem.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $totem->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.totem.fields.establecimiento') }}
                                    </th>
                                    <td>
                                        {{ $totem->establecimiento->codigo ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.totem.fields.codigo') }}
                                    </th>
                                    <td>
                                        {{ $totem->codigo }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.totem.fields.fuente_imagenes') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Totem::FUENTE_IMAGENES_SELECT[$totem->fuente_imagenes] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.totem.fields.imagenes') }}
                                    </th>
                                    <td>
                                        @foreach($totem->imagenes as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $media->getUrl('thumb') }}">
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.totem.fields.created_at') }}
                                    </th>
                                    <td>
                                        {{ $totem->created_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.totem.fields.comentarios') }}
                                    </th>
                                    <td>
                                        {{ $totem->comentarios }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.totem.fields.updated_at') }}
                                    </th>
                                    <td>
                                        {{ $totem->updated_at }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.totems.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection