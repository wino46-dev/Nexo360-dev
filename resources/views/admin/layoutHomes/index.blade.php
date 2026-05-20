@extends('layouts.admin')
@section('content')
@can('layout_home_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.layout-homes.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.layoutHome.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('global.list') }} {{ trans('cruds.layoutHome.title') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-LayoutHome">
            <thead>
                <tr>

                    <th>
                        {{ trans('cruds.layoutHome.fields.tipo') }}
                    </th>
                    <th>
                        {{ trans('cruds.layoutHome.fields.imagen_1') }}
                    </th>
                    <th>
                        {{ trans('cruds.layoutHome.fields.imagen_2') }}
                    </th>
                    <th>
                        {{ trans('cruds.layoutHome.fields.updated_at') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>

                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\LayoutHome::TIPO_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                    <td>
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
    ajax: "{{ route('admin.layout-homes.index') }}",
    columns: [

{ data: 'tipo', name: 'tipo' },
{ data: 'imagen_1', name: 'imagen_1', sortable: false, searchable: false },
{ data: 'imagen_2', name: 'imagen_2', sortable: false, searchable: false },
{ data: 'updated_at', name: 'updated_at' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 3, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-LayoutHome').DataTable(dtOverrideGlobals);
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
