
<div class="card">
    <div class="card-header">
        {{ trans('global.list') }} {{ trans('cruds.habitacion.title') }} relacionados
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-establecimientoHabitacions">
                <thead>
                    <tr>

                        <th>
                            {{ trans('cruds.habitacion.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.habitacion.fields.establecimiento') }}
                        </th>
                        <th>
                            {{ trans('cruds.habitacion.fields.codigo') }}
                        </th>
                        <th>
                            {{ trans('cruds.habitacion.fields.updated_at') }}
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($habitacions as $key => $habitacion)
                        <tr data-entry-id="{{ $habitacion->id }}">

                            <td>
                                {{ $habitacion->id ?? '' }}
                            </td>
                            <td>
                                {{ $habitacion->establecimiento->codigo ?? '' }}
                            </td>
                            <td>
                                {{ $habitacion->codigo ?? '' }}
                            </td>
                            <td>
                                {{ $habitacion->updated_at ?? '' }}
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
    order: [[ 2, 'asc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-establecimientoHabitacions:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
