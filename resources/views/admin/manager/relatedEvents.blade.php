<!-- Button trigger modal -->
<button style="float: right" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
    Logs de sesión
</button>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Eventos lanzados en la sesion actual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                     <table class="table">
                         <thead>
                         <tr>
                             <th scope="col">ID</th>
                             <th scope="col">Sesión ID</th>
                             <th scope="col">Tipo</th>
                             <th scope="col">Canal</th>
                             <th scope="col">Objeto</th>
                             <th scope="col">Creado</th>
                         </tr>
                         </thead>
                         <tbody>

                                 @foreach($control_sesion_actual as $logactual)
                                     <tr>
                                         <th scope="row">{{$logactual->id}}</th>
                                         <th scope="row">{{$logactual->sesion_id}}</th>
                                         <td>{{$logactual->tipo_evento->nombre}}</td>
                                         <td>{{$logactual->canal_transmision}}</td>
                                         <td>{{$logactual->objeto}}</td>
                                         <td>{{$logactual->created_at}}</td>

                                     </tr>

                                 @endforeach

                         </tbody>
                     </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
