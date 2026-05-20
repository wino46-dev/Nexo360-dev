@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.list') }} {{ trans('cruds.configuracionGrabador.title_singular') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ConfiguracionGrabador">
            <thead>
                <tr>

                    <th>
                        {{ trans('cruds.configuracionGrabador.fields.totem') }}
                    </th>
                    <th>
                        {{ trans('cruds.configuracionGrabador.fields.software_gestion') }}
                    </th>
                    <th>
                        {{ trans('cruds.configuracionGrabador.fields.reader_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.configuracionGrabador.fields.seq_mode') }}
                    </th>
                    <th>
                        {{ trans('cruds.configuracionGrabador.fields.show_message') }}
                    </th>
                    <th>
                        {{ trans('cruds.configuracionGrabador.fields.user_host') }}
                    </th>

                </tr>
                <tr>

                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\ConfiguracionGrabador::SOFTWARE_GESTION_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input style="max-width: 120px" class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input style="max-width: 120px" class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input style="max-width: 120px" class="search" type="text" placeholder="{{ trans('global.search') }}">
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
    ajax: "{{ route('admin.configuracion-grabadors.index') }}",
    columns: [

{ data: 'totem_codigo', name: 'totem.codigo' },
{ data: 'software_gestion', name: 'software_gestion' },
{ data: 'reader_no', name: 'reader_no' },
{ data: 'seq_mode', name: 'seq_mode' },
{ data: 'show_message', name: 'show_message' },
{ data: 'user_host', name: 'user_host' },

    ],
    orderCellsTop: true,
    order: [[ 0, 'asc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-ConfiguracionGrabador').DataTable(dtOverrideGlobals);
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
