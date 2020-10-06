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
          <i class="fa fa-align-justify"></i> Usuarios
          <button
            type="button"
            class="btn btn-secondary"
            @click="abrirModal('User','registrar')"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio" @click="listarUser(1)">
                  <option value="nombre">Nombre</option>
                  <option value="num_documento">Número de documento</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  class="form-control"
                  placeholder="Texto a buscar"
                  @keyup="listarUser(1)"
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
                <th>Usuario</th>
                <th>Condición</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="User in arrayUser" :key="User.id">
                <td>
                  <button
                    type="button"
                    class="btn btn-warning btn-sm"
                    data-toggle="modal"
                    data-target="#modalNuevo"
                    @click="abrirModal('User','actualizar',User)"
                  >
                    <i class="icon-pencil"></i>
                  </button>&nbsp;
                  <button
                    type="button"
                    class="btn btn-danger btn-sm"
                    @click="abrirModalDesactivar(User.id)"
                  >
                    <i class="icon-trash"></i>
                  </button>
                  &nbsp;
                </td>
                <td v-text="User.tipo_documento"></td>
                <td v-text="User.num_documento"></td>
                <td v-text="User.nombre"></td>
                <td v-text="User.direccion"></td>
                <td v-text="User.telefono"></td>
                <td v-text="User.email"></td>
                <td v-text="User.usuario"></td>
                <td>
                  <div v-if="User.condicion">
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
                <label class="col-md-3 form-control-label" for="text-input">Tipo de documento</label>
                <div class="col-md-9">
                  <select v-model="tipo_documento" class="form-control">
                    <option value="DNI">DNI</option>
                    <option value="RUC">RUC</option>
                    <option value="PASS">PASS</option>
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
                <label class="col-md-3 form-control-label" for="email-input">Dirección</label>
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
                    type="text"
                    v-model="telefono"
                    class="form-control"
                    placeholder="Ingrese teléfono"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Usuario</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="usuario"
                    class="form-control"
                    placeholder="Ingrese usuario"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Password</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="password"
                    class="form-control"
                    placeholder="Ingrese password de usuario"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Rol</label>
                <div class="col-md-9">
                  <select class="form-control select-search" v-model="idrol">
                    <option
                      v-for="rol in arrayRol"
                      :key="rol.id"
                      :value="rol.id"
                      v-text="rol.nombre"
                      :selected="rol.id ==idrol"
                    ></option>
                  </select>
                </div>
              </div>

              <div v-show="errorUser" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjUser" :key="error" v-text="error"></div>
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
              @click="registrarUser()"
            >Guardar</button>
            <button
              type="button"
              class="btn btn-primary"
              v-if="tipoAccion==2"
              @click="actualizarUser()"
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
            <h4 class="modal-title">Desactivar Usuario</h4>
            <button type="button" class="close" @click="cerrarModalDesactivar()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Estas seguro de eliminar este usuario?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModalDesactivar()">Cerrar</button>
            <button type="button" class="btn btn-danger" @click="desactivarUsuario()">Eliminar</button>
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
      tipo_documento: "DNI",
      num_documento: "",
      nombre: "",
      direccion: "",
      telefono: "",
      email: "",
      usuario: "",
      password: "",
      condicion: "",
      idrol: "",

      arrayUser: [],
      arrayRol: [],
      modal: 0,
      modalDesactivar: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorUser: 0,
      errorMostrarMsjUser: [],
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
    listarUser(page) {
      let me = this;
      axios
        .get(
          "/user?page=" +
            page +
            "&buscar=" +
            me.buscar +
            "&criterio=" +
            me.criterio
        )
        .then(function(response) {
          me.arrayUser = response.data.User.data;
          me.pagination = response.data.pagination;
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    listarRol() {
      let me = this;
      axios
        .get("/rol")
        .then(function(response) {
          me.arrayRol = response.data.roles.data;
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
      me.listarUser(page);
    },
    registrarUser() {
      let me = this;
      if (this.validarUser()) {
        return false;
      }
      axios
        .post("/user/registrar", {
          nombre: this.nombre,
          email: this.email,
          telefono: this.telefono,
          direccion: this.direccion,
          tipo_documento: this.tipo_documento,
          num_documento: this.num_documento,
          usuario: this.usuario,
          password: this.password,
          condicion: this.condicion,
          idrol: this.idrol
        })
        .then(function(response) {
          me.cerrarModal();
          me.buscar = "";
          me.criterio = "nombre";
          me.listarUser(1);
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    actualizarUser() {
      let me = this;
      if (this.validarUser()) {
        return false;
      }
      axios
        .put("/user/actualizar", {
          id: this.id,
          nombre: this.nombre,
          email: this.email,
          telefono: this.telefono,
          direccion: this.direccion,
          tipo_documento: this.tipo_documento,
          num_documento: this.num_documento,
          usuario: this.usuario,
          password: this.password,
          condicion: this.condicion,
          idrol: this.idrol
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarUser(me.pagination.current_page);
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    abrirModalDesactivar(id) {
      this.modalDesactivar = 1;
      this.id = id;
    },
    cerrarModalDesactivar() {
      this.modalDesactivar = 0;
      this.id = "";
    },

      desactivarUsuario() {
      let me = this;
      axios
        .put("/user/desactivar", {
          id: this.id
        })
        .then(function(response) {
          me.listarUser(me.pagination.current_page);
          me.cerrarModalDesactivar();
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    validarUser() {
      this.errorUser = 0;
      this.errorMostrarMsjUser = [];

      if (!this.nombre)
        this.errorMostrarMsjUser.push("El nombre no puede estar vacío");
      if (this.nombre.length > 50)
        this.errorMostrarMsjUser.push(
          "El nombre debe contener 50 caracteres max."
        );

      if (this.errorMostrarMsjUser.length) this.errorUser = 1;

      return this.errorUser;
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "User": {
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
              this.tipo_documento = "DNI";
              this.usuario = "";
              this.password = "";
              this.idrol = "";
              this.condicion = "";
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
              this.usuario = data.usuario;
              this.password = data.password;
              this.condicion = data.condicion;
              this.idrol = data.idrol;
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
    this.listarUser(1);
    this.listarRol();
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
  background-color: #6ee0107a !important;
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
