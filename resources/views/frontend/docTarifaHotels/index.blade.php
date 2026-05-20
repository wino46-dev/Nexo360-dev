@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('doc_tarifa_hotel_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.doc-tarifa-hotels.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.docTarifaHotel.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.docTarifaHotel.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-DocTarifaHotel">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docTarifaHotel.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docTarifaHotel.fields.establecimiento') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docTarifaHotel.fields.tarifa') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docTarifaHotel.fields.regimen') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docTarifaHotel.fields.habitacion') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docTarifaHotel.fields.importe') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docTarifaHotel.fields.fecha') }}
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
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <select class="search" strict="true">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach(App\Models\DocTarifaHotel::REGIMEN_SELECT as $key => $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($doc_habitacion_hotels as $key => $item)
                                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($docTarifaHotels as $key => $docTarifaHotel)
                                    <tr data-entry-id="{{ $docTarifaHotel->id }}">
                                        <td>
                                            {{ $docTarifaHotel->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docTarifaHotel->establecimiento->codigo ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docTarifaHotel->tarifa ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\DocTarifaHotel::REGIMEN_SELECT[$docTarifaHotel->regimen] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docTarifaHotel->habitacion->nombre ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docTarifaHotel->importe ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docTarifaHotel->fecha ?? '' }}
                                        </td>
                                        <td>
                                            @can('doc_tarifa_hotel_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.doc-tarifa-hotels.show', $docTarifaHotel->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('doc_tarifa_hotel_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.doc-tarifa-hotels.edit', $docTarifaHotel->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('doc_tarifa_hotel_delete')
                                                <form action="{{ route('frontend.doc-tarifa-hotels.destroy', $docTarifaHotel->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('doc_tarifa_hotel_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.doc-tarifa-hotels.massDestroy') }}",
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
    order: [[ 2, 'asc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-DocTarifaHotel:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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