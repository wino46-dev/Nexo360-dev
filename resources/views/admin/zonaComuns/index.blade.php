@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                {{ trans('cruds.zonaComun.title_singular') }} {{ trans('global.list') }}
            </div>
            <div class="col text-right">
                @can('zona_comun_create')                    
                    <a class="btn btn-success" href="{{ route('admin.zona-comuns.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.zonaComun.title_singular') }}
                    </a>
                    <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                        {{ trans('global.app_csvImport') }}
                    </button>
                    
                @endcan
            </div>
        </div>
        
    </div>
    @include('csvImport.modal', ['model' => 'ZonaComun', 'route' => 'admin.zona-comuns.parseCsvImport'])             
    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ZonaComun">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.zonaComun.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.zonaComun.fields.nombre') }}
                    </th>
                    <th>
                        {{ trans('cruds.zonaComun.fields.codigo') }}
                    </th>
                    <th>
                        {{ trans('cruds.zonaComun.fields.orden') }}
                    </th>
                    <th>
                        {{ trans('cruds.zonaComun.fields.establecimiento') }}
                    </th>                   
                    
                    <th>
                        {{ trans('cruds.zonaComun.fields.global') }}
                    </th>
                    <th>
                        {{ trans('cruds.zonaComun.fields.estado') }}
                    </th>

                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
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
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($establecimientos as $key => $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
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
    $(function() {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('zona_comun_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.zona-comuns.massDestroy') }}",
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
            ajax: "{{ route('admin.zona-comuns.index') }}",
            columns: [{
                    data: 'placeholder',
                    name: 'placeholder'
                },
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'nombre',
                    name: 'nombre'
                },
                {
                    data: 'codigo',
                    name: 'codigo'
                },                
                {
                    data: 'orden',
                    name: 'orden',
                    
                },
                {
                    data: 'establecimiento_nombre',
                    name: 'establecimiento.nombre'
                },
                {
                    data: 'global',
                    name: 'global'
                },
                
                {
                    data: 'estado',
                    name: 'estado'
                },
                {
                    data: 'actions',
                    name: '{{ trans('global.actions') }}'
                }
            ],
            orderCellsTop: true,
            order: [
                [5, 'asc'], [4, 'asc']
            ],
            pageLength: 100,
        };
        let table = $('.datatable-ZonaComun').DataTable(dtOverrideGlobals);
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