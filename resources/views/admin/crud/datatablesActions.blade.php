@can($viewGate)
    <!-- <button type="button" class="btn btn-xs btn-primary" onclick="alert('view')">
        {{ trans('global.view') }}
    </button> -->
@endcan
@can($editGate)
    <button type="button" class="btn btn-xs btn-info"
        onclick="crudApp.edit_modal('{{ $row->id }}')">
        {{ trans('global.edit') }}
    </button>
@endcan
@can($deleteGate)
    <button type="button" class="btn btn-xs btn-danger"
        onclick="crudApp.delete('{{ $row->id }}')">
        {{ trans('global.delete') }}
    </button>
@endcan
