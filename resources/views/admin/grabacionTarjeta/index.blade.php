@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.list') }} {{ trans('cruds.grabacionTarjetum.title') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-GrabacionTarjetum">
                <thead>
                    <tr>
                        <th>
                            ID
                        </th>

                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.sesion') }}
                        </th>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.emisor') }}
                        </th>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.receptor') }}
                        </th>

                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.date_in') }}
                        </th>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.date_out') }}
                        </th>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.room_no') }}
                        </th>
                        <th>
                            Estado
                        </th>
                        <th>
                            {{ trans('cruds.grabacionTarjetum.fields.uid_card') }}
                        </th>
                        <th>
                            Creado
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <input style="max-width: 80px" class="search" type="text"
                                placeholder="{{ trans('global.search') }}">
                        </td>

                        <td>
                            <input style="max-width: 80px" class="search" type="text"
                                placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input style="max-width: 120px" class="search" type="text"
                                placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input style="max-width: 110px" class="search" type="text"
                                placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                            <input style="max-width: 80px" class="search" type="text"
                                placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input style="max-width: 80px" class="search" type="text"
                                placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input style="max-width: 120px" class="search" type="text"
                                placeholder="{{ trans('global.search') }}">
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
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('grabacion_tarjetum_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.grabacion-tarjeta.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.grabacion-tarjeta.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'sesion_id',
                        name: 'sesion_id'
                    },
                    {
                        data: 'emisor_name',
                        name: 'emisor.name'
                    },
                    {
                        data: 'receptor_name',
                        name: 'receptor.name'
                    },
                    {
                        data: 'date_in',
                        name: 'date_in'
                    },
                    {
                        data: 'date_out',
                        name: 'date_out'
                    },
                    {
                        data: 'room_no',
                        name: 'room_no'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'uid_card',
                        name: 'uid_card'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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
            let table = $('.datatable-GrabacionTarjetum').DataTable(dtOverrideGlobals);
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
