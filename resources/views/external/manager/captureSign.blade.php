<form id="signForm" class="signForm">
    @csrf
    <div class="form-group">
        <input type="hidden" name="sesion_id" value="{{$control_actual->id}}">
        @if($errors->has('sesion'))
            <div class="invalid-feedback">
                {{ $errors->first('sesion') }}
            </div>
        @endif
        <span class="help-block">{{ trans('cruds.firmaCheckIn.fields.sesion_helper') }}</span>
    </div>
    <!-- <div class="form-group">
        <label class="required" for="documento">{{ trans('cruds.firmaCheckIn.fields.documento') }}</label>
        <div class="needsclick dropzone {{ $errors->has('documento') ? 'is-invalid' : '' }}" id="documento-dropzone">
        </div>
        @if($errors->has('documento'))
            <div class="invalid-feedback">
                {{ $errors->first('documento') }}
            </div>
        @endif
        <span class="help-block">{{ trans('cruds.firmaCheckIn.fields.documento_helper') }}</span>
    </div> -->
    <div class="form-group">
        <button class="btn btn-danger" type="submit">
            Solicitar Firma
        </button>
    </div>


</form>

<hr>
<br>
<div style="margin-top: 20px">
    <div class="alert alert-success" id="success-alert-push-sign" style="display: none">
        <strong>Bien Hecho! </strong> Solicitud de firma correcta.
    </div>

    <div class="alert alert-danger" id="error-alert-push-sign" style="display: none">
        <strong>Ups! </strong> Ha ocurrido un error.
    </div>
</div>
<br><br>
<h5 id="respuestaSignPush" class="respuestaSignPush" style="font-weight: bold;display: none;color: darkgreen"></h5>




    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Dropzone.options.documentoDropzone = {
                url: '{{ route('external.firma-check-ins.storeMedia') }}',
                maxFilesize: 4, // MB
                maxFiles: 1,
                addRemoveLinks: true,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                params: {
                    size: 4
                },
                success: function (file, response) {
                    $('form').find('input[name="documento"]').remove()
                    $('form').append('<input type="hidden" name="documento" value="' + response.name + '">')
                },
                removedfile: function (file) {
                    file.previewElement.remove()
                    if (file.status !== 'error') {
                        $('form').find('input[name="documento"]').remove()
                        this.options.maxFiles = this.options.maxFiles + 1
                    }
                },
                init: function () {
                    @if(isset($firmaCheckIn) && $firmaCheckIn->documento)
                    var file = {!! json_encode($firmaCheckIn->documento) !!}
                    this.options.addedfile.call(this, file)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="documento" value="' + file.file_name + '">')
                    this.options.maxFiles = this.options.maxFiles - 1
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
        });
    </script>
<script type="text/javascript">
    $(document).ready(function(){

        $('#signForm').on('submit',function(e){
            e.preventDefault();
            $.ajax({
                url: "{{ route("external.firma-check-ins.store") }}",
                method: 'POST',
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
                data:new FormData(this),
                success:function(response){

                    if(response['success']){
                        $("#success-alert-push-sign").show();
                        $("#success-alert-push-sign").fadeTo(2000, 500).slideUp(500, function() {
                            $("#success-alert-push-sign").slideUp(500);
                        });

                        $(".respuestaignPush").empty();
                        $(".respuestaSignPush").show();
                        $(".respuestaSignPush").append(response['success']);

                        console.log(response)

                    }
                    if(response['error']){
                        $("#error-alert-push-sign").show();
                        $("#error-alert-push-sign").fadeTo(2000, 500).slideUp(500, function() {
                            $("#error-alert-push-sign").slideUp(500);
                        });
                        alert(response['error']['errorInfo'])
                    }


                },
                error: function(response) {

                },
            });
        });
    });
</script>

