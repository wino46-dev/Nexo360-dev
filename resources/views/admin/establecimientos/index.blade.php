@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    {{ trans('global.list') }} {{ trans('cruds.establecimiento.title') }}
                </div>
                <div class="col text-right">
                    @can('establecimiento_create')
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', [
                            'model' => 'Establecimiento',
                            'route' => 'admin.establecimientos.parseCsvImport',
                        ])
                        <a class="btn btn-success" href="{{ route('admin.establecimientos.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.establecimiento.title_singular') }}
                        </a>
                    @endcan
                </div>

            </div>
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Establecimiento">
                <thead>
                    <tr>

                        <th>
                            {{ trans('cruds.establecimiento.fields.sociedad') }}
                        </th>
                        <th>
                            {{ trans('cruds.establecimiento.fields.codigo') }}
                        </th>
                        <th>
                            {{ trans('cruds.establecimiento.fields.nombre') }}
                        </th>
                        <th>
                            Api pms
                        </th>
                        <th>
                            Remote Id
                        </th>
                        <th>
                            {{ trans('cruds.establecimiento.fields.updated_at') }}
                        </th>
                        <th>
                            -
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>

                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach ($sociedads as $key => $item)
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
                        <td>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @include('admin.establecimientos._partes_modal')
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.establecimientos.index') }}",
                columns: [

                    {
                        data: 'sociedad_codigo',
                        name: 'sociedad.codigo'
                    },
                    {
                        data: 'codigo',
                        name: 'codigo'
                    },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },
                    {
                        data: 'api_pms',
                        name: 'api_pms',
                        className: 'text-center'
                    },
                    {
                        data: 'remote_hotel_id',
                        name: 'remote_hotel_id',
                        className: 'text-center'
                    },
                    {
                        data: 'updated_at',
                        className: 'text-nowrap text-center',
                        name: 'updated_at'
                    },
                    {
                        data: 'actions2',
                        className: 'text-nowrap text-center',
                        name: '{{ trans('global.actions') }}'
                    },
                    {
                        data: 'actions',
                        className: 'text-nowrap text-center',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'asc']
                ],
                pageLength: 25,
            };
            let table = $('.datatable-Establecimiento').DataTable(dtOverrideGlobals);
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
