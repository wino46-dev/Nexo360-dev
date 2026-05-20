@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('doc_no_deseado_hotel_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.doc-no-deseado-hotels.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.docNoDeseadoHotel.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.docNoDeseadoHotel.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-DocNoDeseadoHotel">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.establecimiento') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.fecha') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.no_deseado') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.motivo') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docNoDeseadoHotel.fields.updated_at') }}
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
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($docNoDeseadoHotels as $key => $docNoDeseadoHotel)
                                    <tr data-entry-id="{{ $docNoDeseadoHotel->id }}">
                                        <td>
                                            {{ $docNoDeseadoHotel->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docNoDeseadoHotel->establecimiento->codigo ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docNoDeseadoHotel->fecha ?? '' }}
                                        </td>
                                        <td>
                                            <span style="display:none">{{ $docNoDeseadoHotel->no_deseado ?? '' }}</span>
                                            <input type="checkbox" disabled="disabled" {{ $docNoDeseadoHotel->no_deseado ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            {{ $docNoDeseadoHotel->motivo ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docNoDeseadoHotel->updated_at ?? '' }}
                                        </td>
                                        <td>
                                            @can('doc_no_deseado_hotel_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.doc-no-deseado-hotels.show', $docNoDeseadoHotel->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('doc_no_deseado_hotel_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.doc-no-deseado-hotels.edit', $docNoDeseadoHotel->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('doc_no_deseado_hotel_delete')
                                                <form action="{{ route('frontend.doc-no-deseado-hotels.destroy', $docNoDeseadoHotel->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('doc_no_deseado_hotel_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.doc-no-deseado-hotels.massDestroy') }}",
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
    order: [[ 5, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-DocNoDeseadoHotel:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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