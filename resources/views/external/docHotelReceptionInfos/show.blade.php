@extends('layouts.external')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.docHotelReceptionInfo.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('external.doc-hotel-reception-infos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelReceptionInfo.fields.id') }}
                        </th>
                        <td>
                            {{ $docHotelReceptionInfo->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelReceptionInfo.fields.establecimiento') }}
                        </th>
                        <td>
                            {{ $docHotelReceptionInfo->establecimiento->codigo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelReceptionInfo.fields.hour_open') }}
                        </th>
                        <td>
                            {{ $docHotelReceptionInfo->hour_open }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelReceptionInfo.fields.hour_close') }}
                        </th>
                        <td>
                            {{ $docHotelReceptionInfo->hour_close }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelReceptionInfo.fields.open_holiday') }}
                        </th>
                        <td>
                            {{ $docHotelReceptionInfo->open_holiday }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelReceptionInfo.fields.close_holiday') }}
                        </th>
                        <td>
                            {{ $docHotelReceptionInfo->close_holiday }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelReceptionInfo.fields.acces_type_after_hour') }}
                        </th>
                        <td>
                            {{ App\Models\DocHotelReceptionInfo::ACCES_TYPE_AFTER_HOUR_SELECT[$docHotelReceptionInfo->acces_type_after_hour] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelReceptionInfo.fields.box_locate') }}
                        </th>
                        <td>
                            {!! $docHotelReceptionInfo->box_locate !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelReceptionInfo.fields.box_photo') }}
                        </th>
                        <td>
                            @foreach($docHotelReceptionInfo->box_photo as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.docHotelReceptionInfo.fields.acces_videoportero') }}
                        </th>
                        <td>
                            {!! $docHotelReceptionInfo->acces_videoportero !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('external.doc-hotel-reception-infos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
