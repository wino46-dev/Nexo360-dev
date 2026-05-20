@can('reserva_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.reservas.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.reserva.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.reserva.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-clienteReservas">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.reserva.fields.establecimiento') }}
                        </th>
                        <th>
                            {{ trans('cruds.reserva.fields.codigo') }}
                        </th>
                        <th>
                            {{ trans('cruds.reserva.fields.cliente') }}
                        </th>
                        <th>
                            {{ trans('cruds.cliente.fields.apellidos') }}
                        </th>
                        <th>
                            {{ trans('cruds.reserva.fields.entrada') }}
                        </th>
                        <th>
                            {{ trans('cruds.reserva.fields.salida') }}
                        </th>
                        <th>
                            {{ trans('cruds.reserva.fields.verificado') }}
                        </th>
                        <th>
                            {{ trans('cruds.reserva.fields.estado_pago') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservas as $key => $reserva)
                        <tr data-entry-id="{{ $reserva->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $reserva->establecimiento->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $reserva->codigo ?? '' }}
                            </td>
                            <td>
                                {{ $reserva->cliente->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $reserva->cliente->apellidos ?? '' }}
                            </td>
                            <td>
                                {{ $reserva->entrada ?? '' }}
                            </td>
                            <td>
                                {{ $reserva->salida ?? '' }}
                            </td>
                            <td>
                                <span style="display:none">{{ $reserva->verificado ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $reserva->verificado ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $reserva->estado_pago ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $reserva->estado_pago ? 'checked' : '' }}>
                            </td>
                            <td>
                                @can('reserva_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.reservas.show', $reserva->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('reserva_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.reservas.edit', $reserva->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('reserva_delete')
                                    <form action="{{ route('admin.reservas.destroy', $reserva->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('reserva_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.reservas.massDestroy') }}",
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
  let table = $('.datatable-clienteReservas:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection