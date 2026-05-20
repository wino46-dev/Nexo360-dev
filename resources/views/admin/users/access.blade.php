@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Accesos de {{ $user->name }} (Sociedades y Hoteles)
    </div>
    <div class="card-body">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('admin.users.access.update', $user->id) }}">
            @csrf

            <p class="text-muted">Selecciona una sociedad por bloque. Puedes añadir más sociedades con el botón "Añadir sociedad". Los hoteles son dependientes de la sociedad del propio bloque.</p>

            <div id="societyBlocks">
                @php($idx = 0)
                @if(count($selectedSocieties) > 0)
                    @foreach($selectedSocieties as $sid)
                        @php($idx++)
                        <div class="society-block border rounded p-3 mb-3" data-index="{{ $idx }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Sociedad {{ $idx }}</h6>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-block">Quitar</button>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Sociedad</label>
                                    <select class="form-control select2 society-select" name="societies[]" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($societies as $id => $name)
                                            <option value="{{ $id }}" {{ (int)$id === (int)$sid ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Hoteles de la sociedad</label>
                                    <select class="form-control select2 hotels-select" name="hotels[]" multiple {{ $sid ? '' : 'disabled' }}>
                                        @foreach($hotels as $hotel)
                                            @if((int)$hotel->sociedad_id === (int)$sid)
                                                <option value="{{ $hotel->id }}" {{ in_array($hotel->id, $selectedHotels) ? 'selected' : '' }}>{{ $hotel->nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Debes elegir una sociedad para poder seleccionar hoteles.</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    @php($idx = 1)
                    <div class="society-block border rounded p-3 mb-3" data-index="1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Sociedad 1</h6>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-block" style="display:none">Quitar</button>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Sociedad</label>
                                <select class="form-control select2 society-select" name="societies[]">
                                    <option value="">-- Seleccione --</option>
                                    @foreach($societies as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Hoteles de la sociedad</label>
                                <select class="form-control select2 hotels-select" name="hotels[]" multiple disabled>
                                </select>
                                <small class="form-text text-muted">Debes elegir una sociedad para poder seleccionar hoteles.</small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <button type="button" id="addSocietyBlock" class="btn btn-outline-primary">Añadir sociedad</button>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-danger">{{ trans('global.save') }}</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ trans('global.back') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    // Build hotels list for client-side filtering
    var hotelsList = @json($hotels->map(function($h){ return ['id'=>$h->id,'nombre'=>$h->nombre,'sociedad_id'=>$h->sociedad_id]; }));

    function hotelsForSociety(sid){
        sid = String(sid);
        return hotelsList.filter(function(h){ return String(h.sociedad_id) === sid; });
    }

    function populateHotelsSelect($block){
        var sid = $block.find('.society-select').val();
        var $hotels = $block.find('.hotels-select');
        $hotels.empty();
        if(!sid){
            $hotels.prop('disabled', true);
            return;
        }
        var options = hotelsForSociety(sid);
        options.forEach(function(h){
            var pre = $hotels.data('preselected');
            var selected = Array.isArray(pre) && pre.indexOf(String(h.id)) !== -1;
            var opt = new Option(h.nombre, h.id, selected, selected);
            $hotels.append(opt);
        });
        $hotels.prop('disabled', false).trigger('change');
        // Preselection is only for the initial render of the block
        $hotels.removeData('preselected');
    }

    function isSocietyUsed(sid, exceptBlock){
        var used = false;
        document.querySelectorAll('#societyBlocks .society-block').forEach(function(block){
            if(exceptBlock && block === exceptBlock) return;
            var val = block.querySelector('.society-select').value;
            if(val && String(val) === String(sid)) used = true;
        });
        return used;
    }

    function refreshRemoveButtons(){
        var blocks = document.querySelectorAll('#societyBlocks .society-block');
        blocks.forEach(function(b, idx){
            b.querySelector('h6').textContent = 'Sociedad ' + (idx+1);
            var btn = b.querySelector('.remove-block');
            btn.style.display = (blocks.length > 1) ? '' : 'none';
        });
    }

    function attachBlockHandlers(block){
        var $block = $(block);
        // Initialize Select2 if available
        if($.fn.select2){
            $block.find('.select2').select2({ width: '100%' });
        }
        $block.find('.society-select').on('change', function(){
            var selectedSid = this.value;
            if(selectedSid && isSocietyUsed(selectedSid, block)){
                alert('Esta sociedad ya está seleccionada en otro bloque.');
                this.value = '';
                if($.fn.select2){ $(this).trigger('change.select2'); }
                $block.find('.hotels-select').empty().prop('disabled', true);
                return;
            }
            // When society changes, clear hotels of this block
            $block.find('.hotels-select').val([]);
            populateHotelsSelect($block);
        });
        $block.find('.remove-block').on('click', function(){
            $(block).remove();
            refreshRemoveButtons();
        });

        // Initial populate based on existing value
        // Preserve any server-rendered selected hotels before repopulating
        var currentSelected = ($block.find('.hotels-select').val() || []).map(String);
        $block.find('.hotels-select').data('preselected', currentSelected);
        populateHotelsSelect($block);
    }

    document.addEventListener('DOMContentLoaded', function(){
        // Attach to existing blocks
        document.querySelectorAll('#societyBlocks .society-block').forEach(function(block){ attachBlockHandlers(block); });
        refreshRemoveButtons();

        document.getElementById('addSocietyBlock').addEventListener('click', function(){
            // Create new block
            var societies = @json($societies);
            var societiesOptions = '<option value="">-- Seleccione --</option>';
            Object.entries(societies).forEach(function(entry){
                var id = entry[0], name = entry[1];
                societiesOptions += '<option value="'+id+'">'+name+'</option>';
            });

            var blockHtml = '\
            <div class="society-block border rounded p-3 mb-3">\
                <div class="d-flex justify-content-between align-items-center mb-2">\
                    <h6 class="mb-0">Sociedad</h6>\
                    <button type="button" class="btn btn-sm btn-outline-danger remove-block">Quitar</button>\
                </div>\
                <div class="row">\
                    <div class="form-group col-md-6">\
                        <label>Sociedad</label>\
                        <select class="form-control select2 society-select" name="societies[]">'+societiesOptions+'<\/select>\
                    </div>\
                    <div class="form-group col-md-6">\
                        <label>Hoteles de la sociedad</label>\
                        <select class="form-control select2 hotels-select" name="hotels[]" multiple disabled><\/select>\
                        <small class="form-text text-muted">Debes elegir una sociedad para poder seleccionar hoteles.<\/small>\
                    </div>\
                </div>\
            </div>';

            var container = document.getElementById('societyBlocks');
            var temp = document.createElement('div');
            temp.innerHTML = blockHtml.trim();
            var block = temp.firstChild;
            container.appendChild(block);
            attachBlockHandlers(block);
            refreshRemoveButtons();
        });
    });
</script>
@endsection
