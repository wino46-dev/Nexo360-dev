@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('check_in_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.check-ins.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.checkIn.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.checkIn.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-CheckIn">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.cliente') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.reserva') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.habitacion') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.totem') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.llave') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.firma_verificada') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.checkIn.fields.created_at') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($clientes as $key => $item)
                                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($reservas as $key => $item)
                                                <option value="{{ $item->codigo }}">{{ $item->codigo }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($habitacions as $key => $item)
                                                <option value="{{ $item->codigo }}">{{ $item->codigo }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($totems as $key => $item)
                                                <option value="{{ $item->codigo }}">{{ $item->codigo }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($checkIns as $key => $checkIn)
                                    <tr data-entry-id="{{ $checkIn->id }}">
                                        <td>
                                            {{ $checkIn->cliente->nombre ?? '' }}
                                        </td>
                                        <td>
                                            {{ $checkIn->reserva->codigo ?? '' }}
                                        </td>
                                        <td>
                                            {{ $checkIn->habitacion->codigo ?? '' }}
                                        </td>
                                        <td>
                                            {{ $checkIn->totem->codigo ?? '' }}
                                        </td>
                                        <td>
                                            {{ $checkIn->llave ?? '' }}
                                        </td>
                                        <td>
                                            <span style="display:none">{{ $checkIn->firma_verificada ?? '' }}</span>
                                            <input type="checkbox" disabled="disabled" {{ $checkIn->firma_verificada ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            {{ $checkIn->created_at ?? '' }}
                                        </td>
                                        <td>
                                            @can('check_in_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.check-ins.show', $checkIn->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('check_in_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.check-ins.edit', $checkIn->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('check_in_delete')
                                                <form action="{{ route('frontend.check-ins.destroy', $checkIn->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
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

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('check_in_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.check-ins.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 6, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-CheckIn:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
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
})

</script>
@endsection