@extends('layouts.admin')

@section('title', 'Crud Helper')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    Crud Helper
                </div>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    Tabla: <input class="form-control" id="table" value="product_category_amazons" />
                    <br />

                    <button type="button" class="btn btn-success" onclick="action_execute(this, 'fields')">
                        Generar array campos
                    </button>
                </div>
                <div class="col-12 mt-2" id="res">

                </div>
            </div>
            
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script>
        function action_execute(btn, action){
            $('#res').html('Loading...');
            let table = $('#table').val();
            console.log(table)
            $.ajax({                
                method: 'GET',
                cache: false, 
                url: '{{url('admin/crud-helper/action') }}',
                data: {
                    'action': action,
                    'table': table
                }            
            })
            .done(function (data) {           
                $('#res').html('<pre>'+data+'</pre>');

            })
            .fail( function(jqXHR) {          
                Toast.fire({
                    icon: "error",
                    title: "Error en " + action
                });                
            });
        }
    </script>

@endsection
