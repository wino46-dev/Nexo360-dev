@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.docHotelReceptionInfo.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.doc-hotel-reception-infos.update", [$docHotelReceptionInfo->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="establecimiento_id">{{ trans('cruds.docHotelReceptionInfo.fields.establecimiento') }}</label>
                            <select class="form-control select2" name="establecimiento_id" id="establecimiento_id">
                                @foreach($establecimientos as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('establecimiento_id') ? old('establecimiento_id') : $docHotelReceptionInfo->establecimiento->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('establecimiento'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('establecimiento') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHotelReceptionInfo.fields.establecimiento_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="hour_open">{{ trans('cruds.docHotelReceptionInfo.fields.hour_open') }}</label>
                            <input class="form-control timepicker" type="text" name="hour_open" id="hour_open" value="{{ old('hour_open', $docHotelReceptionInfo->hour_open) }}">
                            @if($errors->has('hour_open'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('hour_open') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHotelReceptionInfo.fields.hour_open_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="hour_close">{{ trans('cruds.docHotelReceptionInfo.fields.hour_close') }}</label>
                            <input class="form-control timepicker" type="text" name="hour_close" id="hour_close" value="{{ old('hour_close', $docHotelReceptionInfo->hour_close) }}">
                            @if($errors->has('hour_close'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('hour_close') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHotelReceptionInfo.fields.hour_close_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="open_holiday">{{ trans('cruds.docHotelReceptionInfo.fields.open_holiday') }}</label>
                            <input class="form-control timepicker" type="text" name="open_holiday" id="open_holiday" value="{{ old('open_holiday', $docHotelReceptionInfo->open_holiday) }}">
                            @if($errors->has('open_holiday'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('open_holiday') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHotelReceptionInfo.fields.open_holiday_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="close_holiday">{{ trans('cruds.docHotelReceptionInfo.fields.close_holiday') }}</label>
                            <input class="form-control timepicker" type="text" name="close_holiday" id="close_holiday" value="{{ old('close_holiday', $docHotelReceptionInfo->close_holiday) }}">
                            @if($errors->has('close_holiday'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('close_holiday') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHotelReceptionInfo.fields.close_holiday_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.docHotelReceptionInfo.fields.acces_type_after_hour') }}</label>
                            <select class="form-control" name="acces_type_after_hour" id="acces_type_after_hour">
                                <option value disabled {{ old('acces_type_after_hour', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\DocHotelReceptionInfo::ACCES_TYPE_AFTER_HOUR_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('acces_type_after_hour', $docHotelReceptionInfo->acces_type_after_hour) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('acces_type_after_hour'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('acces_type_after_hour') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHotelReceptionInfo.fields.acces_type_after_hour_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="box_locate">{{ trans('cruds.docHotelReceptionInfo.fields.box_locate') }}</label>
                            <textarea class="form-control ckeditor" name="box_locate" id="box_locate">{!! old('box_locate', $docHotelReceptionInfo->box_locate) !!}</textarea>
                            @if($errors->has('box_locate'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('box_locate') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHotelReceptionInfo.fields.box_locate_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="box_photo">{{ trans('cruds.docHotelReceptionInfo.fields.box_photo') }}</label>
                            <div class="needsclick dropzone" id="box_photo-dropzone">
                            </div>
                            @if($errors->has('box_photo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('box_photo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHotelReceptionInfo.fields.box_photo_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="acces_videoportero">{{ trans('cruds.docHotelReceptionInfo.fields.acces_videoportero') }}</label>
                            <textarea class="form-control ckeditor" name="acces_videoportero" id="acces_videoportero">{!! old('acces_videoportero', $docHotelReceptionInfo->acces_videoportero) !!}</textarea>
                            @if($errors->has('acces_videoportero'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('acces_videoportero') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.docHotelReceptionInfo.fields.acces_videoportero_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('frontend.doc-hotel-reception-infos.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $docHotelReceptionInfo->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

<script>
    var uploadedBoxPhotoMap = {}
Dropzone.options.boxPhotoDropzone = {
    url: '{{ route('frontend.doc-hotel-reception-infos.storeMedia') }}',
    maxFilesize: 4, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 4,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="box_photo[]" value="' + response.name + '">')
      uploadedBoxPhotoMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedBoxPhotoMap[file.name]
      }
      $('form').find('input[name="box_photo[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($docHotelReceptionInfo) && $docHotelReceptionInfo->box_photo)
      var files = {!! json_encode($docHotelReceptionInfo->box_photo) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="box_photo[]" value="' + file.file_name + '">')
        }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}

</script>
@endsection