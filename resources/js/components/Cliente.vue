<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>        
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Personas
                    <button type="button" class="btn btn-success" @click="abrirModal('persona','registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" id="opcion" name="opcion" v-model="criterio">
                                <option value="nombre">Nombre</option>
                                <option value="num_documento">Documento</option>
                                <option value="email">Email</option>
                                <option value="telefono">Telefono</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarPersona(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary" @click="listarPersona(1,buscar,criterio)"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Tipo Documento</th>
                                <th>Numero Doc</th>
                                <th>Dirección</th>
                                <th>Telefono</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="persona in arrayPersona" :key="persona.id">
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('persona','actualizar', persona)">
                                    <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                </td>
                                <td v-text="persona.nombre"></td>
                                <td v-text="persona.tipo_documento"></td>
                                <td v-text="persona.num_documento"></td>
                                <td v-text="persona.direccion"></td>
                                <td v-text="persona.telefono"></td>
                                <td v-text="persona.email"></td>
                                
                            </tr>
                            
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" @v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page -1,buscar,criterio)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                            </li>
                            <li class="page-item" @v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
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
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre Completo">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Tipo Documento</label>
                                <div class="col-md-9">
                                    <select v-model="tipo_documento" class="form-control">
                                        <option value="CC">CC</option>
                                        <option value="PASS">PASS</option>
                                        <option value="DNI">DNI</option>
                                        <option value="RUC">RUC</option>
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Número Documento</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="num_documento" class="form-control" placeholder="Ingrese Número Doc">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Dirección</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="direccion" class="form-control" placeholder="Ingrese Dirección">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Telefono</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="telefono" class="form-control" placeholder="Ingrese Telefono">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="email" class="form-control" placeholder="Ingrese Email">
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
                        <button type="button" class="btn btn-danger" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" v-if="tipoAccion==1" @click="registrarPersona()">Guardar</button>
                        <button type="button" class="btn btn-success" v-if="tipoAccion==2" @click="actualizarPersona()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        <!-- Inicio del modal Eliminar -->
       
                <!-- /.modal-content -->
            <!-- /.modal-dialog -->
        <!-- Fin del modal Eliminar -->
    </main>
</template>

<script>
    export default 
    {
        data ()
        {
            return {
                persona_id: 0,
                nombre : '',
                tipo_documento : 'CC',
                num_documento : '',
                direccion : '',
                telefono: '',
                email : '', 
                arrayPersona : [],
                modal : 0, // Valida 0 si no esta abierto el modal de crear registro y 1 si esta abierto dicho modal
                tituloModal : '', 
                tipoAccion : 0, // condición para mostrar boton guardar o actualizar.
                errorPersona : 0,
                errorMostrarMensajePersona : [],
                // Propiedades para la paginación
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : '' 
            }
        },
        computed : {
            isActived: function()
            {
                return this.pagination.current_page;
            },
            // Calcula los elementos de la paginación
            pagesNumber: function()
            {
                if(!this.pagination.to)
                {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1)
                {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page)
                {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to)
                {
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray; 
            }
        },
        methods : 
        {
            // Listar datos en la vista Categoria
            listarPersona(page, buscar, criterio){
                let me = this;
                var url = '/cliente?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){

                    // Almacenamos todos los datos Recibidos en un Array "arrayCategoria"
                    // Recibiendo todos los datos enviados desde la vista /categoria
                    var respuesta = response.data;
                    me.arrayPersona = respuesta.personas.data;
                    me.pagination = respuesta.pagination;
                    //console.log(response);
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            
            //
            cambiarPagina(page, buscar, criterio)
            {
                let me = this;
                // Actualiza la pagina Actual
                me.pagination.current_page = page;
                // Envia la petición para visualizar la data de la pagina
                me.listarPersona(page,buscar,criterio);

            },

            //
            registrarPersona()
            {
                if(this.validarPersona()){
                    return;
                }

                let me = this; // Indicamos que vamos a utilizar las funciones locales del metodo
                axios.post('/cliente/registrar',{
                    // Almacena los nuevos parametros enviados por medio de esta petición
                    'nombre' : this.nombre,
                    'tipo_documento' : this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email
                }).then(function(response){
                    //En caso de registrar la categoria, realizara estas dos funciones.
                    me.cerrarModal();
                    me.listarPersona(1, '', 'nombre');
                }).catch(function (error){
                    console.log(error)
                });

            },
        
            //
            actualizarPersona()
            {
                  if(this.validarPersona()){
                    return;
                }

                let me = this; // Indicamos que vamos a utilizar las funciones locales del metodo
                axios.put('/cliente/actualizar',{
                    // Almacena los nuevos parametros enviados por medio de esta petición
                    'nombre' : this.nombre,
                    'tipo_documento' : this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email,
                    'id' : this.persona_id

                }).then(function(response){
                    //En caso de registrar la categoria, realizara estas dos funciones.
                    me.cerrarModal();
                    me.listarPersona(1,'','nombre');
                }).catch(function (error){
                    console.log(error)
                });

            },

          
            //
            validarPersona()
            {
                this.errorPersona = 0;
                this.errorMostrarMensajePersona = [];
                //Validaciones
                if(!this.nombre) this.errorMostrarMensajePersona.push("El nombre de la Persona no puede estar Vacío...");
                // Cuando tiene un error de registro, la variable errorCategoria pasa a 1
                if(this.errorMostrarMensajePersona.length) this.errorPersona = 1;

                return this.errorPersona;
            },

            //
            cerrarModal()
            {
                this.modal=0;
                this.tituloModal = '';
                this.nombre = '';
                this.tipo_documento = '';
                this.num_documento = '';
                this.direccion = '';
                this.telefono = '';
                this.email = '';
            },
            
            //abrirModal(model, action, data = []){
            abrirModal(modelo, accion, data = [])
            {
                switch(modelo)
                {
                    case "persona":
                    {
                        switch(accion)
                        {
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Persona';
                                this.nombre = '';
                                this.tipo_documento = 'DNI';
                                this.num_documento = '';
                                this.direccion = '';
                                this.telefono = '';
                                this.email = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                // console.log(data);
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Persona';
                                this.tipoAccion = 2;
                                
                                this.persona_id = data['id'];
                                this.nombre = data['nombre'];
                                this.tipo_documento = data['tipo_documento'];
                                this.num_documento = data['num_documento'];
                                this.direccion = data['direccion'];
                                this.telefono = data['telefono'];
                                this.email = data['email'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarPersona(1,this.buscar,this.criterio);
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