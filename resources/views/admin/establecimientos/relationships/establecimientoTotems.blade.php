

<div class="card">
    <div class="card-header">
        {{ trans('global.list') }} {{ trans('cruds.totem.title') }} relacionadas
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-establecimientoTotems">
                <thead>
                    <tr>

                        <th>
                            {{ trans('cruds.totem.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.totem.fields.establecimiento') }}
                        </th>
                        <th>
                            {{ trans('cruds.totem.fields.codigo') }}
                        </th>
                        <th>
                            {{ trans('cruds.totem.fields.fuente_imagenes') }}
                        </th>
                        <th>
                            {{ trans('cruds.totem.fields.updated_at') }}
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($totems as $key => $totem)
                        <tr data-entry-id="{{ $totem->id }}">

                            <td>
                                {{ $totem->id ?? '' }}
                            </td>
                            <td>
                                {{ $totem->establecimiento->codigo ?? '' }}
                            </td>
                            <td>
                                {{ $totem->codigo ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Totem::FUENTE_IMAGENES_SELECT[$totem->fuente_imagenes] ?? '' }}
                            </td>
                            <td>
                                {{ $totem->updated_at ?? '' }}
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
  let table = $('.datatable-establecimientoTotems:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
