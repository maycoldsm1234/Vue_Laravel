<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>        </ol>
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
                                <select class="form-control col-md-3" id="opcion" name="opcion" v-model="criterio">
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarCategoria(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary" @click="listarCategoria(1,buscar,criterio)"><i class="fa fa-search"></i> Buscar</button>
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
                                    <template v-if="categoria.condicion">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarCategoria(categoria.id)">
                                        <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="activarCategoria(categoria.id)">
                                        <i class="icon-check"></i>
                                        </button>
                                    </template>
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
                        <ul class="pagination mt-2 mx-auto">
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
                        <button type="button" class="btn btn-danger" @click="cerrarModal()">Cerrar</button>
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
                categoria_id : 0, // Obtenemos el ID de la Categoria a Actualizar
                nombre : '',
                descripcion : '',
                arrayCategoria : [],
                modal : 0, // Valida 0 si no esta abierto el modal de crear registro y 1 si esta abierto dicho modal
                tituloModal : '', // Genera titulo de la vista dinamico
                tipoAccion : 0, // condición para mostrar boton guardar o actualizar.
                errorCategoria : 0,
                errorMostrarMensajeCategoria : [],
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
            listarCategoria(page, buscar, criterio){
                let me = this;
                var url = '/categoria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){

                    // Almacenamos todos los datos Recibidos en un Array "arrayCategoria"
                    // Recibiendo todos los datos enviados desde la vista /categoria
                    var respuesta = response.data;
                    me.arrayCategoria = respuesta.categorias.data;
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
                me.listarCategoria(page,buscar,criterio);

            },

            //
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
                    me.listarCategoria(1, '', 'nombre');
                }).catch(function (error){
                    console.log(error)
                });

            },
        
            //
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
                    me.listarCategoria(1,'','nombre');
                }).catch(function (error){
                    console.log(error)
                });

            },

            //
            desactivarCategoria(id)
            {
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de desactivar esta categoria?',
                text: "Por favor confirma si es correcto...",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this; 
                    axios.put('/categoria/desactivar',{
                        'id' : id

                    }).then(function(response){
                        me.listarCategoria(1, '', 'nombre');

                        //  Evento que se ejecuta después de Actualizar la columna Condición.    
                        swalWithBootstrapButtons.fire(
                        'Desactivado!',
                        'El registro ha sido desactivado Exitosamente.',
                        'success'
                        )
                    }).catch(function (error){
                        console.log(error)
                    });

                } else if (
                    // Read more about handling dismissals  
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Your imaginary file is safe :)',
                    'error'
                    )
                }
                })
            },

            //
            activarCategoria(id)
            {

                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de Activar esta categoria?',
                text: "Por favor confirma si es correcto...",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this; 
                    axios.put('/categoria/activar',{
                        'id' : id

                    }).then(function(response){
                        me.listarCategoria(1, '', 'nombre');

                        //  Evento que se ejecuta después de Actualizar la columna Condición.    
                        swalWithBootstrapButtons.fire(
                        'Activado!',
                        'El registro ha sido Activado Exitosamente.',
                        'success'
                        )
                    }).catch(function (error){
                        console.log(error)
                    });

                } else if (
                    // Read more about handling dismissals  
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Your imaginary file is safe :)',
                    'error'
                    )
                }
                })
            },

            //
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

            //
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
            this.listarCategoria(1,this.buscar,this.criterio);
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