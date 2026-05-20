

<div class="card">


    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-sesionGrabacionTarjeta">
                <thead>
                    <tr>

                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.emisor') }}
                        </th>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.receptor') }}
                        </th>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.sesion') }}
                        </th>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.date_in') }}
                        </th>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.date_out') }}
                        </th>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.room_no') }}
                        </th>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.uid_card') }}
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($grabacionTarjeta as $key => $grabacionTarjetum)
                        <tr data-entry-id="{{ $grabacionTarjetum->id }}">

                            <td>
                                {{ $grabacionTarjetum->emisor->name ?? '' }}
                            </td>
                            <td>
                                {{ $grabacionTarjetum->receptor->name ?? '' }}
                            </td>
                            <td>
                                {{ $grabacionTarjetum->sesion->estado_sesion ?? '' }}
                            </td>
                            <td>
                                {{ $grabacionTarjetum->date_in ?? '' }}
                            </td>
                            <td>
                                {{ $grabacionTarjetum->date_out ?? '' }}
                            </td>
                            <td>
                                {{ $grabacionTarjetum->room_no ?? '' }}
                            </td>
                            <td>
                                {{ $grabacionTarjetum->uid_card ?? '' }}
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
@can('grabacion_tarjetum_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.grabacion-tarjeta.massDestroy') }}",
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
    order: [[ 3, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-sesionGrabacionTarjeta:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
