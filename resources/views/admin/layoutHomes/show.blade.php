@extends('layouts.admin')
@section('content')

<div class="card">


    <div class="card-body">
        <div class="form-group">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.layoutHome.fields.tipo') }}
                        </th>
                        <td>
                            {{ App\Models\LayoutHome::TIPO_SELECT[$layoutHome->tipo] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.layoutHome.fields.imagen_1') }}
                        </th>
                        <td>
                            @if($layoutHome->imagen_1)
                                <a href="{{ $layoutHome->imagen_1->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $layoutHome->imagen_1->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.layoutHome.fields.imagen_2') }}
                        </th>
                        <td>
                            @if($layoutHome->imagen_2)
                                <a href="{{ $layoutHome->imagen_2->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $layoutHome->imagen_2->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.layoutHome.fields.imagen_3') }}
                        </th>
                        <td>
                            @if($layoutHome->imagen_3)
                                <a href="{{ $layoutHome->imagen_3->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $layoutHome->imagen_3->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.layoutHome.fields.imagen_4') }}
                        </th>
                        <td>
                            @if($layoutHome->imagen_4)
                                <a href="{{ $layoutHome->imagen_4->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $layoutHome->imagen_4->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.layoutHome.fields.imagen_5') }}
                        </th>
                        <td>
                            @if($layoutHome->imagen_5)
                                <a href="{{ $layoutHome->imagen_5->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $layoutHome->imagen_5->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.layoutHome.fields.created_at') }}
                        </th>
                        <td>
                            {{ $layoutHome->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.layoutHome.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $layoutHome->updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-warning" href="{{ route('admin.layout-homes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
