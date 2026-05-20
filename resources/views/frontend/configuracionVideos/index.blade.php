@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('configuracion_video_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.configuracion-videos.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.configuracionVideo.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.configuracionVideo.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-ConfiguracionVideo">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.configuracionVideo.fields.totem') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.configuracionVideo.fields.sip_identity') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.configuracionVideo.fields.display_name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.configuracionVideo.fields.sip_registar') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.configuracionVideo.fields.sip_identity_destino') }}
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
                                            @foreach($totems as $key => $item)
                                                <option value="{{ $item->codigo }}">{{ $item->codigo }}</option>
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
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($configuracionVideos as $key => $configuracionVideo)
                                    <tr data-entry-id="{{ $configuracionVideo->id }}">
                                        <td>
                                            {{ $configuracionVideo->totem->codigo ?? '' }}
                                        </td>
                                        <td>
                                            {{ $configuracionVideo->sip_identity ?? '' }}
                                        </td>
                                        <td>
                                            {{ $configuracionVideo->display_name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $configuracionVideo->sip_registar ?? '' }}
                                        </td>
                                        <td>
                                            {{ $configuracionVideo->sip_identity_destino ?? '' }}
                                        </td>
                                        <td>
                                            @can('configuracion_video_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.configuracion-videos.show', $configuracionVideo->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('configuracion_video_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.configuracion-videos.edit', $configuracionVideo->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
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
  
  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'asc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-ConfiguracionVideo:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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