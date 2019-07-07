<template>

<main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Categorías
                    <button type="button" class="btn btn-success" @click="abrirModal('categoria','registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" id="opcion" name="opcion">
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="categoria in arrayCategoria" :key="categoria.id">
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('categoria','actualizar', categoria)">
                                    <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <button type="button" class="btn btn-danger btn-sm">
                                    <i class="icon-trash"></i>
                                    </button>
                                </td>
                                <td v-text="categoria.nombre"></td>
                                <td v-text="categoria.descripcion"></td>
                                <td>
                                    <div v-if="categoria.condicion">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div>

                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="#">Ant</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">4</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="descripcion" class="form-control" placeholder="Ingrese Descripción">
                                </div>
                            </div>
                            <div v-show="errorCategoria" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMensajeCategoria" :key="error" v-text="error">

                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" v-if="tipoAccion==1" @click="registrarCategoria()">Guardar</button>
                        <button type="button" class="btn btn-success" v-if="tipoAccion==2" @click="actualizarCategoria()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        <!-- Inicio del modal Eliminar -->
        <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-danger" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Eliminar Categoría</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button> 
                    </div>
                    <div class="modal-body">
                        <p>Estas seguro de eliminar la categoría?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-danger">Eliminar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- Fin del modal Eliminar -->
    </main>
</template>

<script>
    export default {
        data (){
            return {
                categoria_id : 0, // Obtenemos el ID de la Categoria a Actualizar
                nombre : '',
                descripcion : '',
                arrayCategoria : [],
                modal : 0, // Valida 0 si no esta abierto el modal de crear registro y 1 si esta abierto dicho modal
                tituloModal : '', // Genera titulo de la vista dinamico
                tipoAccion : 0, // condición para mostrar boton guardar o actualizar.
                errorCategoria : 0,
                errorMostrarMensajeCategoria : []
            }
        },
        methods : {
            // Listar datos en la vista Categoria
            listarCategoria(){
                let me = this; // Indicamos que vamos a utilizar las funciones locales del metodo
                axios.get('/categoria').then(function(response){
                    // Almacenamos todos los datos Recibidos en un Array "arrayCategoria"
                    // Recibiendo todos los datos enviados desde la vista /categoria
                    me.arrayCategoria = response.data;
                    console.log(response);
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            
            registrarCategoria()
            {
                if(this.validarCategoria()){
                    return;
                }

                let me = this; // Indicamos que vamos a utilizar las funciones locales del metodo
                axios.post('/categoria/registrar',{
                    // Almacena los nuevos parametros enviados por medio de esta petición
                    'nombre' : this.nombre,
                    'descripcion' : this.descripcion
                }).then(function(response){
                    //En caso de registrar la categoria, realizara estas dos funciones.
                    me.cerrarModal();
                    me.listarCategoria();
                }).catch(function (error){
                    console.log(error)
                });

            },
        
            actualizarCategoria()
            {
                  if(this.validarCategoria()){
                    return;
                }

                let me = this; // Indicamos que vamos a utilizar las funciones locales del metodo
                axios.put('/categoria/actualizar',{
                    // Almacena los nuevos parametros enviados por medio de esta petición
                    'nombre' : this.nombre,
                    'descripcion' : this.descripcion,
                    'id' : this.categoria_id

                }).then(function(response){
                    //En caso de registrar la categoria, realizara estas dos funciones.
                    me.cerrarModal();
                    me.listarCategoria();
                }).catch(function (error){
                    console.log(error)
                });

            },

            validarCategoria()
            {
                this.errorCategoria = 0;
                this.errorMostrarMensajeCategoria = [];
                //Validaciones
                if(!this.nombre) this.errorMostrarMensajeCategoria.push("El nombre de la Categoria no puede estar Vacío...");
                // Cuando tiene un error de registro, la variable errorCategoria pasa a 1
                if(this.errorMostrarMensajeCategoria.length) this.errorCategoria = 1;

                return this.errorCategoria;
            },

            cerrarModal()
            {
                this.modal=0;
                this.tituloModal = '';
                this.nombre = '';
                this.descripcion = '';
            },
            
            //abrirModal(model, action, data = []){
            abrirModal(modelo, accion, data = [])
            {
                switch(modelo)
                {
                    case "categoria":
                    {
                        switch(accion)
                        {
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Categoría';
                                this.nombre = '';
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                // console.log(data);
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Categoria';
                                this.tipoAccion = 2;
                                
                                this.categoria_id = data['id'];
                                this.nombre = data['nombre'];
                                this.descripcion = data['descripcion'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarCategoria();
        }
    }
</script>

<style>
    .modal-content
    {
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar
    {
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a;

    }
    .div-error
    {
        display: flex;
        justify-content: center;

    }
    .text-error
    {
        color: red !important;
        font-weight: bold;  
    }

</style>