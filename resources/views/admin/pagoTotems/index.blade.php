@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.list') }} {{ trans('cruds.pagoTotem.title') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-PagoTotem">
            <thead>
                <tr>

                    <th>
                        {{ trans('cruds.pagoTotem.fields.emisor') }}
                    </th>
                    <th>
                        {{ trans('cruds.pagoTotem.fields.receptor') }}
                    </th>
                    <th>
                        {{ trans('cruds.pagoTotem.fields.sesion') }}
                    </th>
                    <th>
                        {{ trans('cruds.pagoTotem.fields.importe') }}
                    </th>
                    <th>
                        {{ trans('cruds.pagoTotem.fields.factura') }}
                    </th>
                    <th>
                        {{ trans('cruds.pagoTotem.fields.tipo_operacion') }}
                    </th>
                    <th>
                        Origen
                    </th>
                    <th>
                        {{ trans('cruds.pagoTotem.fields.created_at') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>

                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($users as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input style="max-width: 140px" class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input style="max-width: 140px" class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input style="max-width: 90px" class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input style="max-width: 90px" class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input style="max-width: 140px" class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\PagoTotem::ORIGEN_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
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
@can('pago_totem_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.pago-totems.massDestroy') }}",
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
    ajax: "{{ route('admin.pago-totems.index') }}",
    columns: [

{ data: 'emisor_name', name: 'emisor.name' },
{ data: 'receptor_name', name: 'receptor.name' },
{ data: 'sesion_id', name: 'sesion.id' },
{ data: 'importe', name: 'importe' },
{ data: 'factura', name: 'factura' },
{ data: 'tipo_operacion', name: 'tipo_operacion' },
{ data: 'origen', name: 'origen' },
{ data: 'created_at', name: 'created_at' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 7, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-PagoTotem').DataTable(dtOverrideGlobals);
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
