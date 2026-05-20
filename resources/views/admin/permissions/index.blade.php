@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">

            <div class="row">
                <div class="col">
                    {{ trans('global.list') }} {{ trans('cruds.permission.title') }}
                </div>
                <div class="col text-right">
                    <a class="btn btn-primary text-white" onclick="sync()">
                        Sincronizar Permisos
                    </a>
                    @can('permission_create')
                        <a class="btn btn-success" href="{{ route('admin.permissions.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.permission.title_singular') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Permission">
                <thead>
                    <tr>

                        <th>
                            {{ trans('cruds.permission.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.permission.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.permission.fields.created_at') }}
                        </th>
                        <th>
                            {{ trans('cruds.permission.fields.updated_at') }}
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
        function sync() {
            $.ajax({
                url: "{{ url('admin/permissions/sync') }}",
                method: 'POST',
                cache: false,
                success: function(response) {
                    console.log(response)
                    Toast.fire({
                        icon: "success",
                        title: response.message
                    });
                },
                error: function(response) {

                },
            });
        }
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.permissions.index') }}",
                columns: [

                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [0, 'desc']
                ],
                pageLength: 25,
            };
            let table = $('.datatable-Permission').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            let visibleColumnsIndexes = null;
            $('.datatable thead').on('input', '.search', function() {
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
