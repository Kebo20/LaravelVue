<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Home</li>
      <li class="breadcrumb-item">
        <a href="#">Admin</a>
      </li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Categorías
          <button
            type="button"
            class="btn btn-secondary"
            @click="abrirModal('categoria','registrar')"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio" @click="listarCategoria(1)">
                  <option value="nombre">Nombre</option>
                  <option value="descripcion">Descripción</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  class="form-control"
                  placeholder="Texto a buscar"
                  @keyup="listarCategoria(1)"
                />
               
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
                  <button
                    type="button"
                    class="btn btn-warning btn-sm"
                    data-toggle="modal"
                    data-target="#modalNuevo"
                    @click="abrirModal('categoria','actualizar',categoria)"
                  >
                    <i class="icon-pencil"></i>
                  </button> &nbsp;
                  <button
                    type="button"
                    class="btn btn-danger btn-sm"
                    @click="abrirModalDesactivar(categoria.id)"
                  >
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
              <li class="page-item" v-if="pagination.current_page>1">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page-1)">Ant</a>
              </li>
              <li class="page-item " v-for="page in pagesNumber" :key="page" :class="[page==isActived ?'active' :'']">
                <a class="page-link" href="#"  @click.prevent="cambiarPagina(page)" v-text="page"></a>
              </li>
              
              <li class="page-item" v-if="pagination.current_page<pagination.last_page">
                <a class="page-link" href="#"  @click.prevent="cambiarPagina(pagination.current_page+1)">Sig</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div
      class="modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none;"
      aria-hidden="true"
      :class="{'mostrar':modal}"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre"
                    class="form-control"
                    placeholder="Nombre de categoría"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                <div class="col-md-9">
                  <input
                    type="email"
                    v-model="descripcion"
                    class="form-control"
                    placeholder="Ingrese descripción"
                  />
                </div>
              </div>
              <div v-show="errorCategoria" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjCategoria" :key="error" v-text="error"></div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button
              type="button"
              class="btn btn-primary"
              v-if="tipoAccion==1"
              @click="registrarCategoria()"
            >Guardar</button>
            <button
              type="button"
              class="btn btn-primary"
              v-if="tipoAccion==2"
              @click="actualizarCategoria()"
            >Actualizar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
    <!-- Inicio del modal Eliminar -->
    <div
      class="modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none;"
      aria-hidden="true"
      :class="{'mostrarDesactivar':modalDesactivar}"
    >
      <div class="modal-dialog modal-danger" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar Categoría</h4>
            <button type="button" class="close" @click="cerrarModalDesactivar()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Estas seguro de eliminar la categoría?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModalDesactivar()">Cerrar</button>
            <button type="button" class="btn btn-danger" @click="desactivarCategoria()">Eliminar</button>
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
  data() {
    return {
      id: "",
      nombre: "",
      descripcion: "",
      arrayCategoria: [],
      modal: 0,
      modalDesactivar: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorCategoria: 0,
      errorMostrarMsjCategoria: [],
      pagination:{
                'total' : 0,
                'current_page': 0,
                'per_page' : 0,
                'last_page' : 0,
                'from' : 0,
                'to' : 0
      },
      offset:3,
      criterio:'nombre',
      buscar:''
    };
  },
  computed:{
    isActived:function(){
      return this.pagination.current_page;
    },
    pagesNumber:function(){
      if(!this.pagination.to){
        return []
      }
      //var from=this.pagination.current_page-this.offset;
     var from=1;
      //var to=pagination.last_page;

      /*if(from <1){
        from=1
      }
      var to= from +(this.offset*2);
      if(to>=this.pagination.last_page){
        to=this.pagination.last_page;
      }*/

      var pagesArray=[];
      while(from<=this.pagination.last_page){
        pagesArray.push(from);
        from++;
      }

      return pagesArray;

    }
  },
  methods: {
    listarCategoria(page) {
      let me = this;
      axios
        .get("/categoria?page="+page+"&buscar="+me.buscar+"&criterio="+me.criterio)
        .then(function(response) {
          me.arrayCategoria = response.data.categorias.data;
          me.pagination=response.data.pagination;
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cambiarPagina(page){
      let me=this;
      me.pagination.current_page=page;
      me.listarCategoria(page);

    },
    registrarCategoria() {
      let me = this;
      if (this.validarCategoria()) {
        return false;
      }
      axios
        .post("/categoria/registrar", {
          nombre: this.nombre,
          descripcion: this.descripcion
        })
        .then(function(response) {
          me.cerrarModal();
          me.buscar='';
          me.criterio='nombre';
          me.listarCategoria(1);
          console.log(response);
          if(response.status=='200'){alert('ok') }else {alert(response.status)};
          
        })
        .catch(function(error) {
          console.log(error);
          alert(error)
        });
    },

    actualizarCategoria() {
      let me = this;
      if (this.validarCategoria()) {
        return false;
      }
      axios
        .put("/categoria/actualizar", {
          id: this.id,
          nombre: this.nombre,
          descripcion: this.descripcion
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarCategoria(me.pagination.current_page);
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    desactivarCategoria() {
      let me = this;
      axios
        .put("/categoria/desactivar", {
          id: this.id
        })
        .then(function(response) {
          me.listarCategoria(me.pagination.current_page);
          me.cerrarModalDesactivar();
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    abrirModalDesactivar(idCategoria) {
      this.modalDesactivar = 1;
      this.id = idCategoria;
    },
    cerrarModalDesactivar() {
      this.modalDesactivar = 0;
      this.id = "";
    },
    validarCategoria() {
      this.errorCategoria = 0;
      this.errorMostrarMsjCategoria = [];
      if (!this.nombre)
        this.errorMostrarMsjCategoria.push("El nombre no puede estar vacío");
        var expresion=/[0-9]/;
    //  if(!expresion.test(this.nombre))  
      //this.errorMostrarMsjCategoria.push("solo numeros");
      if (this.errorMostrarMsjCategoria.length) this.errorCategoria = 1;

      return this.errorCategoria;
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "categoria": {
          switch (accion) {
            case "registrar": {
              this.tipoAccion = 1;
              this.tituloModal = "Registrar";
              this.modal = 1;
              this.nombre = "";
              this.descripcion = "";
              break;
            }
            case "actualizar": {
              this.tipoAccion = 2;
              this.tituloModal = "Actualizar";
              this.modal = 1;
              this.id = data.id;
              this.nombre = data.nombre;
              this.descripcion = data.descripcion;
            }
          }
        }
      }
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.nombre = "";
      this.descripcion = "";
    }
  },
  mounted() {
    this.listarCategoria(1);
  }
};
</script>
<style >
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}
.mostrarDesactivar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}

.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red;
  font-weight: bold;
}
</style>
