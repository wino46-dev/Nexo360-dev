<div class="card">
    <div class="card-header">
        {{ trans('global.list') }} {{ trans('cruds.respuestaPago.title') }}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-pagoOrigenRespuestaPagos">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.pago_origen') }}
                        </th>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.tipo_pago') }}
                        </th>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.importe') }}
                        </th>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.moneda') }}
                        </th>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.fecha_operacion') }}
                        </th>
                        <th>
                            {{ trans('cruds.respuestaPago.fields.resultado') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($respuestaPagos as $key => $respuestaPago)
                        <tr data-entry-id="{{ $respuestaPago->id }}">

                            <td>
                                {{ $respuestaPago->pago_origen->factura ?? '' }}
                            </td>
                            <td>
                                {{ $respuestaPago->tipo_pago ?? '' }}
                            </td>
                            <td>
                                {{ $respuestaPago->importe ?? '' }}
                            </td>
                            <td>
                                {{ $respuestaPago->moneda ?? '' }}
                            </td>
                            <td>
                                {{ $respuestaPago->fecha_operacion ?? '' }}
                            </td>
                            <td>
                                {{ $respuestaPago->resultado ?? '' }}
                            </td>
                            <td>
                                @can('respuesta_pago_show')
                                    <a class="btn btn-xs btn-primary"
                                        href="{{ route('admin.respuesta-pagos.show', $respuestaPago->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('respuesta_pago_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.respuesta-pagos.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id')
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

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [0, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-pagoOrigenRespuestaPagos:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
