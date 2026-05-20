@if(isset($establecimiento))
    <style>
        /* The grid: Four equal columns that floats next to each other */
        .column {
            float: left;
            width: 20%;
            padding: 5px;
        }

        /* Style the images inside the grid */
        .column img {
            opacity: 0.8;
            cursor: pointer;
        }

        .column img:hover {
            opacity: 1;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }



        /* Expanding image text */
        #imgtext {
            position: absolute;
            bottom: 15px;
            left: 15px;
            color: white;
            font-size: 20px;
        }

        /* Closable button inside the image */
        .closebtn {
            position: absolute;
            top: 10px;
            right: 15px;
            color: white;
            font-size: 35px;
            cursor: pointer;
        }

        
    </style>

    <!-- The grid: four columns -->

    <div class=" text-right mb-3">        
        <button class="btn btn-success" style=" width:190px" type="button" onclick="image_toten()">Enviar Imágen al Totem</button>        
    </div>
    <hr />

    <div class="row" style="margin-bottom: 10px">
        @foreach($imagenes as $key => $media)
            <div class="column">
                <img  class="imgTour" style="max-width: 100%" src="{{ $media->getUrl() }}" alt="{{ $media->getUrl() }}" onclick="myFunction(this);">
            </div>
        @endforeach
        <div class="column" >
            <img  class="imgTour" style="max-width: 100%" src="/img/ocultar_imagen.jpg" alt="ocultar_imagen" onclick="myFunction(this);">
        </div>
    </div>
    <hr />
    <h4>Carrousel Crosselling</h4>
    <div class="row" style="margin-bottom: 10px">
        @foreach($imagenes_tour as $key => $media)
            <div class="column">
                <img  class="imgTour" style="max-width: 100%" src="{{ $media->getUrl() }}" alt="{{ $media->getUrl() }}" onclick="myFunction(this);">
            </div>
        @endforeach
        <div class="column" >
            <img  class="imgTour" style="max-width: 100%" src="/img/ocultar_imagen.jpg" alt="ocultar_imagen" onclick="myFunction(this);">
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


    <div class="alert alert-success" id="success-alert-tour-galery" style="display: none">

    </div>

    <div class="alert alert-danger" id="error-alert-tour-galery" style="display: none">
        <strong>Ups! </strong> Ha ocurrido un error.
    </div>


    <!-- The expanding image container -->
    <div class="containered" style="background: transparent;margin:30px auto">
        <!-- Close the image -->
        <span onclick="this.parentElement.style.display='none'" class="closebtn">&times;</span>

        <!-- Expanded image -->
        <img id="expandedImg" style="width:100%">

        <!-- Image text -->
        <div id="imgtext" style="display: none"></div>
    </div>


@endif



<script type="text/javascript">
    function image_toten(){
        $('#changeImageGalery').trigger('submit');
    }
    $(document).ready(function(){

        $('#changeImageGalery').on('submit',function(e){
            e.preventDefault();
            $.ajax({
                url: "{{ route("admin.evento-home-totems.tour-galery") }}",
                method: 'POST',
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
                data:new FormData(this),
                success:function(response){

                    if(response['success']){
                        $("#success-alert-tour-galery").show();
                        $("#success-alert-tour-galery").empty();
                        $('#success-alert-tour-galery').append(' <strong>Bien Hecho!  &nbsp;&nbsp;&nbsp;</strong> ' + response['success'] )
                        $("#success-alert-tour-galery").fadeTo(2000, 500).slideUp(500, function() {
                            $("#success-alert-tour-galery").slideUp(500);
                        });
                    }

                    if(response['error']){
                        $("#error-alert-tour-galery").show();
                        $("#error-alert-tour-galery").fadeTo(2000, 500).slideUp(500, function() {
                            $("#error-alert-tour-galery").slideUp(500);
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
<script>
    function myFunction(imgs) {

        // Get the expanded image
        var expandImg = document.getElementById("expandedImg");
        // Get the image text
        var imgText = document.getElementById("imgtext");

        // Use the same src in the expanded image as the image being clicked on from the grid
        expandImg.src = imgs.src;

        // Use the value of the alt attribute of the clickable image as text inside the expanded image
        imgText.innerHTML = imgs.alt;

        document.getElementById("url").value = imgs.alt;


        // Show the container element (hidden with CSS)
        const boxes = document.querySelectorAll('.imgTour');
        boxes.forEach(box => {
            box.style.border = 'none';
        });

        imgs.style.border = "5px solid green";

        expandImg.parentElement.style.display = "none";

    }
</script>
