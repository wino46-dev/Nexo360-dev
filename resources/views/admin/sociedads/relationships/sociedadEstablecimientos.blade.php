

<div class="card">
    <div class="card-header">
        {{ trans('global.list') }} {{ trans('cruds.establecimiento.title') }} relacionados
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-sociedadEstablecimientos">
                <thead>
                    <tr>

                        <th>
                            {{ trans('cruds.establecimiento.fields.sociedad') }}
                        </th>
                        <th>
                            {{ trans('cruds.establecimiento.fields.codigo') }}
                        </th>
                        <th>
                            {{ trans('cruds.establecimiento.fields.nombre') }}
                        </th>
                        <th>
                            {{ trans('cruds.establecimiento.fields.updated_at') }}
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($establecimientos as $key => $establecimiento)
                        <tr data-entry-id="{{ $establecimiento->id }}">

                            <td>
                                {{ $establecimiento->sociedad->codigo ?? '' }}
                            </td>
                            <td>
                                {{ $establecimiento->codigo ?? '' }}
                            </td>
                            <td>
                                {{ $establecimiento->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $establecimiento->updated_at ?? '' }}
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
    order: [[ 1, 'asc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-sociedadEstablecimientos:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
