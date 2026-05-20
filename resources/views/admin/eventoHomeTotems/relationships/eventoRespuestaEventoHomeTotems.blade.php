
<div class="card">
    <div class="card-header">
        {{ trans('global.list') }} {{ trans('cruds.respuestaEventoHomeTotem.title_singular') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-eventoRespuestaEventoHomeTotems">
                <thead>
                    <tr>

                        <th>
                            {{ trans('cruds.respuestaEventoHomeTotem.fields.evento') }}
                        </th>
                        <th>
                            {{ trans('cruds.respuestaEventoHomeTotem.fields.respuesta') }}
                        </th>
                        <th>
                            {{ trans('cruds.respuestaEventoHomeTotem.fields.estado') }}
                        </th>
                        <th>
                            {{ trans('cruds.respuestaEventoHomeTotem.fields.created_at') }}
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($respuestaEventoHomeTotems as $key => $respuestaEventoHomeTotem)
                        <tr data-entry-id="{{ $respuestaEventoHomeTotem->id }}">

                            <td>
                                {{ $respuestaEventoHomeTotem->evento->objeto ?? '' }}
                            </td>
                            <td>
                                {{ $respuestaEventoHomeTotem->respuesta ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\RespuestaEventoHomeTotem::ESTADO_SELECT[$respuestaEventoHomeTotem->estado] ?? '' }}
                            </td>
                            <td>
                                {{ $respuestaEventoHomeTotem->created_at ?? '' }}
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


  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 3, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-eventoRespuestaEventoHomeTotems:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
