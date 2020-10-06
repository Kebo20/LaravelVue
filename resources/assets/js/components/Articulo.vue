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
          <i class="fa fa-align-justify"></i> Articulos
          <button
            type="button"
            class="btn btn-secondary"
            @click="abrirModal('Articulo','registrar')"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio" @click="listarArticulo(1)">
                  <option value="nombre">Nombre</option>
                  <option value="descripcion">Descripción</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  class="form-control"
                  placeholder="Texto a buscar"
                  @keyup="listarArticulo(1)"
                />
              </div>
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Opciones</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Stock</th>
                <th>Precio venta</th>
                <th>Categoría</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                <td>
                  <button
                    type="button"
                    class="btn btn-warning btn-sm"
                    data-toggle="modal"
                    data-target="#modalNuevo"
                    @click="abrirModal('Articulo','actualizar',articulo)"
                  >
                    <i class="icon-pencil"></i>
                  </button> &nbsp;
                  <button
                    type="button"
                    class="btn btn-danger btn-sm"
                    @click="abrirModalDesactivar(articulo.id)"
                  >
                    <i class="icon-trash"></i>
                  </button>
                </td>
                <td v-text="articulo.codigo"></td>
                <td v-text="articulo.nombre"></td>
                <td v-text="articulo.descripcion"></td>
                <td v-text="articulo.stock"></td>
                <td v-text="articulo.precio_venta"></td>
                <td v-text="articulo.nombre_categoria"></td>

                <td>
                  <div v-if="articulo.condicion">
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
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page-1)"
                >Ant</a>
              </li>
              <li
                class="page-item"
                v-for="page in pagesNumber"
                :key="page"
                :class="[page==isActived ?'active' :'']"
              >
                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
              </li>

              <li class="page-item" v-if="pagination.current_page<pagination.last_page">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page+1)"
                >Sig</a>
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
                <label class="col-md-3 form-control-label" for="text-input">Código</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="codigo"
                    class="form-control"
                    placeholder="Nombre de categoría"
                  />
                  <barcode :value="codigo" :options="{format: 'EAN-13'}">Generando código de barras</barcode>
                </div>
                
                 
               </div>
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
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Stock</label>
                <div class="col-md-9">
                  <input
                    type="email"
                    v-model="stock"
                    class="form-control"
                    placeholder="Ingrese descripción"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Precio de venta</label>
                <div class="col-md-9">
                  <input
                    type="email"
                    v-model="precio_venta"
                    class="form-control"
                    placeholder="Ingrese descripción"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Categoria</label>
                <div class="col-md-9">
                  <select v-model="categoria" class="select-search form-control">
                    <option value="0" >Seleccionar</option>
                    <option
                      v-for="cat in arrayCategoria"
                      :key="cat.id"
                      :value="cat.id"
                      :selected="cat.id == categoria"
                      v-text="cat.nombre"
                    ></option>
                  </select>
                  
                </div>
              </div>
              <div v-show="errorArticulo" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error"></div>
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
              @click="registrarArticulo()"
            >Guardar</button>
            <button
              type="button"
              class="btn btn-primary"
              v-if="tipoAccion==2"
              @click="actualizarArticulo()"
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
            <button type="button" class="btn btn-danger" @click="desactivarArticulo()">Eliminar</button>
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
import VueBarcode from 'vue-barcode';
export default {
  data() {
    return {
      id: "",
      codigo: "",
      nombre: "",
      descripcion: "",
      precio_venta: "",
      stock: "",
      categoria: 0,
      arrayCategoria: [],
      arrayArticulo: [],
      modal: 0,
      modalDesactivar: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorArticulo: 0,
      errorMostrarMsjArticulo: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "nombre",
      buscar: ""
    };
  },
  components: {
    'barcode': VueBarcode
  },
  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }
      //var from=this.pagination.current_page-this.offset;
      var from = 1;
      //var to=pagination.last_page;

      /*if(from <1){
        from=1
      }
      var to= from +(this.offset*2);
      if(to>=this.pagination.last_page){
        to=this.pagination.last_page;
      }*/

      var pagesArray = [];
      while (from <= this.pagination.last_page) {
        pagesArray.push(from);
        from++;
      }

      return pagesArray;
    }
  },
  methods: {
    listarArticulo(page) {
      let me = this;
      axios
        .get(
          "/articulo?page=" +
            page +
            "&buscar=" +
            me.buscar +
            "&criterio=" +
            me.criterio
        )
        .then(function(response) {
          me.arrayArticulo = response.data.articulos.data;
          me.pagination = response.data.pagination;
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    listarCategoria() {
      let me = this;
      axios
        .get("/categoria/listar")
        .then(function(response) {
          me.arrayCategoria = response.data;

          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cambiarPagina(page) {
      let me = this;
      me.pagination.current_page = page;
      me.listarArticulo(page);
    },
    registrarArticulo() {
      let me = this;
      if (this.validarArticulo()) {
        return false;
      }
      axios
        .post("/articulo/registrar", {
          nombre: this.nombre,
          codigo: this.codigo,
          stock: this.stock,
          precio_venta: this.precio_venta,
          idcategoria: this.categoria,
          descripcion: this.descripcion
        })
        .then(function(response) {
          me.cerrarModal();
          me.buscar = "";
          me.criterio = "nombre";
          me.listarArticulo(1);
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    actualizarArticulo() {
      let me = this;
      if (this.validarArticulo()) {
        return false;
      }
      axios
        .put("/articulo/actualizar", {
          id: this.id,
          nombre: this.nombre,
          codigo: this.codigo,
          stock: this.stock,
          precio_venta: this.precio_venta,
          idcategoria: this.categoria,
          descripcion: this.descripcion
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarArticulo(me.pagination.current_page);
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    desactivarArticulo() {
      let me = this;
      axios
        .put("/articulo/desactivar", {
          id: this.id
        })
        .then(function(response) {
          me.listarArticulo(me.pagination.current_page);
          me.cerrarModalDesactivar();
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    abrirModalDesactivar(idArticulo) {
      this.modalDesactivar = 1;
      this.id = idArticulo;
    },
    cerrarModalDesactivar() {
      this.modalDesactivar = 0;
      this.id = "";
    },
    validarArticulo() {
      this.errorArticulo = 0;
      this.errorMostrarMsjArticulo = [];
      if (!this.nombre)this.errorMostrarMsjArticulo.push("El nombre no puede estar vacío");
        
      if(!this.precio_venta)  this.errorMostrarMsjArticulo.push("El precio no puede estar vacío");
      if(!this.stock)  this.errorMostrarMsjArticulo.push("El stock no puede estar vacío");
       if(!this.categoria)  this.errorMostrarMsjArticulo.push("Seleccione una categoría");

      if (this.errorMostrarMsjArticulo.length) this.errorArticulo = 1;

      return this.errorArticulo;
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "Articulo": {
          switch (accion) {
            case "registrar": {
              this.tipoAccion = 1;
              this.tituloModal = "Registrar";
             
              this.id = 0;
              this.nombre = "";
              this.descripcion = "";
              this.stock = 0;
              this.precio_venta = 0;
              this.codigo = "";
              this.categoria = 0;
              this.modal = 1;
              break;
            }
            case "actualizar": {
              this.tipoAccion = 2;
              this.tituloModal = "Actualizar";
              this.modal = 1;
              this.id = data.id;
              this.nombre = data.nombre;
              this.descripcion = data.descripcion;
              this.stock = data.stock;
              this.precio_venta = data.precio_venta;
              this.codigo = data.codigo;
              this.categoria = data.idcategoria;
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
    this.listarArticulo(1);
    this.listarCategoria();
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
