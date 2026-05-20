

<div class="card">


    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-sesionFirmaCheckIns">
                <thead>
                    <tr>

                        <th>
                            {{ trans('cruds.firmaCheckIn.fields.sesion') }}
                        </th>
                        <th>
                            {{ trans('cruds.firmaCheckIn.fields.documento') }}
                        </th>
                        <th>
                            {{ trans('cruds.firmaCheckIn.fields.created_at') }}
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($firmaCheckIns as $key => $firmaCheckIn)
                        <tr data-entry-id="{{ $firmaCheckIn->id }}">

                            <td>
                                {{ $firmaCheckIn->sesion->estado_sesion ?? '' }}
                            </td>
                            <td>
                                @if($firmaCheckIn->documento)
                                    <a href="{{ $firmaCheckIn->documento->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $firmaCheckIn->created_at ?? '' }}
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
@can('firma_check_in_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.firma-check-ins.massDestroy') }}",
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
    order: [[ 2, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-sesionFirmaCheckIns:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
