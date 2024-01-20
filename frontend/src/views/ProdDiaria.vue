<template>
  <v-container grid-list-xL>
    <v-row row wrap>


      <!--Aqui esta el formulario principal encargado de crear las producciones-->

      <v-col md6 v-if="formAgregar">
        <v-card class="mb-3">
          <v-card-text>
            <h1 style="color:black;">Crear Producción Diaria</h1>
          </v-card-text>
          <div>
            <v-divider :thickness="8" class="border-opacity-50" color="brown"> </v-divider>
          </div>
          <br>
          <v-form @submit.prevent="agregarProduccion">
            <v-autocomplete label=" Escriba la categoria" :items="['IX', 'VIII', 'VII']" v-model="Categoria"
              @blur="autoCompletableNombreVitola"></v-autocomplete>
            <br>
            <v-autocomplete label="Escriba el nombre de la vitola" :items="arrayNombreVitola"
              v-model="vitola"></v-autocomplete>
            <br>
            <v-autocomplete label=" Escriba el # de la brigada" :items="arrayBrigada" v-model="brigada"
              @blur="mostrarCantidadEmpleados"></v-autocomplete>
            <br>
            <v-row align="center">

              <v-col cols="7">

                <v-text-field hide-details label="Cantidad Trabajores de la Brigada" variant="outlined"
                  v-model="cant_trabajadores" type="number" min="1"
                  @change="calcularCantEmpleadosRestantes"></v-text-field>

              </v-col>
              <v-col>
                <v-card>

                  <v-card-text>
                    <h4>Total de empleados: {{ cantEmpleados }} </h4>
                  </v-card-text>
                  <v-card-text>
                    <h4>Empleados restantes: {{ cantEmpleadosRestantes }} </h4>
                  </v-card-text>

                </v-card>
              </v-col>
            </v-row>

            <br>
            <v-text-field label="Introduzca Cantidad Producida" variant="outlined" v-model="cant_producida" type="number"
              min="1"></v-text-field>
            <v-container>
              <v-btn block rounded="xl" elevation="16" color="success" type="submit">Crear Producción</v-btn>
            </v-container>
          </v-form>
        </v-card>
      </v-col>

      <!--Aqui esta el formulario copiado para la operacion del editar-->
      <v-col md6 v-if="!formAgregar">
        <v-card class="mb-3">
          <v-card-text>
            <h1 style="color:black;">Crear Producción Diaria</h1>
          </v-card-text>
          <div>
            <v-divider :thickness="8" class="border-opacity-50" color="brown"> </v-divider>
          </div>
          <br>
          <v-form @submit.prevent="editarProduccionA">
            <v-autocomplete label=" Escriba la categoria" :items="['IX', 'VIII', 'VII']" v-model="Categoria"
              @blur="autoCompletableNombreVitola"></v-autocomplete>
            <br>
            <v-autocomplete label="Escriba el nombre de la vitola" :items="arrayNombreVitola"
              v-model="vitola"></v-autocomplete>
            <br>
            <v-autocomplete label=" Escriba la brigada" :items="arrayBrigada" v-model="brigada"
              @blur="autoCompletableBrigadas"></v-autocomplete>
            <br>
            <v-text-field label=" Cantidad Trabajores de la Brigada" variant="outlined" v-model="cant_trabajadores"
              type="number"></v-text-field>
            <br>
            <v-text-field label="Introduzca Cantidad Producida" variant="outlined" v-model="cant_producida"
              type="number"></v-text-field>
            <br>
            <v-container>

              <v-btn black color="success" class="ml-15" type="submit">Editar Producción</v-btn>
              <v-btn black color="warning" class="ml-15" @click="cancelarEditar">Cancelar</v-btn>
            </v-container>

          </v-form>
        </v-card>
      </v-col>





      <!--Aqui se encuentra todo lo relacionado con las producciones que se crean-->

      <v-col md6>


        <v-card>
          <v-card-text>
            <h1 style="color:black;">Lista de Producción</h1>
          </v-card-text>
          <div>
            <v-divider :thickness="8" class="border-opacity-50" color="brown"> </v-divider>
          </div>
          <br>
          <v-card height="580" class="scrollable-card">

            <v-card v-for="(item, index) in listaProducciones" :key="index">
              <v-card-text>
                <v-chip class="ma-2" color="green" label>
                  <v-icon start icon="mdi-label"></v-icon>
                  Categoría:{{ item.Categoria }}
                </v-chip>
                <ul>
                  <li>
                    <strong>Vitola:</strong> {{ item.vitola }}
                  </li>
                  <li>
                    <strong>Brigada #:</strong> {{ item.brigada }}
                  </li>
                  <li>
                    <strong>Cantidad de Trabajadores:</strong> {{ item.cant_trabajadores }}
                  </li>
                  <li>
                    <strong>Cantidad Producida:</strong> {{ item.cant_producida }}
                  </li>
                </ul>
                <br>
                <v-btn color="green" @click="editarProduccion(index)">Editar</v-btn>
                <v-btn color="error" @click="eliminarProduccion(item.id)" class="ml-10">Eliminar</v-btn>
              </v-card-text>

            </v-card>

          </v-card>
        </v-card>
      </v-col>


      <!--Aqui todo relacionado con las estadisticas-->
      <v-col md6>
        <v-card>
          <v-card-text>
            <h1>Estadísticas</h1>
          </v-card-text>
          <div>
            <v-divider :thickness="8" class="border-opacity-50" color="brown"> </v-divider>
          </div>
          <v-card-text>
            <ul>
              <li>
                <h3>Cantidad de producciones:</h3>
              </li>
              <li>
                <h3>Cantidad total producida en el día: {{ prodDiariaTotal }}</h3>
              </li>
              <li>
                <h3>Porcentaje del cumplimiento con respecto al plan mensual:</h3>
              </li>
            </ul><br>

            <v-btn black color="success">Calcular</v-btn>
            <v-btn black color="warning" class="ml-10">Ver datos anteriores</v-btn>
          </v-card-text>

        </v-card>

      </v-col>

      <v-divider :thickness="8" class="border-opacity-50" color="brown"> </v-divider><br>
    </v-row>
    <!--Snackbar que muestra los mensajes cuando se realizan las operaciones-->
    <v-snackbar v-model="snackbar">
      {{ mensaje }}

      <template v-slot:actions>
        <v-btn color="red" variant="text" @click="snackbar = false">
          Cerrar
        </v-btn>
      </template>
    </v-snackbar>

    <v-btn black color="success" @click="guardarProduccion()">Agragar producciones del Dia</v-btn>
  </v-container>
</template>
<!--Aqui estan los scripts(en la seccion data estan los datos y en methods estan todos los metodos)-->
<script>
import axios from 'axios';

export default {

  data() {
    return {
      listaProducciones: [
        { id: 1, Categoria: 'Categoria#1', vitola: 'vitola', brigada: 'brigada', cant_trabajadores: 'cant trabajadores', cant_producida: 'cant producida' },
        { id: 3, Categoria: 'Categoria#1', vitola: 'vitola', brigada: 'brigada', cant_trabajadores: 'cant trabajadores', cant_producida: 'cant producida' },
        { id: 4, Categoria: 'Categoria#1', vitola: 'vitola', brigada: 'brigada', cant_trabajadores: 'cant trabajadores', cant_producida: 'cant producida' },
        { id: 2, Categoria: 'Categoria#2', vitola: 'vitola', brigada: 'brigada', cant_trabajadores: 'cant trabajadores', cant_producida: 'cant producida' }
      ],
      fechaID: '',
      Categoria: '',
      vitolaSelected: '',
      arrayVitolas: '',
      arrayNombreVitola: '',
      brigada: '',
      cant_trabajadores: '',
      cant_producida: '',
      snackbar: false,
      mensaje: '',
      formAgregar: true,
      indexProduccion: '',
      arrayBrigada: '',
      cantEmpleados: '',
      cantEmpleadosRestantes: '',
      prodDiariaTotal: ''
    }
  },
  // HACER EN LA API METODO PARA BUSCAR TODAS LAS CATEGORIAS Q HAY PARA LLAMARLO, Y TAMBIEN HACER EL DE OBTENER NOMBRES D VITOLAS PASANDOLE LA CATEGORIA
  created() {
    this.autoCompletableBrigadas()
    this.obtenerFecha()
    this.obtenerVitolas()
  },
  methods: {
    async obtenerFecha() {
      try {

        const fecha = new Date()
        const anno = fecha.getFullYear()
        const mes = fecha.getMonth() + 1
        const dia = fecha.getDate()
        const response = await axios.get(`http://127.0.0.1:8000/api/fechas/dia=${dia}/mes=${mes}/anno=${anno}`)
        this.fechaID = response.data.fecha_id
      } catch (error) {
        console.error('Error al obtener la fecha', error);
      }
    },
    async obtenerVitolas() {
      try {

        const response = await axios.get('http://127.0.0.1:8000/api/vitolas')
        
        this.arrayNombreVitola = response.data.vitolas[0].nombre
console.log(this.arrayNombreVitola)
      } catch (error) {
        console.error('Error al obtener las vitolasssssssssssssss', error);
      }
    },

    async autoCompletableNombreVitola() {
      // try {

      //   if (this.Categoria !== '') {


      //   }



      //   if (this.Categoria !== '') {
      //     const response = await axios.get(`http://127.0.0.1:8000/api/vitolasPorCategoria`, {
      //       params: {
      //         categoria: this.Categoria,
      //       }
      //     });
      //     this.arrayNombreVitola = [{ nombre: response.data.vitolas }]
      //   }
      // } catch (error) {
      //   console.error('Error al obtener las vitolas', error);
      // }
    },

    async autoCompletableBrigadas() {
      try {

        const response = await axios.get(`http://127.0.0.1:8000/api/brigadas`);
        console.log(response.data);
        this.arrayBrigada = response.data.brigadas.map(brigadas => brigadas.numero);

      } catch (error) {
        console.error('Error al obtener las brigadas', error);
      }
    },

    async mostrarCantidadEmpleados() {
      try {

        if (this.brigada !== null) {
          console.log(this.brigada)
          let numeroBrigada = this.brigada;
          const response = await axios.get(`http://127.0.0.1:8000/api/cant-empleados-brigada/${numeroBrigada}`);
          this.cantEmpleados = response.data;
          this.calcularCantEmpleadosRestantes()
        }
      } catch (error) {
        console.error('Error al obtener la cantidad de empleados', error);
      }
    },



    agregarProduccion() {
      if (this.Categoria === '' || this.vitolaSelected === '' || this.brigada === '' || this.cant_trabajadores === '' || this.cant_producida === '') {
        this.snackbar = true
        this.mensaje = 'Llena todos los campos!'
      } else {
        if (this.cantEmpleadosRestantes < 0) {
          this.snackbar = true
          this.mensaje = 'Sobrepasó la cantidad de trabajadores restantes de esa brigada!'
        } else {
          this.listaProducciones.push({
            Categoria: this.Categoria,
            vitola: this.vitolaSelected,
            brigada: this.brigada,
            cant_trabajadores: this.cant_trabajadores,
            cant_producida: this.cant_producida
          })
          this.Categoria = ''
          this.vitolaSelected = ''
          this.brigada = ''
          this.cant_trabajadores = ''
          this.cant_producida = ''
          this.snackbar = true
          this.mensaje = 'Producción creada con exito!'
          this.cantEmpleados = ''
          this.cantEmpleadosRestantes = ''
          this.calcularProduccionDiaria()
        }
      }
    },
    eliminarProduccion(id) {
      this.listaProducciones = this.listaProducciones.filter(e => e.id != id)
      this.snackbar = true
      this.mensaje = 'Producción eliminada correctamente!'
    },
    editarProduccion(index) {
      this.formAgregar = false
      this.Categoria = this.listaProducciones[index].Categoria
      this.vitolaSelected = this.listaProducciones[index].vitola
      this.brigada = this.listaProducciones[index].brigada
      this.cant_trabajadores = this.listaProducciones[index].cant_trabajadores
      this.cant_producida = this.listaProducciones[index].cant_producida
      this.indexProduccion = index
    },
    editarProduccionA() {
      this.listaProducciones[this.indexProduccion].Categoria = this.Categoria
      this.listaProducciones[this.indexProduccion].vitola = this.vitolaSelected
      this.listaProducciones[this.indexProduccion].brigada = this.brigada
      this.listaProducciones[this.indexProduccion].cant_trabajadores = this.cant_trabajadores
      this.listaProducciones[this.indexProduccion].cant_producida = this.cant_producida
      this.formAgregar = true
      this.Categoria = ''
      this.vitolaSelected = ''
      this.brigada = ''
      this.cant_trabajadores = ''
      this.cant_producida = ''
      this.snackbar = true
      this.mensaje = 'Se ha editado correctamente la producción!'
    },
    cancelarEditar() {
      this.formAgregar = true
      this.Categoria = ''
      this.vitolaSelected = ''
      this.brigada = ''
      this.cant_trabajadores = ''
      this.cant_producida = ''
      this.snackbar = true
      this.mensaje = 'Se ha cancelado la edición !'
    },
    calcularCantEmpleadosRestantes() {
      let asignados = 0
      for (let i = 0; i < this.listaProducciones.length; i++) {
        if (this.listaProducciones[i].brigada === this.brigada) {
          asignados += Number(this.listaProducciones[i].cant_trabajadores)
        }
      }
      // No funciona
      asignados += Number(this.cant_trabajadores)
      return this.cantEmpleadosRestantes = this.cantEmpleados - asignados
    },
    calcularProduccionDiaria() {
      let prodDiaria = 0
      for (let i = 0; i < this.listaProducciones.length; i++) {
        prodDiaria += Number(this.listaProducciones[i].cant_producida)
      }
      this.prodDiariaTotal = prodDiaria
    },

    async guardarProduccion() {
      try {
        this.listaProducciones.forEach(element => {


        });

        const responseVitola = await axios.get('http://127.0.0.1:8000/api/obtener-id-vitola', {
          params: {
            nombre: element.vitola
          }
        })
        console.log(responseVitola.data)
        // if (responseVitola.data.id) {
        //   this.idVitola = responseVitola.data.id;
        // } else {
        //   console.error('Vitola no encontrada');
        // }
        // const responseBrigada = await axios.get('http://127.0.0.1:8000/api/obtener-id-brigada', {
        //   params:{
        //     numero: 1,
        //   }
        // });
        // console.log(responseBrigada)
        // if (responseBrigada.data.id) {
        //   this.idVitola = responseBrigada.data.id;
        // } else {
        //   console.error('Vitola no encontrada');
        // }






      } catch (error) {
        // Manejar errores aquí
        console.error('Error al enviar los datos:', error);
      }

    },


  }
}
</script>

<style scoped>
.scrollable-card {
  overflow-y: auto;
}
</style>