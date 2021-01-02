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
          <button type="button" @click="mostrar()" class="btn btn-secondary">
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <!-- Listado-->
        <template v-if="listado">
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-3" v-model="criterio">
                    <option value="tipo_comprobante">Tipo Comprobante</option>
                    <option value="num_comprobante">Número Comprobante</option>
                    <option value="fecha_hora">Fecha-Hora</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscar"
                    @keyup.enter="listarIngreso(1)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  />
                  <button
                    type="submit"
                    @click="listarIngreso(1)"
                    class="btn btn-primary"
                  >
                    <i class="fa fa-search"></i> Buscar
                  </button>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>Opciones</th>
                    <th>Usuario</th>
                    <th>Proveedor</th>
                    <th>Tipo</th>
                    <th>Serie</th>
                    <th>Número</th>
                    <th>Fecha Hora</th>
                    <th>Total</th>
                    <th>Impuesto</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                    <td>
                      <button
                        type="button"
                        @click="mostrarDetalle(ingreso.id)"
                        class="btn btn-success btn-sm"
                      >
                        <i class="icon-eye"></i>
                      </button>
                      &nbsp;
                      <template v-if="ingreso.estado == 'registrado'">
                        <button
                          type="button"
                          class="btn btn-danger btn-sm"
                          @click="desactivarIngreso(ingreso.id)"
                        >
                          <i class="icon-trash"></i>
                        </button>
                      </template>
                    </td>
                    <td
                      v-text="ingreso.usuario + ' (' + ingreso.rol + ')'"
                    ></td>
                    <td v-text="ingreso.proveedor"></td>
                    <td v-text="ingreso.tipo_comprobante"></td>
                    <td v-text="ingreso.serie_comprobante"></td>
                    <td v-text="ingreso.num_comprobante"></td>
                    <td v-text="ingreso.fecha_hora"></td>
                    <td v-text="'S/. ' + ingreso.total"></td>
                    <td v-text="'S/. ' + ingreso.impuesto"></td>
                    <td v-text="ingreso.estado"></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <nav>
              <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="
                      cambiarPagina(
                        pagination.current_page - 1,
                        buscar,
                        criterio
                      )
                    "
                    >Ant</a
                  >
                </li>
                <li
                  class="page-item"
                  v-for="page in pagesNumber"
                  :key="page"
                  :class="[page == isActived ? 'active' : '']"
                >
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="cambiarPagina(page, buscar, criterio)"
                    v-text="page"
                  ></a>
                </li>
                <li
                  class="page-item"
                  v-if="pagination.current_page < pagination.last_page"
                >
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="
                      cambiarPagina(
                        pagination.current_page + 1,
                        buscar,
                        criterio
                      )
                    "
                    >Sig</a
                  >
                </li>
              </ul>
            </nav>
          </div>
        </template>
        <!--Fin Listado-->
        <!-- Detalle-->
        <template v-else>
          <div class="card-body">
            <div class="form-group row border">
              <div class="col-md-9">
                <div class="form-group">
                  <label for="">Proveedor(*)</label>

                  <span
                    class="text-center text-error"
                    v-for="e in errorsProveedor"
                    :key="e"
                    v-text="e"
                  ></span>

                  <v-select
                    v-model="proveedor"
                    @search="selectProveedor"
                    label="nombre"
                    :options="arrayProveedor"
                    @input="getDatosProveedor"
                    placeholder="Buscar Proveedores..."
                  >
                  </v-select>

                  <!--<select class="form-control" v-model="idproveedor">
                    <option value="0">Seleccione</option>
                    <option v-for="proveedor in arrayProveedor" :key="proveedor.id" :value="proveedor.id" v-text="proveedor.nombre"></option>
                  </select>-->
                </div>
              </div>
              <div class="col-md-3">
                <label for="">Impuesto(*)</label>
                <span
                  class="text-center text-error"
                  v-for="e in errorsImpuesto"
                  :key="e"
                  v-text="e"
                ></span>
                <input type="number" class="form-control" v-model="impuesto" />
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Tipo Comprobante(*)</label>
                  <span
                    class="text-center text-error"
                    v-for="e in errorsTipoComprobante"
                    :key="e"
                    v-text="e"
                  ></span>
                  <select class="form-control" v-model="tipo_comprobante">
                    <option value="0">Seleccione</option>
                    <option value="BOLETA">Boleta</option>
                    <option value="FACTURA">Factura</option>
                    <option value="TICKET">Ticket</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Serie Comprobante</label>

                  <input
                    type="text"
                    class="form-control"
                    v-model="serie_comprobante"
                    placeholder="000x"
                  />
                  <div
                    class="text-center text-error"
                    v-for="e in errorsSerie"
                    :key="e"
                    v-text="e"
                  >
                    <br />
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Número Comprobante(*)</label>
                  <span
                    class="text-center text-error"
                    v-for="e in errorsNumero"
                    :key="e"
                    v-text="e"
                  ></span>
                  <input
                    type="text"
                    class="form-control"
                    v-model="num_comprobante"
                    placeholder="000xx"
                  />
                </div>
              </div>
            </div>
            <div class="form-group row border">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Artículo</label>
                  <div class="form-inline">
                    <input
                      type="text"
                      class="form-control"
                      v-model="codigo"
                      placeholder="Ingrese artículo"
                      @keyup.enter="buscarArticulo()"
                    />
                    <button class="btn btn-primary" @click="abrirModal()">
                      ...
                    </button>
                    <input
                      type="text"
                      readonly
                      class="form-control"
                      v-model="articulo"
                    />
                  </div>
                  <span v-show="idarticulo == 0" style="color: red"
                    >Seleccione</span
                  >
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>Precio</label>

                  <input
                    type="number"
                    value="0"
                    step="any"
                    class="form-control"
                    v-model="precio"
                  />
                  <span v-show="precio == '' || precio < 0" style="color: red"
                    >Ingrese un precio correcto</span
                  >
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>Cantidad</label>
                  <input
                    type="number"
                    value="0"
                    class="form-control"
                    v-model="cantidad"
                  />
                  <span
                    v-show="cantidad == '' || cantidad < 0"
                    style="color: red"
                    >Ingrese una cantidad correcta</span
                  >
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <button
                    @click="añadirDetalle()"
                    class="btn btn-success form-control btnagregar"
                  >
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
                      <th>Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                      <td>
                        <button
                          type="button"
                          @click="eliminarDetalle(detalle.id)"
                          class="btn btn-danger btn-sm"
                        >
                          <i class="icon-close"></i>
                        </button>
                      </td>
                      <td v-text="detalle.nombre"></td>
                      <td>
                        <input
                          type="number"
                          v-model="detalle.precio"
                          class="form-control"
                        />
                      </td>
                      <td>
                        <input
                          type="number"
                          v-model="detalle.cantidad"
                          class="form-control"
                        />
                      </td>
                      <td v-text="(detalle.precio * detalle.cantidad).toFixed(2)"></td>
                    </tr>
                  </tbody>
                </table>

                <div class="col-6" style="left: 50%">
                  <table class="table table-bordered table-striped table-sm">
                    <tbody v-for="dato in total" :key="dato">
                      <tr style="background-color: #ceecf5">
                        <td colspan="4" align="right">
                          <strong>Total Parcial:</strong>
                        </td>
                        <td>
                          <span
                            v-text="dato.total_parcial"
                            class="form-control"
                            readonly
                          ></span>
                        </td>
                      </tr>
                      <tr style="background-color: #ceecf5">
                        <td colspan="4" align="right">
                          <strong>Total Impuesto:</strong>
                        </td>
                        <td>
                          <span
                            v-text="dato.total_impuesto"
                            class="form-control"
                            readonly
                          ></span>
                        </td>
                      </tr>
                      <tr style="background-color: #ceecf5">
                        <td colspan="4" align="right">
                          <strong>Total Neto:</strong>
                        </td>
                        <td>
                          <span
                            v-text="dato.total_neto"
                            class="form-control"
                            readonly
                          ></span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <button
                  type="button"
                  @click="ocultarDetalle()"
                  class="btn btn-secondary"
                >
                  Cerrar
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="registrarIngreso()"
                >
                  Registrar Compra
                </button>
              </div>
            </div>
          </div>
        </template>
        <!-- Fin Detalle-->
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal articulos-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{ mostrar: modal }"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button
              type="button"
              class="close"
              @click="cerrarModal()"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body table-responsive">
            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-6">
                  <div class="input-group">
                    <select
                      class="form-control col-md-3"
                      v-model="criterio_articulo"
                      @click="listarArticulo(1)"
                    >
                      <option value="nombre">Nombre</option>
                      <option value="descripcion">Descripción</option>
                    </select>
                    <input
                      type="text"
                      v-model="buscar_articulo"
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
                        class="btn btn-success btn-sm"
                        data-toggle="modal"
                        data-target="#modalNuevo"
                        @click="seleccionarArticulo(articulo)"
                      >
                        <i class="icon-check"></i>
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
                  <li
                    class="page-item"
                    v-if="pagination_articulo.current_page > 1"
                  >
                    <a
                      class="page-link"
                      href="#"
                      @click.prevent="
                        cambiarPaginaArticulo(
                          pagination_articulo.current_page - 1
                        )
                      "
                      >Ant</a
                    >
                  </li>
                  <li
                    class="page-item"
                    v-for="page in pagesNumberArticulo"
                    :key="page"
                    :class="[page == isActived ? 'active' : '']"
                  >
                    <a
                      class="page-link"
                      href="#"
                      @click.prevent="cambiarPaginaArticulo(page)"
                      v-text="page"
                    ></a>
                  </li>

                  <li
                    class="page-item"
                    v-if="
                      pagination_articulo.current_page <
                      pagination_articulo.last_page
                    "
                  >
                    <a
                      class="page-link"
                      href="#"
                      @click.prevent="
                        cambiarPaginaArticulo(
                          pagination_articulo.current_page + 1
                        )
                      "
                      >Sig</a
                    >
                  </li>
                </ul>
              </nav>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="cerrarModal()"
            >
              Cerrar
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->

    <!--Inicio del modal detalle-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{ mostrar: modal_detalle }"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Detalle compra</h4>
            <button
              type="button"
              class="close"
              @click="cerrarModalDetalle()"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body table-responsive">
            <div class="card-body">
              <table
                class="table table-bordered table-striped table-sm text-right"
              >
                <thead>
                  <tr>
                    <th>Articulo</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="detalle in arrayIngresoDetalle" :key="detalle.id">
                    <td v-text="detalle.nombre"></td>

                    <td v-text="detalle.cantidad"></td>
                    <td v-text="'S/. ' + detalle.precio"></td>
                    <td
                      v-text="
                        'S/. ' + (detalle.cantidad * detalle.precio).toFixed(2)
                      "
                    ></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="cerrarModalDetalle()"
            >
              Cerrar
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </main>
</template>

<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

import Swal from "sweetalert";
import swal from "sweetalert";

export default {
  data() {
    return {
      ingreso_id: 0,
      idproveedor: 0,
      nombre: "",
      idarticulo: 0,
      codigo: "",
      articulo: "",
      precio: 0,
      cantidad: 0,
      tipo_comprobante: "BOLETA",
      serie_comprobante: "",
      num_comprobante: "",
      impuesto: 0.18,

      proveedor: { nombre: "Por defecto" },
      arrayIngreso: [],
      arrayProveedor: [],
      arrayArticulo: [],
      arrayDetalle: [],
      arrayIngresoDetalle: [],

      errorsProveedor: [],
      errorsImpuesto: [],
      errorsTipoComprobante: [],
      errorsSerie: [],
      errorsNumero: [],

      listado: 1,
      modal: 0,
      modal_detalle: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorIngreso: 0,
      errorMostrarMsjIngreso: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      pagination_articulo: {
        total: 0,

        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      offset: 2,
      criterio: "num_comprobante",
      buscar: "",
      criterio_articulo: "nombre",
      buscar_articulo: "",
    };
  },
  components: {
    vSelect,
    Swal,
  },
  computed: {
    total: function () {
      let me = this;

      var neto = 0;
      for (var i = 0; i < me.arrayDetalle.length; i++) {
        var subtotal =
          parseFloat(me.arrayDetalle[i].precio) *
          parseInt(me.arrayDetalle[i].cantidad);
        neto = neto + subtotal;
      }
      var impuesto = (neto * me.impuesto) / (1 + me.impuesto);
      var parcial = parseFloat(neto) - impuesto;
      var total = [
        {
          total_parcial: "S/. " + parcial.toFixed(2),
          total_impuesto: "S/. " + impuesto.toFixed(2),
          total_neto: "S/. " + neto.toFixed(2),
        },
      ];

      return total;
    },

    isActived: function () {
      return this.pagination.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumber: function () {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },

    pagesNumberArticulo: function () {
      if (!this.pagination_articulo.to) {
        return [];
      }
      /*
      var from = this.pagination_articulo.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination_articulo.last_page) {
        to = this.pagination_articulo.last_page;
      }
*/
      var pagesArray = [];
      var from = 1;
      while (from <= this.pagination_articulo.last_page) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
  },
  methods: {
    desactivarIngreso(id) {
      swal({
        title: "¿Esta seguro de anular este ingreso?",

        icon: "warning",
        buttons: {
          confirm: {
            text: "Aceptar",
            value: true,
            visible: true,
            className: "btn",
            closeModal: true,
          },
          cancel: {
            text: "Cancel",
            value: false,
            visible: true,
            className: "btn",
            closeModal: true,
          },
        },
      }).then((result) => {
        if (result) {
          let me = this;

          axios
            .put("/ingreso/desactivar", {
              id: id,
            })
            .then(function (response) {
              me.listarIngreso(1);
              swal(
                "Anulado!",
                "El ingreso ha sido anulado con éxito.",
                "success"
              );
            })
            .catch(function (error) {
              console.log(error);
            });
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
        }
      });
    },

    registrarIngreso() {
      let me = this;
      if (me.arrayDetalle.length == 0) {
        swal({
          icon: "warning",
          title: "Mensaje",
          text: "¡Sin datos!",
        });
        return false;
      }

      if (this.validarDatos()) {
        axios
          .post("/ingreso/registrar", {
            detalles: me.arrayDetalle,
            serie_comprobante: me.serie_comprobante,
            num_comprobante: me.num_comprobante,
            tipo_comprobante: me.tipo_comprobante,
            idproveedor: me.idproveedor,
            impuesto: me.impuesto,
          })
          .then(function (response) {
            swal({
              icon: "success",
              title: "Correcto",
              text: "¡compra registrada!",
            });
          })
          .catch(function (error) {
            console.log(error);

            swal({
              icon: "error",
              title: "Error",
              text: "Compra no registrada",
            });
          });
        me.listarIngreso();
      } else {
        swal({
          icon: "warning",
          title: "Mensaje",
          text: "¡complete los campos correctamente!",
        });
        return false;
      }
    },

    añadirDetalle() {
      let me = this;
      if (
        me.idarticulo == 0 ||
        me.cantidad == 0 ||
        me.precio == 0 ||
        me.cantidad < 0 ||
        me.precio < 0
      ) {
        return false;
      }

      for (var i = 0; i < me.arrayDetalle.length; i++) {
        if (me.arrayDetalle[i].idarticulo == me.idarticulo) {
          swal({
            type: "error",
            icon: "error",
            title: "Error ...",
            text: "¡Este artículo ya se encuentra agregado!",
          });
          return false;
        }
      }

      var detalle = {
        id: parseInt(me.arrayDetalle.length) + 1,
        idarticulo: me.idarticulo,
        nombre: me.articulo,
        cantidad: me.cantidad,
        precio: me.precio,
      };
      me.arrayDetalle.push(detalle);

      me.idarticulo = 0;
      me.cantidad = 0;
      me.codigo = "";
      me.precio = 0;
      me.articulo = "";
    },

    eliminarDetalle(id) {
      let me = this;
      for (var i = 0; i < me.arrayDetalle.length; i++) {
        if (me.arrayDetalle[i].id == id) {
          me.arrayDetalle.splice(i, 1);
          return false;
        }
      }
    },
    seleccionarArticulo(articulo) {
      let me = this;
      me.idarticulo = articulo.id;
      me.articulo = articulo.nombre;

      me.precio = 1;
      me.cantidad = 1;
      this.añadirDetalle();
      //me.modal = 0;
    },
    listarArticulo(page) {
      let me = this;
      axios
        .get(
          "/articulo?page=" +
            page +
            "&buscar=" +
            me.buscar_articulo +
            "&criterio=" +
            me.criterio_articulo
        )
        .then(function (response) {
          me.arrayArticulo = response.data.articulos.data;
          me.pagination_articulo = response.data.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    cambiarPaginaArticulo(page) {
      let me = this;
      me.pagination_articulo.current_page = page;
      me.listarArticulo(page);
    },
    cambiarPagina(page) {
      let me = this;
      me.pagination.current_page = page;
      me.listarIngreso(page);
    },
    listarIngreso(page) {
      let me = this;
      var url =
        "/ingreso?page=" +
        page +
        "&buscar=" +
        me.buscar +
        "&criterio=" +
        me.criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayIngreso = respuesta.ingresos.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    listarIngresoDetalle(idingreso) {
      let me = this;
      var url = "/detalle-ingreso?idingreso=" + idingreso;
      axios
        .get(url)
        .then(function (response) {
          me.arrayIngresoDetalle = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    selectProveedor(search, loading) {
      let me = this;
      loading(true);

      var url = "/proveedor/selectProveedor?filtro=" + search;
      axios
        .get(url)
        .then(function (response) {
          q: search;
          me.arrayProveedor = response.data;
          loading(false);
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    getDatosProveedor(val1) {
      let me = this;
      me.loading = true;
      me.idproveedor = val1.id;
    },

    buscarArticulo() {
      let me = this;
      var url = "/articulo/buscarArticulo?filtro=" + me.codigo;
      axios
        .get(url)
        .then(function (response) {
          me.arrayArticulo = response.data;
          if (me.arrayArticulo.length > 0) {
            me.articulo = me.arrayArticulo[0].nombre;
            me.idarticulo = me.arrayArticulo[0].id;
          } else {
            me.articulo = "No existe articulos";
            me.idarticulos = 0;
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    mostrarDetalle(idingreso) {
      this.modal_detalle = 1;
      this.listarIngresoDetalle(idingreso);
    },
    ocultarDetalle() {
      this.listado = 1;
    },
    abrirModal() {
      this.listarArticulo(1);
      this.modal = 1;
    },
    cerrarModal() {
      this.modal = 0;
      this.arrayArticulo = [];
    },
    cerrarModalDetalle() {
      this.modal_detalle = 0;
      this.arrayIngresoDetalle = [];
    },

    validarDatos() {
      let me = this;
      me.errorsProveedor = [];
      me.errorsImpuesto = [];
      me.errorsTipoComprobante = [];
      me.errorsSerie = [];
      me.errorsNumero = [];
      var expresion_num = /[0-9]/;

      if (me.idproveedor == 0) me.errorsProveedor.push("Seleccione");
      if (me.impuesto < 0) me.errorsImpuesto.push("Ingrese un némero valido");
      if (me.tipo_comprobante == 0) me.errorsTipoComprobante.push("Seleccione");
      if (me.serie_comprobante == "")
        me.errorsSerie.push("Ingrese la serie del comprobante");
      if (isNaN(me.impuesto)) me.errorsImpuesto.push("Solo se permite numeros");
      if (me.num_comprobante == "")
        me.errorsNumero.push("Ingrese un numero de comprobante");

      if (
        me.errorsProveedor.length > 0 ||
        me.errorsImpuesto.length > 0 ||
        me.errorsTipoComprobante.length > 0 ||
        me.errorsSerie.length > 0 ||
        me.errorsNumero.length > 0
      ) {
        return false;
      }
      return true;
    },

    mostrar() {
      this.listado = 0;
    },
  },
  mounted() {
    this.listarIngreso(1, this.buscar, this.criterio);
    this.selectProveedor();
    this.listarArticulo();
  },
};
</script>
<style>
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

.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
@media (min-width: 600px) {
  .btnagregar {
    margin-top: 2rem;
  }
}
</style>
