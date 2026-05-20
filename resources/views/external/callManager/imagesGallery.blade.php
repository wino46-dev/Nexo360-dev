@if(isset($establecimiento))
    <style>

    </style>

    <!-- The grid: four columns -->

    <div class=" text-right mb-3">
        <button class="btn btn-success" style=" width:190px" type="button" onclick="image_toten()">Enviar Imágen al Totem</button>
    </div>
    <hr />

    <div class="row" >
        @foreach($imagenes as $key => $media)
            <div class="col-4 col-sm-3 col-md-2 col-lg-2 col-xl-1">
                <img  class="imgTour img-fluid"  src="{{ $media->getUrl() }}" alt="{{ $media->getUrl() }}" onclick="imagen_select(this);">
            </div>
        @endforeach
        <div class="col-4 col-sm-3 col-md-2 col-lg-2 col-xl-1" >
            <img  class="imgTour" style="max-width: 100%" src="/img/ocultar_imagen.jpg" alt="ocultar_imagen" onclick="imagen_select(this);">
        </div>
    </div>
    <hr />
    <h4>Carrousel Crosselling</h4>
    <div class="row">
        @foreach($imagenes_tour as $key => $media)
            <div class="col-4 col-sm-3 col-md-2 col-lg-2 col-xl-1">
                <img  class="imgTour img-fluid" src="{{ $media->getUrl() }}" alt="{{ $media->getUrl() }}" onclick="imagen_select(this);">
            </div>
        @endforeach
        <div class="col-4 col-sm-3 col-md-2 col-lg-2 col-xl-1" >
            <img  class="imgTour img-fluid" src="/img/ocultar_imagen.jpg" alt="ocultar_imagen" onclick="imagen_select(this);">
        </div>
    </div>
    <hr />
    <div class="card" style="border: none;background:none;padding-top: 10px;padding-bottom: 20px;box-shadow: none">
        <form id="changeImageGalery" class="changeImageGalery">
            @csrf
            <input type="hidden" name="receptor_id" id="receptor_id" value="{{$control_actual->receptor_id}}"></input>
            <input type="hidden" name="emisor_id" id="emisor_id" value="{{$control_actual->emisor_id}}"></input>
            <input type="hidden" name="sesion_id" id="sesion_id" value="{{$control_actual->id}}"></input>
            <input type="hidden" name="url" id="url" value="test_url"></input>
            <button class="btn btn-success" style="float: right" type="submit">Enviar Imágen al Totem</button>
        </form>
    </div>



@endif



<script type="text/javascript">
    function image_toten(){
        $('#changeImageGalery').trigger('submit');
    }
    $(document).ready(function(){

        $('#changeImageGalery').on('submit',function(e){

            if($('#changeImageGalery').find('#url').val() == 'test_url'){
                Toast.fire({
                    icon: "error",
                    title: "Seleccione un imágen"
                });
                return false;
            }
            e.preventDefault();
            $.ajax({
                url: "{{ route("external.evento-home-totems.tour-galery") }}",
                method: 'POST',
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
                data:new FormData(this),
                success:function(response){

                    if(response['success']){
                        Toast.fire({
                            icon: "success",
                            title: response['success']
                        });
                    }

                    if(response['error']){
                        Toast.fire({
                            icon: "error",
                            title: "Error: <br >" +response['error']['errorInfo']
                        });
                    }

                },
                error: function(response) {
                    Toast.fire({
                        icon: "error",
                        title: "Error al lanzar Imagen"
                    });
                },
            });
        });
    });
</script>
<script>
    function imagen_select(imgs) {

        // Get the expanded image
        var expandImg = document.getElementById("expandedImg");

        // Get the image text
        var imgText = document.getElementById("imgtext");

        // Use the same src in the expanded image as the image being clicked on from the grid
       // expandImg.src = imgs.src;

        // Use the value of the alt attribute of the clickable image as text inside the expanded image
       // imgText.innerHTML = imgs.alt;

        document.getElementById("url").value = imgs.alt;


        // Show the container element (hidden with CSS)
        const boxes = document.querySelectorAll('.imgTour');
        boxes.forEach(box => {
            box.style.border = 'none';
        });

        imgs.style.border = "3px solid green";

       /// expandImg.parentElement.style.display = "none";

    }
</script>
