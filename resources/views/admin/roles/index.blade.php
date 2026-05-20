@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">

            <div class="row">
                <div class="col">
                    {{ trans('global.list') }} {{ trans('cruds.role.title') }}
                </div>
                <div class="col text-right">
                    @can('role_create')
                        <a class="btn btn-success" href="{{ route('admin.roles.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.role.title_singular') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Role">
                    <thead>
                        <tr>

                            <th>
                                {{ trans('cruds.role.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.role.fields.title') }}
                            </th>
                            <th>
                                {{ trans('cruds.role.fields.permissions') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $key => $role)
                            <tr data-entry-id="{{ $role->id }}">

                                <td>
                                    {{ $role->id ?? '' }}
                                </td>
                                <td>
                                    {{ $role->title ?? '' }}
                                </td>
                                <td>
                                    @foreach ($role->permissions as $key => $item)
                                        <span class="badge badge-info">{{ $item->title }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @can('role_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.roles.show', $role->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('role_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.roles.edit', $role->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('role_delete')
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger"
                                                value="{{ trans('global.delete') }}">
                                        </form>
                                    @endcan

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-Role:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
