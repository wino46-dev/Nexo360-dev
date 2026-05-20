

<div class="card">


    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-sesionPagoTotems">
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
                            {{ trans('cruds.pagoTotem.fields.created_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagoTotems as $key => $pagoTotem)
                        <tr data-entry-id="{{ $pagoTotem->id }}">

                            <td>
                                {{ $pagoTotem->emisor->name ?? '' }}
                            </td>
                            <td>
                                {{ $pagoTotem->receptor->name ?? '' }}
                            </td>
                            <td>
                                {{ $pagoTotem->sesion->estado_sesion ?? '' }}
                            </td>
                            <td>
                                {{ $pagoTotem->importe ?? '' }}
                            </td>
                            <td>
                                {{ $pagoTotem->factura ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\PagoTotem::TIPO_OPERACION_SELECT[$pagoTotem->tipo_operacion] ?? '' }}
                            </td>
                            <td>
                                {{ $pagoTotem->created_at ?? '' }}
                            </td>
                            <td>
                                @can('pago_totem_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.pago-totems.show', $pagoTotem->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('pago_totem_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.pago-totems.massDestroy') }}",
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
    order: [[ 6, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-sesionPagoTotems:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
