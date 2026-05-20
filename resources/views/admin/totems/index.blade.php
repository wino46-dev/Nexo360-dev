@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    {{ trans('global.list') }} {{ trans('cruds.totem.title') }}
                </div>
                <div class="col text-right">
                    @can('totem_create')
                        <a class="btn btn-success" href="{{ route('admin.totems.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.totem.title_singular') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card-body">

            <table id="datatable-Totem" class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Totem">
                <thead>
                    <tr>
                        <th>

                        </th>
                        <th>
                            {{ trans('cruds.totem.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.totem.fields.establecimiento') }}
                        </th>
                        <th>
                            {{ trans('cruds.totem.fields.codigo') }}
                        </th>
                        <th>
                            {{ trans('cruds.totem.fields.fuente_imagenes_pagina_1') }}
                        </th>
                        <th>
                            {{ trans('cruds.totem.fields.updated_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td class="text-center"><input type="checkbox" id="select-all">

                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach ($establecimientos as $key => $item)
                                    <option value="{{ $item->codigo }}">{{ $item->codigo }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach (App\Models\Totem::FUENTE_IMAGENES_SELECT as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                    </tr>
                </thead>
            </table>
            @include('admin.totems._update_masive')

        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        var table;
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


            let dtOverrideGlobals = {
                 buttons: dtButtons,
                //dom: 'lrtip',
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.totems.index') }}",
                select: false,
                columns: [{
                        data: null, // Columna para los checkboxes
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="row-checkbox" data-id="${row.id}">`;
                        }
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'establecimiento_codigo',
                        name: 'establecimiento.codigo'
                    },
                    {
                        data: 'codigo',
                        name: 'codigo'
                    },
                    {
                        data: 'fuente_imagenes_pagina_1',
                        name: 'fuente_imagenes_pagina_1'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'actions',
                        className: 'text-center',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [2, 'asc']
                ],
                pageLength: 25,
            };
            table = $('#datatable-Totem').DataTable(dtOverrideGlobals);
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
            $('#select-all').on('click', function() {
                var isChecked = $(this).prop('checked');
                $('.row-checkbox').prop('checked', isChecked);
            });
        });
    </script>
@endsection
