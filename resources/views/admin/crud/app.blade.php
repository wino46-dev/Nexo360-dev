<script>

    var lang_select =  @json($langs ?? []) ;
    
    var crudApp = {
        table_id: '#{{ $table_id }}',
        form_id:'#{{ $form_id }}',
        modal_id: '#{{ $modal_id }}',
        title: '{{ $title }}',
        //store_url: '{{ $store_url ?? "" }}',
        delete_url: '{{ $delete_inline_url ?? "" }}',
        sw_row_new: false,
        action:null,
        row_id: null,
        
        create_modal: function() { 
            this.action = 'create';
            let modal_id = this.modal_id;
            $(this.modal_id).find('.modal-title').html('{{ trans('global.create') }} ' + this.title)
            $(this.modal_id).modal('show');
            $(this.modal_id+'_btn_save').prop('disabled', false);
            
            $(modal_id).find('.modal-body').html('Cargando..');

            $.ajax({
                headers: {'x-csrf-token': _token},
                method: 'GET',
                url: '{{ $url }}/create',
                data: {}
            })
            .done(function (data) {     
                
                $(modal_id).find('.modal-body').html(data);
                
            })
            .fail( function(jqXHR) {                              
                Toast.fire({
                    icon: "error",
                    title: "Error al crear"
                });                    
            })
        },
        edit_modal: function(id) {
            this.action = 'edit';
            this.row_id = id;
            let modal_id = this.modal_id;

            $(this.modal_id).find('.modal-title').html('{{ trans('global.edit') }} ' + this.title)
            $(this.modal_id).modal('show');
            $(this.modal_id+'_btn_save').prop('disabled', false);

            $(modal_id).find('.modal-body').html('Cargando..');

            $.ajax({
                headers: {'x-csrf-token': _token},
                method: 'GET',
                url: '{{ $url }}/'+id+'/edit',
                data: {}
            })
            .done(function (data) {           
                $(modal_id).find('.modal-body').html(data);
                
            })
            .fail( function(jqXHR) {                              
                Toast.fire({
                    icon: "error",
                    title: "Error al modificar"
                });                    
            })
        },
        save_modal: function(){
            
            let $this = this;            
            let form = $(this.form_id);
                                    
            let form_validate = form.validate(); // pasa   

            if (!form_validate.form()) {      
                return false;
            } 
            let url = '{{ $url }}';
            if(this.action == 'edit'){
                url = url + '/'+this.row_id;
            }
            
            
            let data = form.serialize();

            $(this.modal_id+'_btn_save').prop('disabled', true);
            
            $.ajax({
                headers: {'x-csrf-token': _token},
                method: 'POST',
                url: url,
                data: data
            })
            .done(function (data) {           
                $($this.modal_id).modal('hide');
                Toast.fire({
                    icon: "success",
                    title: data.message
                });
                table.ajax.reload(); 
                
            })
            .fail( function(jqXHR, textStatus, errorThrown) {      
                $($this.modal_id+'_btn_save').prop('disabled', false);  
                Toast.fire({
                    icon: "error",
                    title: "Error al guardar"
                });                       
                $this.error_show(jqXHR.responseJSON);     
                                                
            })
        },
        delete: function(id){
            if(!confirm('Are you sure?')){
                return false;
            }
            
            let url = '{{ $url }}/' + id;            

            $.ajax({
                headers: {'x-csrf-token': _token},
                method: 'POST',
                url: url,
                data:{
                    '_method': 'DELETE'
                }
            })
            .done(function (data) {           
                
                Toast.fire({
                    icon: "success",
                    title: data.message
                });
                table.ajax.reload(); 
                
            })
            .fail( function(jqXHR, textStatus, errorThrown) {      
                
                Toast.fire({
                    icon: "error",
                    title: "Error al eliminar"
                });                       
                $this.error_show(jqXHR.responseJSON);     
                                                
            })
        },
        error_show: function(data){            
            console.log(data);
            if(data.message){
                Toast.fire({
                    icon: "error",
                    title: data.message
                }); 
            }
            if(data.errors){
                Object.keys(data.errors).forEach(function (campo) {
                    $("#"+campo).parent().find('.error').remove();
                    data.errors[campo].forEach(function (mensaje) {                            
                        $("#"+campo).parent().append('<div><label class="error">' + mensaje + '</label></div>');
                    });
                });
            }
        },
        create_row: function(){
            if(this.sw_row_new == true){
                return false;
            }
            this.sw_row_new = true;
            let columnas = table.settings().init().columns;
            let tr_html = '<tr class="row_tr_new">';
            columnas.forEach(function (columna) {
                if(columna.name == 'Actions'){
                    tr_html += '<td nowrap class="text-center"><button type="button" class="btn btn-xs btn-success" onclick="crudApp.save_row()">Save</button>'+
                        ' <button type="button" class="btn btn-xs btn-secondary" onclick="crudApp.cancel_row()">Cancel</button>'+
                        '</td>';
                }else if(columna.name == 'created_at' || columna.name == 'updated_at' 
                            || columna.name == 'img' || columna.name == 'image'
                            || columna.name == 'placeholder'){
                    tr_html += '<td> </td>';                
                    
                }else if(columna.name == 'lang.name'){
                    let html_lang = '<select name="lang_id" class="form-control new-input">';
                    lang_select.forEach(function (lang) {
                        html_lang += '<option value="'+lang.id+'">'+lang.name+'</option>';                        
                    });
                    html_lang += '</select>';
                    tr_html += '<td> '+html_lang+'</td>';
                }else if(columna.name == 'idioma_origen' || columna.name == 'idioma_destino' ){
                    let html_lang = '<select name="'+columna.name+'" class="form-control new-input">';
                    lang_select.forEach(function (lang) {
                        html_lang += '<option value="'+lang.code+'">'+lang.name+'</option>';                        
                    });
                    html_lang += '</select>';
                    tr_html += '<td> '+html_lang+'</td>';
                }else{
                    tr_html += '<td><textarea name="'+columna.name+'" class="new-input form-control" style="padding:2px 5px; min-height:70px"></textarea></td>';                    
                }
                
            });
            tr_html += '</tr>';
            $(this.table_id).find('tbody').prepend(tr_html)
            
            $(this.table_id).DataTable().columns.adjust();
            setTimeout(() => {
                $('.row_tr_new textarea:first').focus();
            }, 200);
            
        },
        save_row: function(){
            let $this = this;
            var data_new = {};
            $(this.table_id).find('.new-input').each(function () {                                      
                data_new[$(this).attr('name')] = $(this).val();
            });
            
            $.ajax({
                headers: {'x-csrf-token': _token},
                method: 'POST',
                url: '{{ $url }}',
                data: data_new
            })
            .done(function (data) {           
                
                Toast.fire({
                    icon: "success",
                    title: data.message
                });
                table.ajax.reload(); 
                $this.sw_row_new = false;
                
            })
            .fail( function(jqXHR, textStatus, errorThrown) {      
                
                Toast.fire({
                    icon: "error",
                    title: "Error al guardar"
                });                       
                $this.error_show(jqXHR.responseJSON);     
                                                
            })

        },
        delete_row: function(id){
            
            let $this = this;
            var data_new = {
                id:id
            };
                        
            $.ajax({
                headers: {'x-csrf-token': _token},
                method: 'GET',
                url: this.delete_url,
                data: data_new
            })
            .done(function (data) {           
                
                Toast.fire({
                    icon: "success",
                    title: data.message
                });
                table.ajax.reload(); 
                $this.sw_row_new = false;
                
            })
            .fail( function(jqXHR, textStatus, errorThrown) {      
                
                Toast.fire({
                    icon: "error",
                    title: "Error al guardar"
                });                       
                $this.error_show(jqXHR.responseJSON);     
                                                
            })

        },
        cancel_row: function(){
            this.sw_row_new = false;
            $(this.table_id).find('.row_tr_new').remove();
            $(this.table_id).DataTable().columns.adjust();
        }
    }


    $(document).ready(function () {
               
        
        table.on('draw.dt', function () {            
            if ($('.dataTables_filter').find('.btn_create_row').length == 0) {            
              //  $('.dataTables_filter').append('<button type="button" class="btn btn-sm btn-success ml-2 btn_create_row" onclick="crudApp.create_row()">New Row</button>');            
            }
        });        
        

        $("#{{ $table_id }}").neoDataTableEdit({
            dataTable: table,
            saveUrl: '{{ $url }}/update-inline'
        });
    
    });
</script>