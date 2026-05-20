@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.list') }} {{ trans('cruds.controlError.title') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ControlError">
                <thead>
                    <tr>
                        <th>
                            Establecimiento
                        </th>
                        <th>
                            Usuario
                        </th>
                        <th>
                            {{ trans('cruds.controlError.fields.origen') }}
                        </th>
                        <th>
                            {{ trans('cruds.controlError.fields.tipo') }}
                        </th>
                        <th>
                            {{ trans('cruds.controlError.fields.mensaje') }}
                        </th>
                        <th>
                            {{ trans('cruds.controlError.fields.descripcion') }}
                        </th>
                        
                        <th>
                            {{ trans('cruds.controlError.fields.created_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>                            
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($establecimientos as $key => $item)
                                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
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
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            var tableHeight = window.innerHeight - 420;

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                scrollCollapse: true,
                scrollY: tableHeight + "px",
                aaSorting: [],
                ajax: "{{ route('admin.control-errors.index') }}",
                columns: [
                    {
                        data: 'establecimiento_id',
                        name: 'establecimiento_id'
                    },
                    {
                        data: 'user_id',
                        name: 'user_id'
                    },
                    {
                        data: 'origen',
                        name: 'origen'
                    },
                    {
                        data: 'tipo',
                        name: 'tipo'
                    },
                    {
                        data: 'mensaje',
                        name: 'mensaje'
                    },
                    {
                        data: 'descripcion',
                        name: 'descripcion',                        
                    },
                   
                    {
                        data: 'created_at',
                        name: 'created_at',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [6, 'desc']
                ],
                pageLength: 25,
            };
            let table = $('.datatable-ControlError').DataTable(dtOverrideGlobals);
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
