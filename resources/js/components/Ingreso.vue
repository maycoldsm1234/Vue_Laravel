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
                    <i class="fa fa-align-justify"></i> Ingresos
                    <button type="button" class="btn btn-success" @click="mostrarDetalle()">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <!-- listado -->
                <template v-if="listado">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" id="opcion" name="opcion" v-model="criterio">
                                        <option value="tipo_comprobante">Tipo De Comprobante</option>
                                        <option value="num_comprobante">Número De Comprobante</option>
                                        <option value="fecha_hora">Fecha y Hora</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" class="btn btn-primary" @click="listarIngreso(1,buscar,criterio)"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-responsive table-bordered table-striped table-sm col-md-12">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Usuario</th>
                                    <th>Proveedor</th>
                                    <th>Tipo Comprobante</th>
                                    <th>Serie Comprobante</th>
                                    <th>Num Comprobante</th>
                                    <th>Fecha y Hora</th>
                                    <th>Total</th>
                                    <th>Impuesto</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('ingreso','actualizar', ingreso)">
                                        <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="ingreso.estado=='Registrado'">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarUsuario(ingreso.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="ingreso.usuario"></td>
                                    <td v-text="ingreso.nombre"></td>
                                    <td v-text="ingreso.tipo_comprobante"></td>
                                    <td v-text="ingreso.serie_comprobante"></td>
                                    <td v-text="ingreso.num_comprobante"></td>
                                    <td v-text="ingreso.fecha_hora"></td>
                                    <td v-text="ingreso.total"></td>
                                    <td v-text="ingreso.impuesto"></td>
                                    <td v-text="ingreso.estado"></td>
                                    
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
                </template>
            
                <!-- Fin listado -->
                <!-- div para el detalle del listado -->
                <template v-else>
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor(*)</label>
                                    <v-select
                                        :on-search="selectProveedor"
                                        label="nombre"
                                        :options="arrayProveedor"
                                        placeholder="Buscar Proveedores..."
                                        :onChange="getDatosProveedor"
                                    >
                                     
                                    </v-select> 
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto</label>
                                <input type="text" class="form-control" v-model="impuesto">
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Tipo Comprobante(*)</label>
                                    <select name="" id="" class="form-control" v-model="tipo_comprobante">
                                        <option value="0">Seleccionar Opción</option>
                                        <option value="Boleta">Boleta</option>
                                        <option value="Factura">Factura</option>
                                        <option value="Ticket">Ticket</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Serie De Comprobante</label>
                                    <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Número De Comprobante</label>
                                    <input type="text" class="form-control" v-model="num_comprobante" placeholder="000x">
                                </div>
                            </div>

                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Articulo <span style="color: red;" v-show="idarticulo==0">(*Seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" v-model="codigo" @keyup.enter="buscarArticulo()" class="form-control" placeholder="Ingrese Articulo">
                                        <button class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="articulo">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Precio <span style="color: red;" v-show="precio==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="precio">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Cantidad <span style="color: red;" v-show="cantidad==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" class="form-control" v-model="cantidad">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="text" class="btn btn-success form-control btnagregar" @click="agregarDetalle()">
                                        <i class="icon-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>SubTotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" @click="eliminarDetalle(index)">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.articulo"></td>
                                            <td>
                                                <input type="number" value="3" name="" id="" class="form-control" v-model="detalle.precio">
                                            </td>
                                            <td>
                                                <input type="number" value="3" name="" id="" class="form-control" v-model="detalle.cantidad">
                                            </td>
                                            <td>
                                                {{ detalle.precio * detalle.cantidad }}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right">
                                                <strong>Total Parcial:</strong>
                                            </td>
                                            <td>$ {{ totalParcial = ( total - totalImpuesto ).toFixed(2) }} </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right">
                                                <strong>Total Impuesto:</strong>
                                            </td>
                                            <td>$ {{ totalImpuesto = (( total * impuesto ) / (1 + impuesto )).toFixed(2) }} </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right">
                                                <strong>Total Neto:</strong>
                                            </td>
                                            <td>$ {{ total=calcularTotal }} </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay Articulos Agregados...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-danger" @click="ocultarDetalle()">Cerrar</button>
                                    <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar Compra</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- FIN - div para el detalle del listado -->

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
    import vSelect from 'vue-select';
    export default 
    {
        data ()
        {
            return {
                ingreso_id: 0,
                idproveedor : 0,
                idarticulo: 0,
                nombre : '',
                tipo_comprobante : 'Boleta',
                serie_comprobante : '',
                num_comprobante: '',
                impuesto : 0.18,
                total : 0.0,
                totalImpuesto : 0.0,
                totalParcial : 0.0,
                arrayIngreso : [],
                arrayProveedor: [],
                arrayDetalle : [],
                listado: 1, // Si var listado = 1 muestra listado, sino oculta listado y muestra formulario detalle
                modal : 0, // Valida 0 si no esta abierto el modal de crear registro y 1 si esta abierto dicho modal
                tituloModal : '', 
                tipoAccion : 0, // condición para mostrar boton guardar o actualizar.
                errorIngreso : 0,
                errorMostrarMensajeIngreso : [],
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
                criterio : 'num_comprobante', // Inicializamos criterio de busqueda
                buscar : '',
                arrayArticulo: [],
                idarticulo: 0,
                codigo: '',
                articulo: '',
                precio: 0,
                cantidad: 0
            }
        },
        components: {
            vSelect 
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
            },
            calcularTotal: function()
            {
                var resultado = 0.0;
                for(var i=0;i<this.arrayDetalle.length;i++)
                {
                    resultado = resultado + (this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad);
                }
                return resultado;
            }
        },
        methods : 
        {
            // Listar datos en la vista Categoria
            listarIngreso(page, buscar, criterio){
                let me = this;
                var url = '/ingreso?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){

                    // Almacenamos todos los datos Recibidos en un Array "arrayCategoria"
                    // Recibiendo todos los datos enviados desde la vista /categoria
                    var respuesta = response.data;
                    me.arrayIngreso = respuesta.ingresos.data;
                    me.pagination = respuesta.pagination;
                    //console.log(response);
                })
                .catch(function(error){
                    console.log(error);
                });
            },

            //
            selectProveedor(search,loading)
            {
                let me=this;
                loading(true)
                console.log("success");
                var url = '/proveedor/selectProveedor?filtro='+search;
                axios.get(url).then(function(response){
                    let respuesta = response.data;
                    q: search
                    me.arrayProveedor = respuesta.proveedores;
                    //console.log("success");
                    loading(false)
                    //console.log(response);
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            //
            getDatosProveedor(val1)
            {
                // Esta función recibe el ID del proveedor Seleccionado
                let me = this;
                me.loading = true;
                me.idproveedor = val1.id;

            },
            //
            buscarArticulo()
            {
                let me = this;
                var url = '/articulo/buscarArticulo?filtro=' + me.codigo;
                axios.get(url).then(function(response){
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.articulos;

                    if(me.arrayArticulo.length > 0)
                    {
                        me.articulo = me.arrayArticulo[0]['nombre'];
                        me.idarticulo = me.arrayArticulo[0]['id'];
                    }
                    else
                    {
                        me.articulo = 'No existe Ningun articulo';
                        me.idarticulo = 0;
                    }
                });
            },

            //
            cambiarPagina(page, buscar, criterio)
            {
                let me = this;
                // Actualiza la pagina Actual
                me.pagination.current_page = page;
                // Envia la petición para visualizar la data de la pagina
                me.listarIngreso(page,buscar,criterio);

            },

            //
            encuentra(id)
            {
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++)
                {
                    if(this.arrayDetalle[i].idarticulo==id)
                    {
                        sw=true;
                    }
                }
                return sw;
            },

            //
            eliminarDetalle(index)
            {
                let me = this;
                me.arrayDetalle.splice(index, 1);
            },
            
            //
            agregarDetalle()
            {
                let me = this;
                if(me.idarticulo == 0 || me.cantidad == 0 || me.precio == 0)
                {
                    // si se cumple esta condición, no agrega el producto
                }
                else
                {
                    if(me.encuentra(me.idarticulo))
                    {
                        swal({
                            type:  'error',
                            title: 'Error...',
                            text:  'Este Articulo ya ha sido agregado anteriormente...',
                        })
                    }
                    else
                    {
                        me.arrayDetalle.push({
                        idarticulo: me.idarticulo,
                        articulo: me.articulo,
                        cantidad: me.cantidad,
                        precio: me.precio
                        });

                        me.codigo = "";
                        me.idarticulo = 0;
                        me.articulo = "";
                        me.cantidad = 0;
                        me.precio = 0;

                    }
                    
                }

                
            },

            //
            registrarIngreso()
            {
                if(this.validarIngreso()){
                    return;
                }

                let me = this; // Indicamos que vamos a utilizar las funciones locales del metodo
                axios.post('/ingreso/registrar',{
                    // Almacena los nuevos parametros enviados por medio de esta petición
                    'nombre' : this.nombre,
                    'tipo_documento' : this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email,
                    'password' : this.password,
                    'usuario' : this.usuario,
                    'idrol' : this.idrol
                }).then(function(response){
                    //En caso de registrar la categoria, realizara estas dos funciones.
                    me.cerrarModal();
                    me.listarIngreso(1, '', 'nombre');
                }).catch(function (error){
                    console.log(error)
                });

            },
        
            //
            actualizarIngreso()
            {
                  if(this.validarIngreso()){
                    return;
                }

                let me = this; // Indicamos que vamos a utilizar las funciones locales del metodo
                axios.put('/ingreso/actualizar',{
                    // Almacena los nuevos parametros enviados por medio de esta petición
                    'nombre' : this.nombre,
                    'tipo_documento' : this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email,
                    'usuario' : this.usuario,
                    'password' : this.password,
                    'idrol' : this.idrol,
                    'id' : this.persona_id

                }).then(function(response){
                    //En caso de registrar la categoria, realizara estas dos funciones.
                    me.cerrarModal();
                    me.listarIngreso(1,'','nombre');
                }).catch(function (error){
                    console.log(error)
                });

            },

            //
            desactivarUsuario(id)
            {
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de desactivar este Usuario?',
                text: "Por favor confirma si es correcto...",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this; 
                    axios.put('/user/desactivar',{
                        'id' : id

                    }).then(function(response){
                        me.listarPersona(1, '', 'nombre');

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
            activarUsuario(id)
            {

                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de Activar este Usuario?',
                text: "Por favor confirma si es correcto...",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this; 
                    axios.put('/user/activar',{
                        'id' : id

                    }).then(function(response){
                        me.listarPersona(1, '', 'nombre');

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
            validarIngreso()
            {
                this.errorIngreso = 0;
                this.errorMostrarMensajeIngreso = [];
                //Validaciones
                if(!this.nombre) this.errorMostrarMensajeIngreso.push("El nombre de la Persona no puede estar Vacío...");
                if(this.errorMostrarMensajeIngreso.length) this.errorIngreso = 1;

                return this.errorIngreso;
            },
            //
            mostrarDetalle()
            {
                this.listado = 0;
            },
            //
            ocultarDetalle()
            {
                this.listado = 1;
            },

            //
            cerrarModal()
            {
                this.modal=0;
                this.tituloModal = '';
                this.nombre = '';
                this.tipo_documento = 'DNI';
                this.num_documento = '';
                this.direccion = '';
                this.telefono = '';
                this.email = '';
                this.usuario = '';
                this.password = '';
                this.idrol = '';
                this.errorPersona = 0;

            },
            
            //abrirModal(model, action, data = []){
            abrirModal(modelo, accion, data = [])
            {
                this.selectRol();

                switch(modelo)
                {
                    case "ingreso":
                    {
                        switch(accion)
                        {
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Ingreso Pedido';
                                this.nombre = '';
                                this.tipo_documento = 'DNI';
                                this.num_documento = '';
                                this.direccion = '';
                                this.telefono = '';
                                this.email = '';
                                this.usuario = '';
                                this.password = '';
                                this.idrol = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                // console.log(data);
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Ingreso Pedido';
                                this.tipoAccion = 2;
                                
                                this.persona_id = data['id'];
                                this.nombre = data['nombre'];
                                this.tipo_documento = data['tipo_documento'];
                                this.num_documento = data['num_documento'];
                                this.direccion = data['direccion'];
                                this.telefono = data['telefono'];
                                this.email = data['email'];
                                this.usuario = data['usuario'];
                                this.password = data['password'];
                                this.idrol = data['idrol'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarIngreso(1,this.buscar,this.criterio);
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

    @media(min-width: 600px)
    {
        .btnagregar
        {
            margin-top: 2rem;
        }
    }
</style>