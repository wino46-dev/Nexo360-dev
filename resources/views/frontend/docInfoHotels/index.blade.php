@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('doc_info_hotel_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.doc-info-hotels.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.docInfoHotel.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.docInfoHotel.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-DocInfoHotel">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.hotel') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.categoria') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.pais') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.provincia') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.ciudad') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.direccion') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.codigo_postal') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.latitud') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.longitud') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.telefono') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.emergencias') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.email') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.web') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.enlace_fotos') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.cuenta_bancaria') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.modos_cobro') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.docInfoHotel.fields.pet_friendly') }}
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
                                        <select class="search" strict="true">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach(App\Models\DocInfoHotel::CATEGORIA_SELECT as $key => $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($pais as $key => $item)
                                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($provincia as $key => $item)
                                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($ciudads as $key => $item)
                                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
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
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <select class="search" strict="true">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach(App\Models\DocInfoHotel::PET_FRIENDLY_SELECT as $key => $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($docInfoHotels as $key => $docInfoHotel)
                                    <tr data-entry-id="{{ $docInfoHotel->id }}">
                                        <td>
                                            {{ $docInfoHotel->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->hotel->codigo ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\DocInfoHotel::CATEGORIA_SELECT[$docInfoHotel->categoria] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->pais->nombre ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->provincia->nombre ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->ciudad->nombre ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->direccion ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->codigo_postal ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->latitud ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->longitud ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->telefono ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->emergencias ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->email ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->web ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->enlace_fotos ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->cuenta_bancaria ?? '' }}
                                        </td>
                                        <td>
                                            {{ $docInfoHotel->modos_cobro ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\DocInfoHotel::PET_FRIENDLY_SELECT[$docInfoHotel->pet_friendly] ?? '' }}
                                        </td>
                                        <td>
                                            @can('doc_info_hotel_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.doc-info-hotels.show', $docInfoHotel->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('doc_info_hotel_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.doc-info-hotels.edit', $docInfoHotel->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('doc_info_hotel_delete')
                                                <form action="{{ route('frontend.doc-info-hotels.destroy', $docInfoHotel->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('doc_info_hotel_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.doc-info-hotels.massDestroy') }}",
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
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-DocInfoHotel:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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