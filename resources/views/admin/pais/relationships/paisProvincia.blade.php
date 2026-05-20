@can('provincium_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.provincia.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.provincium.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.provincium.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-paisProvincia">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.provincium.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.provincium.fields.nombre') }}
                        </th>
                        <th>
                            {{ trans('cruds.provincium.fields.pais') }}
                        </th>
                        <th>
                            {{ trans('cruds.provincium.fields.iso') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($provincia as $key => $provincium)
                        <tr data-entry-id="{{ $provincium->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $provincium->id ?? '' }}
                            </td>
                            <td>
                                {{ $provincium->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $provincium->pais->nombre ?? '' }}
                            </td>
                            <td>
                                {{ $provincium->iso ?? '' }}
                            </td>
                            <td>
                                @can('provincium_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.provincia.show', $provincium->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('provincium_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.provincia.edit', $provincium->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('provincium_delete')
                                    <form action="{{ route('admin.provincia.destroy', $provincium->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('provincium_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.provincia.massDestroy') }}",
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
    order: [[ 2, 'asc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-paisProvincia:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection