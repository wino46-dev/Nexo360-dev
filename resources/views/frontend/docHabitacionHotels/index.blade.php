@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('doc_habitacion_hotel_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.doc-habitacion-hotels.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.docHabitacionHotel.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.docHabitacionHotel.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-DocHabitacionHotel">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.establecimiento') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.nombre') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.tipo') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.capacidad') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.tipo_cerradura') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docHabitacionHotel.fields.codigo') }}
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
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($establecimientos as $key => $item)
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
                                            @foreach(App\Models\DocHabitacionHotel::TIPO_SELECT as $key => $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search" strict="true">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach(App\Models\DocHabitacionHotel::CAPACIDAD_SELECT as $key => $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search" strict="true">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach(App\Models\DocHabitacionHotel::TIPO_CERRADURA_SELECT as $key => $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
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
                            <tbody>
                                @foreach($docHabitacionHotels as $key => $docHabitacionHotel)
                                    <tr data-entry-id="{{ $docHabitacionHotel->id }}">
                                        <td>
                                            {{ $docHabitacionHotel->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docHabitacionHotel->establecimiento->codigo ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docHabitacionHotel->nombre ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\DocHabitacionHotel::TIPO_SELECT[$docHabitacionHotel->tipo] ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\DocHabitacionHotel::CAPACIDAD_SELECT[$docHabitacionHotel->capacidad] ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\DocHabitacionHotel::TIPO_CERRADURA_SELECT[$docHabitacionHotel->tipo_cerradura] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docHabitacionHotel->codigo ?? '' }}
                                        </td>
                                        <td>
                                            @can('doc_habitacion_hotel_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.doc-habitacion-hotels.show', $docHabitacionHotel->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('doc_habitacion_hotel_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.doc-habitacion-hotels.edit', $docHabitacionHotel->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('doc_habitacion_hotel_delete')
                                                <form action="{{ route('frontend.doc-habitacion-hotels.destroy', $docHabitacionHotel->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('doc_habitacion_hotel_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.doc-habitacion-hotels.massDestroy') }}",
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
    order: [[ 2, 'asc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-DocHabitacionHotel:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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