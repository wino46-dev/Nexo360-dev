@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-cloud-upload-alt"></i> Logs FTP de partes
    </div>

    <div class="card-body">
        <form id="filters" class="mb-3">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="f_establecimiento_id">Establecimiento</label>
                    <select id="f_establecimiento_id" name="establecimiento_id" class="form-control">
                        <option value="">-- Todos --</option>
                        @isset($establecimientos)
                            @foreach($establecimientos as $id => $nombre)
                                <option value="{{ $id }}">{{ $nombre }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="f_check_in_id">CheckIn ID</label>
                    <input type="number" min="1" id="f_check_in_id" name="check_in_id" class="form-control" />
                </div>
                <div class="form-group col-md-2">
                    <label for="f_status">Estado</label>
                    <select id="f_status" name="status" class="form-control">
                        <option value="">-- Todos --</option>
                        <option value="success">success</option>
                        <option value="skip">skip</option>
                        <option value="fail">fail</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="f_disk">Disco</label>
                    <input type="text" id="f_disk" name="disk" class="form-control" placeholder="ftp_cliente_..."/>
                </div>
                <div class="form-group col-md-2">
                    <label for="f_remote_path">Ruta remota</label>
                    <input type="text" id="f_remote_path" name="remote_path" class="form-control"/>
                </div>
                <div class="form-group col-md-2">
                    <label for="f_message">Mensaje</label>
                    <input type="text" id="f_message" name="message" class="form-control"/>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="f_date_field">Fecha por</label>
                    <select id="f_date_field" name="date_field" class="form-control">
                        <option value="created_at">Creado</option>
                        <option value="uploaded_at">Subido</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="f_date_start">Fecha inicio</label>
                    <input type="datetime-local" id="f_date_start" name="date_start" class="form-control"/>
                </div>
                <div class="form-group col-md-3">
                    <label for="f_date_end">Fecha fin</label>
                    <input type="datetime-local" id="f_date_end" name="date_end" class="form-control"/>
                </div>
                <div class="form-group col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-filter"></i> Filtrar</button>
                    <button type="button" id="btn-reset" class="btn btn-secondary"><i class="fas fa-undo"></i> Reset</button>
                </div>
            </div>
        </form>

        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-FtpUploadLog">
            <thead>
                <tr>
                    <th width="10"></th>
                    <th>ID</th>
                    <th>Establecimiento</th>
                    <th>CheckIn</th>
                    <th>Disco</th>
                    <th>Ruta remota</th>
                    <th>Estado</th>
                    <th>Intentos</th>
                    <th>Tamaño local</th>
                    <th>Tamaño remoto</th>
                    <th>Subido en</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
$(function () {
  let dtOverrideGlobals = {
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: {
      url: "{{ route('admin.ftp-upload-logs.index') }}",
      data: function(d) {
        d.establecimiento_id = $('#f_establecimiento_id').val();
        d.check_in_id = $('#f_check_in_id').val();
        d.status = $('#f_status').val();
        d.disk = $('#f_disk').val();
        d.remote_path = $('#f_remote_path').val();
        d.message = $('#f_message').val();
        d.date_field = $('#f_date_field').val();
        d.date_start = $('#f_date_start').val();
        d.date_end = $('#f_date_end').val();
      }
    },
    columns: [
      { data: 'placeholder', name: 'placeholder' },
      { data: 'id', name: 'id' },
      { data: 'establecimiento_nombre', name: 'establecimiento.nombre' },
      { data: 'check_in_id', name: 'check_in_id' },
      { data: 'disk', name: 'disk' },
      { data: 'remote_path', name: 'remote_path' },
      { data: 'status', name: 'status' },
      { data: 'attempts', name: 'attempts' },
      { data: 'size_local', name: 'size_local' },
      { data: 'size_remote', name: 'size_remote' },
      { data: 'uploaded_at', name: 'uploaded_at' },
      { data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  const table = $('.datatable-FtpUploadLog').DataTable(dtOverrideGlobals);

  $('#filters').on('submit', function(e){
    e.preventDefault();
    table.ajax.reload();
  });
  $('#btn-reset').on('click', function(){
    $('#filters').find('input, select').val('');
    $('#f_date_field').val('created_at');
    table.ajax.reload();
  });

  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  });
});
</script>
@endsection
