<script>
    var table;
    $(function() {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

        var tableHeight = window.innerHeight - 400;
        
        let dtOverrideGlobals = {
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            
            scrollCollapse: true,
            scrollY: tableHeight + "px",
            
            aaSorting: [],
            //ajax: "{{ $config['url'] }}",
            ajax: {
                url: "{{ $config['url'] }}/list",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                @if (!empty($config['show_field_id']))
                {
                    data: 'id',
                    name: 'id'
                },
                @endif
                @foreach($config['table_columns'] as $k => $col)
                {
                    data: '{{ $k }}',
                    name: '{{ $k }}',
                    className: '{{ isset($col['class']) ? $col['class'] : '' }}'
                },
                @endforeach  
                
                {
                    data: 'actions',
                    name: '{{ trans('global.actions') }}',
                    className: 'text-center text-nowrap'
                }
            ],
            orderCellsTop: true,
            order: [
                [1, 'desc']
            ],
            pageLength: 100,
        };
        table = $('#{{ $config['id'] }}_datatable').DataTable(dtOverrideGlobals);

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
