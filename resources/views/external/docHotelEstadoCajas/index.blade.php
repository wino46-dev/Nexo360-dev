@extends('layouts.external')
@section('content')
@can('doc_hotel_estado_caja_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('external.doc-hotel-estado-cajas.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.docHotelEstadoCaja.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'DocHotelEstadoCaja', 'route' => 'external.doc-hotel-estado-cajas.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.docHotelEstadoCaja.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-DocHotelEstadoCaja">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.docHotelEstadoCaja.fields.establecimiento') }}
                    </th>
                    <th>
                        {{ trans('cruds.docHotelEstadoCaja.fields.caja') }}
                    </th>
                    <th>
                        {{ trans('cruds.docHotelEstadoCaja.fields.code') }}
                    </th>
                    <th>
                        {{ trans('cruds.docHotelEstadoCaja.fields.room') }}
                    </th>
                    <th>
                        {{ trans('cruds.docHotelEstadoCaja.fields.room_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.docHotelEstadoCaja.fields.cliente') }}
                    </th>
                    <th>
                        {{ trans('cruds.docHotelEstadoCaja.fields.documento_cliente') }}
                    </th>
                    <th>
                        {{ trans('cruds.docHotelEstadoCaja.fields.pay') }}
                    </th>
                    <th>
                        {{ trans('cruds.docHotelEstadoCaja.fields.fecha') }}
                    </th>
                    <th>
                        {{ trans('cruds.docHotelEstadoCaja.fields.updated_at') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
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
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($habitacions as $key => $item)
                                <option value="{{ $item->codigo }}">{{ $item->codigo }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\DocHotelEstadoCaja::ROOM_STATUS_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\DocHotelEstadoCaja::PAY_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('doc_hotel_estado_caja_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('external.doc-hotel-estado-cajas.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
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

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('external.doc-hotel-estado-cajas.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'establecimiento_codigo', name: 'establecimiento.codigo' },
{ data: 'caja', name: 'caja' },
{ data: 'code', name: 'code' },
{ data: 'room_codigo', name: 'room.codigo' },
{ data: 'room_status', name: 'room_status' },
{ data: 'cliente', name: 'cliente' },
{ data: 'documento_cliente', name: 'documento_cliente' },
{ data: 'pay', name: 'pay' },
{ data: 'fecha', name: 'fecha' },
{ data: 'updated_at', name: 'updated_at' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 2, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-DocHotelEstadoCaja').DataTable(dtOverrideGlobals);
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
});

</script>
@endsection
