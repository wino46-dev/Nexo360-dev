@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.list') }} {{ trans('cruds.controlSesion.title') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ControlSesion">
            <thead>
                <tr>

                    <th>
                        ID
                    </th>
                    <th>
                        {{ trans('cruds.controlSesion.fields.emisor') }}
                    </th>
                    <th>
                        {{ trans('cruds.controlSesion.fields.receptor') }}
                    </th>
                    <th>
                        {{ trans('cruds.controlSesion.fields.estado_sesion') }}
                    </th>
                    <th>
                        {{ trans('cruds.controlSesion.fields.created_at') }}
                    </th>
                    <th>
                        {{ trans('cruds.controlSesion.fields.updated_at') }}
                    </th>
                    <td>
                        &nbsp;
                    </td>

                </tr>
                <tr>

                    <td>
                        <input style="max-width: 100px" class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>

                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($users as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <th>

                    </th>

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
    ajax: "{{ route('admin.control-sesions.index') }}",
    columns: [
        { data: 'id', name: 'id' },
{ data: 'emisor_name', name: 'emisor.name' },
{ data: 'receptor_name', name: 'receptor.name' },
{ data: 'estado_sesion', name: 'estado_sesion' },
{ data: 'created_at', name: 'created_at' },
{ data: 'updated_at', name: 'updated_at' },
        { data: 'actions', name: '{{ trans('global.actions') }}' }

    ],
    orderCellsTop: true,
    order: [[ 4, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-ControlSesion').DataTable(dtOverrideGlobals);
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
