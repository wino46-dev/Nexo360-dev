@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('doc_hotel_reception_info_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.doc-hotel-reception-infos.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.docHotelReceptionInfo.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.docHotelReceptionInfo.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-DocHotelReceptionInfo">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docHotelReceptionInfo.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docHotelReceptionInfo.fields.establecimiento') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docHotelReceptionInfo.fields.hour_open') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docHotelReceptionInfo.fields.hour_close') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docHotelReceptionInfo.fields.open_holiday') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docHotelReceptionInfo.fields.close_holiday') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docHotelReceptionInfo.fields.acces_type_after_hour') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docHotelReceptionInfo.fields.box_photo') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($establecimientos as $key => $item)
                                                <option value="{{ $item->codigo }}">{{ $item->codigo }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <select class="search" strict="true">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach(App\Models\DocHotelReceptionInfo::ACCES_TYPE_AFTER_HOUR_SELECT as $key => $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($docHotelReceptionInfos as $key => $docHotelReceptionInfo)
                                    <tr data-entry-id="{{ $docHotelReceptionInfo->id }}">
                                        <td>
                                            {{ $docHotelReceptionInfo->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docHotelReceptionInfo->establecimiento->codigo ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docHotelReceptionInfo->hour_open ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docHotelReceptionInfo->hour_close ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docHotelReceptionInfo->open_holiday ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docHotelReceptionInfo->close_holiday ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\DocHotelReceptionInfo::ACCES_TYPE_AFTER_HOUR_SELECT[$docHotelReceptionInfo->acces_type_after_hour] ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($docHotelReceptionInfo->box_photo as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                                    <img src="{{ $media->getUrl('thumb') }}">
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @can('doc_hotel_reception_info_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.doc-hotel-reception-infos.show', $docHotelReceptionInfo->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('doc_hotel_reception_info_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.doc-hotel-reception-infos.edit', $docHotelReceptionInfo->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('doc_hotel_reception_info_delete')
                                                <form action="{{ route('frontend.doc-hotel-reception-infos.destroy', $docHotelReceptionInfo->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('doc_hotel_reception_info_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.doc-hotel-reception-infos.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-DocHotelReceptionInfo:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
})

</script>
@endsection