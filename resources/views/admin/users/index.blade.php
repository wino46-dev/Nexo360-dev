@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        
        <div class="row">
            <div class="col">
                {{ trans('global.list') }} {{ trans('cruds.user.title') }}
            </div>
            <div class="col text-right">
                @can('user_create')                    
                <a class="btn btn-success" href="{{ route('admin.users.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
                </a>                  
                @endcan
            </div>
        </div>
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-User">
            <thead>
                <tr>
                    <th>
                         {{ trans('cruds.user.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.email') }}
                    </th>                    
                    <th>
                        {{ trans('cruds.user.fields.roles') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.totem') }}
                    </th>
                    <th nowrap>
                        Sip Identity
                    </th>   
                    <th nowrap>
                        Login PMS
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
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>                    
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($roles as $key => $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </td>                    
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($totems as $key => $item)
                                <option value="{{ $item->id }}">{{ $item->codigo }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                    <td>
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
    ajax: "{{ route('admin.users.index') }}",
    columns: [

        { data: 'name', name: 'name' },
        { data: 'email', name: 'email' },        
        { data: 'roles', name: 'roles', orderable: false },
        { data: 'totem_id', name: 'totem_id', orderable: false },
        { data: 'sip_identity', name: 'sip_identity', orderable: false },
        { data: 'login_pms_establecimiento', name: 'login_pms_establecimiento', orderable: false },
        { data: 'actions', name: '{{ trans('global.actions') }}', className:'text-nowrap ' }
    ],
    orderCellsTop: true,
    order: [[ 0, 'asc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-User').DataTable(dtOverrideGlobals);
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
