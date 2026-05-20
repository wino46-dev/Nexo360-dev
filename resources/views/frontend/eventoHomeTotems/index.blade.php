@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('evento_home_totem_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.evento-home-totems.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.eventoHomeTotem.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.eventoHomeTotem.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-EventoHomeTotem">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.eventoHomeTotem.fields.emisor') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.eventoHomeTotem.fields.receptor') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.eventoHomeTotem.fields.tipo_evento') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.eventoHomeTotem.fields.objeto') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.eventoHomeTotem.fields.canal_transmision') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.eventoHomeTotem.fields.created_at') }}
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
                                            @foreach($users as $key => $item)
                                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($users as $key => $item)
                                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($tipo_eventos as $key => $item)
                                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <select class="search" strict="true">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach(App\Models\EventoHomeTotem::CANAL_TRANSMISION_SELECT as $key => $item)
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
                                @foreach($eventoHomeTotems as $key => $eventoHomeTotem)
                                    <tr data-entry-id="{{ $eventoHomeTotem->id }}">
                                        <td>
                                            {{ $eventoHomeTotem->emisor->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $eventoHomeTotem->receptor->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $eventoHomeTotem->tipo_evento->nombre ?? '' }}
                                        </td>
                                        <td>
                                            {{ $eventoHomeTotem->objeto ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\EventoHomeTotem::CANAL_TRANSMISION_SELECT[$eventoHomeTotem->canal_transmision] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $eventoHomeTotem->created_at ?? '' }}
                                        </td>
                                        <td>
                                            @can('evento_home_totem_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.evento-home-totems.show', $eventoHomeTotem->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan


                                            @can('evento_home_totem_delete')
                                                <form action="{{ route('frontend.evento-home-totems.destroy', $eventoHomeTotem->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('evento_home_totem_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.evento-home-totems.massDestroy') }}",
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
    pageLength: 25,
  });
  let table = $('.datatable-EventoHomeTotem:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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