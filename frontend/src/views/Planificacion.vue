<template>
  <v-container>

    <v-row row wrap>

      <v-col md6>
        <v-card class="mb-3">
          <v-card-text>
            <h2 style="color:black;">Crear Planificación Mensual</h2>
          </v-card-text>
          <div>
            <v-divider :thickness="8" class="border-opacity-50" color="brown"> </v-divider>
          </div>
          <br>
          <v-form @submit.prevent="agregarPlanificacion">

            <v-text-field type="number" label="Introduzca el año" variant="outlined" min="2024"
              v-model="anno"></v-text-field>
            <br>
            <v-autocomplete label="Introduzca el mes" :items="meses" v-model="mes" @blur="autoCompletableNombreVitola"
              variant="outlined"></v-autocomplete> <br>
            <v-text-field type="number" label="Introduzca los días laborales" variant="outlined" min="1" max="25"
              v-model="diasLaborables"></v-text-field>
            <br>
            <v-text-field type="number" label="Introduzca la planificación del mes" variant="outlined" min="1"
              v-model="planificacion"></v-text-field>


            <v-btn black color="brown" class="ml-15" type="submit">Crear Producción</v-btn>
          </v-form>
        </v-card>
      </v-col>





      <v-col md6>

        <v-card class="mb-3" v-for="(item, index) in listaPlanificaciones" :key="index">
          <v-card-text>
            <h2>{{ item.mes }} del {{ item.anno }}</h2>
          </v-card-text>

          <div>
            <v-divider :thickness="8" class="border-opacity-50" color="brown"> </v-divider>
          </div>
          <v-card-text class="d-flex flex-column">
            <ul>
              <li>
                <div mt-1 class="d-flex">
                  <h3>Planificación Mensual:</h3>{{ item.planificacion }} tabacos
                </div>
              </li>
              <li>
                <div mt-1 class="d-flex">
                  <h3>Días Laborables:</h3>{{ item.diasLaborables }}
                </div>
              </li>
            </ul>


          </v-card-text>

        </v-card>

      </v-col>

    </v-row>
    <v-snackbar v-model="snackbar">
      {{ mensaje }}

      <template v-slot:actions>
        <v-btn color="pink" variant="text" @click="snackbar = false">
          Cerrar
        </v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>

<script>
import axios from 'axios'


export default {

  data() {
    return {
      listaPlanificaciones: [],
      meses: ['Enero', 'Febrero', 'Marzo',
        'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre',
        'octubre', 'Noviembre', 'Diciembre'],
      anno: '',
      mes: '',
      diasLaborables: '',
      planificacion: '',
      snackbar: false,
      mensaje: '',
      indexProduccion: ''
    }
  },
  created() {
    this.obtenerPlanificaciones()
    this.obtenerFechaActual()
  },

  methods: {

    obtenerFechaActual() {
      const fechaActual = new Date();

      const añoActual = fechaActual.getFullYear();
      this.anno = añoActual
      const mesActual = parseInt(fechaActual.getMonth()); // Sumar 1 para obtener un número entre 1 y 12
      this.mes =  this.meses[mesActual]

    },

    async obtenerPlanificaciones() {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/planificacions`)
        this.listaPlanificaciones = response.data.planificaciones

      } catch (error) {
        console.error('Error al obtener las planificaciones', error)
      }
    },

    async agregarPlanificacion() {
      try {

        if (this.anno === '' || this.mes === '' || this.diasLaborables === '' || this.planificacion === '') {
          this.snackbar = true
          this.mensaje = 'Llena todos los campos!'
        } else {
          const numMes = this.getMonthNumberByName(this.mes)

          const existe = await axios.get(`http://127.0.0.1:8000/api/planificacions/mes=${numMes}/anno=${this.anno}`)
          if (existe.data.existe) {
            await axios.delete(`http://127.0.0.1:8000/api/planificacions/${existe.data.id}`)
          }
          const response = await axios.post(`http://127.0.0.1:8000/api/planificacions`, {
            anno: this.anno,
            mes: numMes,
            diasLaborables: this.diasLaborables,
            planificacionMensual: this.planificacion,
          })

          this.obtenerPlanificaciones()
          this.anno = '',
            this.mes = '',
            this.diasLaborables = '',
            this.planificacion = ''
          this.snackbar = true
          this.mensaje = 'Producción creada con éxito!'
        }
      } catch (error) {
        console.error('Error al obtener las planificaciones')
      }
    },
    getMonthNumberByName(monthName) {
      // Objeto de mapeo de nombre de mes a número
      try {

      const monthMapping = {
        enero: 1,
        febrero: 2,
        marzo: 3,
        abril: 4,
        mayo: 5,
        junio: 6,
        julio: 7,
        agosto: 8,
        septiembre: 9,
        octubre: 10,
        noviembre: 11,
        diciembre: 12,
      };

      // Obtener el número del mes a partir del nombre
      return monthMapping[monthName.toLowerCase()] || null;
    } catch (error) {
        console.error(error)
      }
    },
  }
}


</script>