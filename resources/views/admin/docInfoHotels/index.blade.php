@extends('layouts.admin')
@section('content')
@can('doc_info_hotel_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.doc-info-hotels.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.docInfoHotel.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.docInfoHotel.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-DocInfoHotel">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.hotel') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.categoria') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.pais') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.provincia') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.ciudad') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.direccion') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.codigo_postal') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.latitud') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.longitud') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.telefono') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.emergencias') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.email') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.web') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.enlace_fotos') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.cuenta_bancaria') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.modos_cobro') }}
                    </th>
                    <th>
                        {{ trans('cruds.docInfoHotel.fields.pet_friendly') }}
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
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\DocInfoHotel::CATEGORIA_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($pais as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($provincia as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($ciudads as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
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
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
                            @foreach(App\Models\DocInfoHotel::PET_FRIENDLY_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
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
@can('doc_info_hotel_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.doc-info-hotels.massDestroy') }}",
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
    ajax: "{{ route('admin.doc-info-hotels.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'hotel_codigo', name: 'hotel.codigo' },
{ data: 'categoria', name: 'categoria' },
{ data: 'pais_nombre', name: 'pais.nombre' },
{ data: 'provincia_nombre', name: 'provincia.nombre' },
{ data: 'ciudad_nombre', name: 'ciudad.nombre' },
{ data: 'direccion', name: 'direccion' },
{ data: 'codigo_postal', name: 'codigo_postal' },
{ data: 'latitud', name: 'latitud' },
{ data: 'longitud', name: 'longitud' },
{ data: 'telefono', name: 'telefono' },
{ data: 'emergencias', name: 'emergencias' },
{ data: 'email', name: 'email' },
{ data: 'web', name: 'web' },
{ data: 'enlace_fotos', name: 'enlace_fotos' },
{ data: 'cuenta_bancaria', name: 'cuenta_bancaria' },
{ data: 'modos_cobro', name: 'modos_cobro' },
{ data: 'pet_friendly', name: 'pet_friendly' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-DocInfoHotel').DataTable(dtOverrideGlobals);
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
