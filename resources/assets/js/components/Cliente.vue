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
          <i class="fa fa-align-justify"></i> Personas
          <button
            type="button"
            class="btn btn-secondary"
            @click="abrirModal('Persona','registrar')"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio" @click="listarPersona(1)">
                  <option value="nombre">Nombre</option>
                  <option value="num_documento">Número de documento</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  class="form-control"
                  placeholder="Texto a buscar"
                  @keyup="listarPersona(1)"
                />
              </div>
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Opciones</th>
                <th>Tipo de documento</th>
                <th>Número de documento</th>
                <th>Nombre</th>
                <th>Direccion</th>
                <th>Teléfono</th>
                <th>email</th>
                
              </tr>
            </thead>
            <tbody>
              <tr v-for="Persona in arrayPersona" :key="Persona.id">
                <td>
                  <button
                    type="button"
                    class="btn btn-warning btn-sm"
                    data-toggle="modal"
                    data-target="#modalNuevo"
                    @click="abrirModal('Persona','actualizar',Persona)"
                  >
                    <i class="icon-pencil"></i>
                  </button> &nbsp;
                 
                </td>
                <td v-text="Persona.tipo_documento"></td>
                <td v-text="Persona.num_documento"></td>
                <td v-text="Persona.nombre"></td>
                <td v-text="Persona.direccion"></td>
                <td v-text="Persona.telefono"></td>
                <td v-text="Persona.email"></td>
               
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
                <label class="col-md-3 form-control-label" for="text-input">Tipo de documento</label>
                <div class="col-md-9">
                  
                <select v-model="tipo_documento" class="form-control">
                    <option value="DNI"  >DNI</option>
                    <option  value="RUC" >RUC</option>
                    <option value="PASS" >PASS</option>
                </select>
                  
                </div>
                
                 
               </div>
               <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Número de documento</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="num_documento"
                    class="form-control"
                    placeholder="Nombre de categoría"
                  />
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
                <label class="col-md-3 form-control-label" for="email-input">dirección</label>
                <div class="col-md-9">
                  <input
                    type="email"
                    v-model="direccion"
                    class="form-control"
                    placeholder="Ingrese dirección"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Email</label>
                <div class="col-md-9">
                  <input
                    type="email"
                    v-model="email"
                    class="form-control"
                    placeholder="Ingrese descripción"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Teléfono</label>
                <div class="col-md-9">
                  <input
                    type="email"
                    v-model="telefono"
                    class="form-control"
                    placeholder="Ingrese teléfono"
                  />
                </div>
              </div>
              
              <div v-show="errorPersona" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error"></div>
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
              @click="registrarPersona()"
            >Guardar</button>
            <button
              type="button"
              class="btn btn-primary"
              v-if="tipoAccion==2"
              @click="actualizarPersona()"
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
            <button type="button" class="btn btn-danger" @click="desactivarPersona()">Eliminar</button>
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
      tipo_documento: "DNI",
      num_documento: "",
      nombre: "",
      direccion: "",
      telefono: "",
      email: "",
      
      arrayPersona: [],
      modal: 0,
      modalDesactivar: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorPersona: 0,
      errorMostrarMsjPersona: [],
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
 
  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }
      
      var from = 1;
      
      var pagesArray = [];
      while (from <= this.pagination.last_page) {
        pagesArray.push(from);
        from++;
      }

      return pagesArray;
    }
  },
  methods: {
    listarPersona(page) {
      let me = this;
      axios
        .get(
          "/persona?page=" +
            page +
            "&buscar=" +
            me.buscar +
            "&criterio=" +
            me.criterio
        )
        .then(function(response) {
          me.arrayPersona = response.data.personas.data;
          me.pagination = response.data.pagination;
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
   
    cambiarPagina(page) {
      let me = this;
      me.pagination.current_page = page;
      me.listarPersona(page);
    },
    registrarPersona() {
      let me = this;
      if (this.validarPersona()) {
        return false;
      }
      axios
        .post("/persona/registrar", {
          nombre: this.nombre,
          email: this.email,
          telefono: this.telefono,
          direccion: this.direccion,
          tipo_documento: this.tipo_documento,
          num_documento: this.num_documento
        })
        .then(function(response) {
          me.cerrarModal();
          me.buscar = "";
          me.criterio = "nombre";
          me.listarPersona(1);
          console.log(response);

        })
        .catch(function(error) {
          console.log(error);
        });
    },

    actualizarPersona() {
      let me = this;
      if (this.validarPersona()) {
        return false;
      }
      axios
        .put("/persona/actualizar", {
          id: this.id,
          nombre: this.nombre,
          email: this.email,
          telefono: this.telefono,
          direccion: this.direccion,
          tipo_documento: this.tipo_documento,
          num_documento: this.num_documento
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarPersona(me.pagination.current_page);
        })
        .catch(function(error) {
          console.log(error);
        });
    },

  
    validarPersona() {
      this.errorPersona = 0;
      this.errorMostrarMsjPersona = [];
      
    
      if (!this.nombre)this.errorMostrarMsjPersona.push("El nombre no puede estar vacío");
      if (this.nombre.length>50)this.errorMostrarMsjPersona.push("El nombre debe contener 50 caracteres max.");
        
      if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

      return this.errorPersona;
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "Persona": {
          switch (accion) {
            case "registrar": {
              this.tipoAccion = 1;
              this.tituloModal = "Registrar";
             
              this.id = 0;
              this.nombre = "";
              this.direccion = "";
              this.telefono = "";
              this.email;
              this.num_documento = "";
              this.tipo_documento="DNI";
              this.modal = 1;
              break;
            }
            case "actualizar": {
              this.tipoAccion = 2;
              this.tituloModal = "Actualizar";
              this.modal = 1;
              this.id = data.id;
              this.nombre = data.nombre;
              this.direccion = data.direccion;
              this.email = data.email;
              this.telefono = data.telefono;
              this.num_documento = data.num_documento;
              this.tipo_documento = data.tipo_documento;
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
    this.listarPersona(1);
    
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
