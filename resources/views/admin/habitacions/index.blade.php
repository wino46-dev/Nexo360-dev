@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                {{ trans('global.list') }} {{ trans('cruds.habitacion.title') }}
            </div>
            <div class="col text-right">
                @can('habitacion_create')                    
                    <a class="btn btn-success" href="{{ route('admin.habitacions.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.habitacion.title_singular') }}
                    </a>
                    <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                        {{ trans('global.app_csvImport') }}
                    </button>
                    
                    
                @endcan
            </div>
        </div>
        
    </div>
    @include('csvImport.modal', ['model' => 'Habitacion', 'route' => 'admin.habitacions.parseCsvImport'])
    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Habitacion">
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
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>

                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($establecimientos as $key => $item)
                                <option value="{{ $item->codigo }}">{{ $item->codigo }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
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
    ajax: "{{ route('admin.habitacions.index') }}",
    columns: [

{ data: 'id', name: 'id' },
{ data: 'establecimiento_codigo', name: 'establecimiento.codigo' },
{ data: 'codigo', name: 'codigo' },
{ data: 'updated_at', name: 'updated_at' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 2, 'asc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-Habitacion').DataTable(dtOverrideGlobals);
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
