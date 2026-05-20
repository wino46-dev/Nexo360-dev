<div class="card divcall" id="divcall" style="border:none;">

        @if(! empty($caller->id))
                <iframe id="iframeid" allowusermedia src="https://assist.guest-in.es/#/?id={{$caller->id}}" style="border:none !important;" width="100%"  allow="camera; microphone; autoplay; fullscreen" >
        @endif

        </iframe>
</div>
<script>
	var width = window.innerWidth;
	var height = window.innerHeight;
	document.getElementById('iframeid').style.height = height * 0.33;
	document.getElementById('iframeid').style.minHeight = height * 0.33;

</script>




