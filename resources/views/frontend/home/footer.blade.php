<div class="footer">
    <div class="container-fluid">
        <div class="row" style="background-color:white">
            <div class="col-12 text-center">
                <img src="{{ $establecimiento->logo_establecimiento ? $establecimiento->logo_establecimiento->getUrl() : '' }}" class="img-fluid" style="max-height:120px"  />
            </div>
        </div>
        <div class="row">
            <div class="col-12 px-0" style="max-height:45px">
                {!! $totem->texto_inferior !!}
            </div>
        </div>
    </div>
</div>