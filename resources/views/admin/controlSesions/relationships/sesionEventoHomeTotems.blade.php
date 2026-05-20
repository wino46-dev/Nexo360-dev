

<div class="card">


    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-sesionEventoHomeTotems">
                <thead>
                    <tr>

                        <th>
                            {{ trans('cruds.eventoHomeTotem.fields.emisor') }}
                        </th>
                        <th>
                            {{ trans('cruds.eventoHomeTotem.fields.receptor') }}
                        </th>

                        <th>
                            {{ trans('cruds.eventoHomeTotem.fields.tipo_evento') }}
                        </th>
                        <th>
                            {{ trans('cruds.eventoHomeTotem.fields.objeto') }}
                        </th>
                        <th>
                            {{ trans('cruds.eventoHomeTotem.fields.canal_transmision') }}
                        </th>
                        <th>
                            {{ trans('cruds.eventoHomeTotem.fields.created_at') }}
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($eventoHomeTotems as $key => $eventoHomeTotem)
                        <tr data-entry-id="{{ $eventoHomeTotem->id }}">

                            <td>
                                {{ $eventoHomeTotem->emisor->name ?? '' }}
                            </td>
                            <td>
                                {{ $eventoHomeTotem->receptor->name ?? '' }}
                            </td>

                            <td>
                                {{ $eventoHomeTotem->tipo_evento->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $eventoHomeTotem->objeto ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\EventoHomeTotem::CANAL_TRANSMISION_SELECT[$eventoHomeTotem->canal_transmision] ?? '' }}
                            </td>
                            <td>
                                {{ $eventoHomeTotem->created_at ?? '' }}
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
@can('evento_home_totem_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.evento-home-totems.massDestroy') }}",
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
    order: [[ 5, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-sesionEventoHomeTotems:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
