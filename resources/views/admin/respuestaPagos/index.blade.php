@extends('layouts.admin')
@section('content')
@can('respuesta_pago_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.respuesta-pagos.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.respuestaPago.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('global.list') }} {{ trans('cruds.respuestaPago.title') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-RespuestaPago">
            <thead>
                <tr>

                    <th>
                        {{ trans('cruds.respuestaPago.fields.pago_origen') }}
                    </th>
                    <th>
                        {{ trans('cruds.respuestaPago.fields.tipo_pago') }}
                    </th>
                    <th>
                        {{ trans('cruds.respuestaPago.fields.importe') }}
                    </th>
                    <th>
                        {{ trans('cruds.respuestaPago.fields.moneda') }}
                    </th>
                    <th>
                        {{ trans('cruds.respuestaPago.fields.fecha_operacion') }}
                    </th>

                    <th>
                        {{ trans('cruds.respuestaPago.fields.resultado') }}
                    </th>
                    <th>
                        {{ trans('cruds.respuestaPago.fields.created_at') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>

                    <td>
                        <input  style="max-width: 140px;" class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input  style="max-width: 120px;" class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input style="max-width: 100px;"  class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input style="max-width: 100px;"  class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input style="max-width: 120px;"  class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input style="max-width: 100px;"  class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input style="max-width: 100px;"  class="search" type="text" placeholder="{{ trans('global.search') }}">
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
@can('respuesta_pago_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.respuesta-pagos.massDestroy') }}",
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
    ajax: "{{ route('admin.respuesta-pagos.index') }}",
    columns: [
{ data: 'pago_origen_factura', name: 'pago_origen.factura' },
{ data: 'tipo_pago', name: 'tipo_pago' },
{ data: 'importe', name: 'importe' },
{ data: 'moneda', name: 'moneda' },
{ data: 'fecha_operacion', name: 'fecha_operacion' },
{ data: 'resultado', name: 'resultado' },
        { data: 'created_at', name: 'created_at' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 6, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-RespuestaPago').DataTable(dtOverrideGlobals);
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
