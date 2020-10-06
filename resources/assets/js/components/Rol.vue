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
            @click="abrirModal('Rol','registrar')"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                
                <input
                  type="text"
                  v-model="buscar"
                  class="form-control "
                  
                  placeholder="Texto a buscar"
                  @keyup="listarRol(1)"
                />
               
              </div>
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="Rol in arrayRol" :key="Rol.id">
               
                <td v-text="Rol.nombre"></td>
                <td v-text="Rol.descripcion"></td>
                <td>
                  <div v-if="Rol.condicional">
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
    
   
  </main>
</template>

<script>
export default {
  data() {
    return {
      id: "",
      nombre: "",
      descripcion: "",
      arrayRol: [],
     
      pagination:{
                'total' : 0,
                'current_page': 0,
                'per_page' : 0,
                'last_page' : 0,
                'from' : 0,
                'to' : 0
      },
      offset:3,
     
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
     var from=1;
     

      var pagesArray=[];
      while(from<=this.pagination.last_page){
        pagesArray.push(from);
        from++;
      }

      return pagesArray;

    }
  },
  methods: {
    listarRol(page) {
      let me = this;
      axios
        .get("/rol?page="+page+"&buscar="+me.buscar)
        .then(function(response) {
          me.arrayRol = response.data.roles.data;
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
      me.listarRol(page);

    },
   
  },
  mounted() {
    this.listarRol(1);
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
