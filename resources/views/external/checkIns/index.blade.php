@extends('layouts.external')
@section('content')
    @can('check_in_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <!--  <a class="btn btn-success" href="{{ route('external.check-ins.create') }}">
                                                                                                                {{ trans('global.add') }} {{ trans('cruds.checkIn.title_singular') }}
                                                                                                            </a> -->
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('global.list') }} {{ trans('cruds.checkIn.title') }}
        </div>
        <div class="card-body">
            @include('external.checkIns.filtros')
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-CheckIn">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>
                            Establecimiento
                        </th>
                        <th>
                            Código Reserva
                        </th>
                        <th>
                            No. Documento
                        </th>
                        <th>
                            Nombre
                        </th>
                        <th>
                            Apellidos
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Movil
                        </th>
                        <th>
                            {{ trans('cruds.checkIn.fields.created_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>

                </thead>
            </table>
            <br />
            <button id="send-selected" type="button" style="width:180px" class="btn btn-sm btn-info px-5">Descargar
                PDF</button>
            <br /><br />
        </div>
    </div>
    @include('external.checkIns._parte_viajero_modal')
@endsection
@section('scripts')
    @parent
    <script>
        var table;
        // Flags coming from server about Control de Sesión
        var HAS_ACTIVE_CONTROL = {{ !empty($hasActiveControlSesion) && !empty($defaultEstablecimientoId) ? 'true' : 'false' }};
        var DEFAULT_EID = {!! json_encode($defaultEstablecimientoId ?? '') !!};
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @php($panelPrefix = (auth()->check() && method_exists(auth()->user(), 'isExternal') && auth()->user()->isExternal()) ? 'external' : 'external')
            let dtOverrideGlobals = {
                //buttons: dtButtons,
                dom: 'lrtip',
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                select: false,
                // ajax: "{{ route('external.check-ins.index') }}",
                ajax: {
                    url: "{{ route('external.check-ins.index') }}/list",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d){
                        if (HAS_ACTIVE_CONTROL && DEFAULT_EID) {
                            d.filtro_establecimiento_id = DEFAULT_EID;
                        }
                        return d;
                    }
                },
                columns: [{
                        data: null, // Columna para los checkboxes
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="row-checkbox" data-id="${row.id}">`;
                        }
                    },
                    {
                        data: 'establecimiento_name',
                        name: 'establecimiento_name'
                    },
                    {
                        data: 'reservation_name',
                        name: 'reservation_name'
                    },
                    {
                        data: 'document_number',
                        name: 'document_number'
                    },
                    {
                        data: 'firstname',
                        name: 'firstname'
                    },
                    {
                        data: 'lastname',
                        name: 'lastname'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile'
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
                    [8, 'desc']
                ],
                pageLength: 25,
            };
            table = $('.datatable-CheckIn').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            $('#select-all').on('click', function() {
                var isChecked = $(this).prop('checked');
                $('.row-checkbox').prop('checked', isChecked);
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

            function setLoading(btn, loading){
                if(!btn) return;
                var spinnerHtml = (typeof window.loading_sm !== 'undefined' && window.loading_sm) ? window.loading_sm : '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
                if(loading){
                    btn.data('prev', btn.html());
                    btn.prop('disabled', true).html(spinnerHtml);
                } else {
                    btn.prop('disabled', false).html(btn.data('prev'));
                }
            }

            $('#send-selected').on('click', function(){
                var selectedIds = [];
                $('.row-checkbox:checked').each(function(){ selectedIds.push($(this).data('id')); });
                if (selectedIds.length === 0) {
                    alert('No se ha seleccionado ninguna fila.');
                    return;
                }
                var btnZip = $('#send-selected');
                setLoading(btnZip, true);

                $.ajax({
                    url: '{{ route(((auth()->check() && method_exists(auth()->user(), 'isExternal') && auth()->user()->isExternal()) ? 'external' : 'external') . '.check-ins.index') }}/pdf-download',
                    type: 'POST',
                    data: { ids: selectedIds },
                    xhrFields: { responseType: 'blob' }
                })
                .done(function(response, status, xhr){
                    const disposition = xhr.getResponseHeader('Content-Disposition');
                    let fileName = 'download.zip';
                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        const matches = filenameRegex.exec(disposition);
                        if (matches && matches[1]) { fileName = matches[1].replace(/["']/g, ''); }
                    }
                    const blob = new Blob([response], { type: 'application/zip' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url; a.download = fileName; document.body.appendChild(a); a.click();
                    window.URL.revokeObjectURL(url); a.remove();
                })
                .fail(function(){
                    alert('Ocurrió un error al intentar generar el archivo ZIP.');
                })
                .always(function(){
                    try { setLoading(btnZip, false); } catch(e) { /* noop */ }
                });
            });

        });

        function parte_viajero_pdf(checkin_id) {
            $('#parte_modal').modal('show');
            $('#parte_modal').find('.modal-body').html('<br />' + loading1);
            $.ajax({
                url: "{{ url(((auth()->check() && method_exists(auth()->user(), 'isExternal') && auth()->user()->isExternal()) ? 'external' : 'external') . '/check-ins/parte-viajero-pdf') }}",
                method: 'POST',
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    checkin_id: checkin_id
                },
                success: function(response) {
                    $('#parte_modal').find('.modal-body').html(response);
                },
                error: function(response) {

                },
            });
        }
    </script>
    <style>
        .fieldset1 {
            border: 1px solid #ccc;
        }

        .fieldset1 legend {
            font-size: 14px !important;
            margin-left: 5px;
            padding-left: 5px;
            margin-bottom: 0px
        }
    </style>
@endsection
