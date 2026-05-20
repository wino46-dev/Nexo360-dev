@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.list') }} {{ trans('cruds.configuracionVideo.title') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ConfiguracionVideo">
            <thead>
                <tr>

                    <th>
                        {{ trans('cruds.configuracionVideo.fields.totem') }}
                    </th>
                    <th>
                        {{ trans('cruds.configuracionVideo.fields.sip_identity') }}
                    </th>
                    <th>
                        {{ trans('cruds.configuracionVideo.fields.display_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.configuracionVideo.fields.sip_registar') }}
                    </th>
                    <th>
                        {{ trans('cruds.configuracionVideo.fields.sip_identity_destino') }}
                    </th>

                </tr>
                <tr>

                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>

                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.configuracion-videos.index') }}",
    columns: [
{ data: 'totem_codigo', name: 'totem.codigo' },
{ data: 'sip_identity', name: 'sip_identity' },
{ data: 'display_name', name: 'display_name' },
{ data: 'sip_registar', name: 'sip_registar' },
{ data: 'sip_identity_destino', name: 'sip_identity_destino' },

    ],
    orderCellsTop: true,
    order: [[ 0, 'asc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-ConfiguracionVideo').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
});

</script>
@endsection
