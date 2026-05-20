<table id="{{ $config['id'] }}_datatable" class=" table table-bordered table-striped table-hover ajaxTable datatable ">
    <thead>
        <tr>
            @if (!empty($config['show_field_id']))
            <th>
                Id
            </th>
            @endif
            @foreach($config['table_columns'] as $k => $col)
                <th>
                    {{ trans('cruds.' . $config['id'] . '.fields.' . $k) }}
                </th>
            @endforeach            
            <th>
                &nbsp;
            </th>
        </tr>
        <tr>  
            @if (!empty($config['show_field_id']))         
            <td>
                <input class="search" type="text" placeholder="{{ trans('global.search') }}">
            </td>
            @endif
            @foreach($config['table_columns'] as $k => $col)
                <td>
                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                </td>
            @endforeach            
            <td>
            </td>
        </tr>
    </thead>
</table>
