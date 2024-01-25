<template>
  <v-row justify="center">
    <v-col sm="3">
      <v-container>
        <v-text-field v-model="fechaSelected" variant="outlined" label="Seleccione la fecha " type="date"
          @input="selected"></v-text-field>
      </v-container>
    </v-col>
  </v-row>

  <v-row>
    <v-col cols="5">
      <v-card-text style="margin-top: -20px;">
        <v-chip color="brown">
          <h1>Lista de Producciones del Día Seleccionado:</h1>
        </v-chip>
      </v-card-text>
      <v-container class="text-center">
        <v-btn-toggle color="brown-darken-3" mandatory v-model="toggleEscogido">
          <v-btn class="bg-brown-lighten-2">
            Diario
          </v-btn>
          <v-btn class="bg-brown-lighten-2">
            Mensual
          </v-btn>

        </v-btn-toggle>
      </v-container>
      <v-data-table v-if="toggleEscogido === 0" :headers="headersDia" :items="itemsDia">
        <template v-slot:no-data>
          <v-btn color="brown">
            Seleccione una Fecha
          </v-btn>
        </template>
      </v-data-table>

      <v-data-table v-else :headers="headersMes" :items="itemsMes" :sort-by="[{ key: 'calories', order: 'asc' }]">
        <template v-slot:no-data>
          <v-btn color="brown">
            Seleccione una Fecha
          </v-btn>
        </template>
      </v-data-table>



    </v-col>
    <v-col cols="7">
      <v-card elevation="5" height="500">
        <v-card-title class="headline text-center">Dashboard de Análisis</v-card-title>
        <v-card-subtitle class="text-center">Enero 2024</v-card-subtitle>

        <!-- Datos -->
        <v-container>

          <v-row>
            <v-col cols="4">
              <v-card height="170">
                <v-card-title class="text-center pb-0">
                  <h3>Planificación</h3>
                </v-card-title>
                <v-card-title class="text-center pt-0 pb-0 ">
                  <h5>Mensual</h5>
                </v-card-title>
                <v-card-text class="text-center">{{ valuePlanificacionMensual }}</v-card-text>
                <v-card-title class="text-center pt-0 pb-0 ">
                  <h5>Diaria</h5>
                </v-card-title>
                <v-card-text class="text-center">{{ valuePlanificacionDiaria }}</v-card-text>

              </v-card>
            </v-col>

            <v-col cols="4">
              <v-card height="170">
                <v-card-title class="text-center">
                  <h3>Producción Total del Día</h3>
                </v-card-title>
                <v-card-text class="text-center">{{ valueProduccionTotalDia }}</v-card-text>
              </v-card>
            </v-col>

            <v-col cols="4">
              <v-card height="170">
                <v-card-title class="text-center">
                  <h3>Producción hasta la fecha</h3>
                </v-card-title>
                <v-card-text class="text-center">{{ valueProduccionTotal }}</v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
        <v-container>

          <v-row justify="center">
            <v-col v-if="toggleEscogido === 0" cols="6">
              <v-card height="150">
                <v-card-title class="text-center">Cumplimiento del Plan Diario</v-card-title>
                <v-card-text class="text-center">
                  <v-progress-circular :rotate="360" :size="90" :width="15" :model-value="valuePorcentajeCumpDiario"
                    color="success">
                    <template v-slot:default> {{ valuePorcentajeCumpDiario }} % </template>
                  </v-progress-circular> </v-card-text>
              </v-card>
            </v-col>

            <v-col v-else cols="6">
              <v-card height="150">
                <v-card-title class="text-center">Cumplimiento del Plan Mensual</v-card-title>
                <v-card-text class="text-center">
                  <v-progress-circular :rotate="360" :size="90" :width="15" :model-value="valuePorcentajeCumpMensual" color="success">
                    <template v-slot:default> {{ valuePorcentajeCumpMensual }} % </template>
                  </v-progress-circular>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-col>
  </v-row>
</template>


<script>
import axios from 'axios'

export default {
  data: () => ({
    valueProduccionTotal: 0,
    valueProduccionTotalDia: 0,
    valuePlanificacionMensual: 0,
    valuePlanificacionDiaria: 0,
    valuePorcentajeCumpDiario: 0,
    valuePorcentajeCumpMensual: 0,
    toggleEscogido: 1,
    fechaSelected: null,
    fecha: null,
    dialog: false,
    dialogDelete: false,
    itemsMes: [],
    itemsDia: [],
    headersDia: [
      {
        title: 'Vitola',
        key: 'nombreVitola',
        align: 'center'
      },
      { title: 'Categoría', key: 'categoria', align: 'center' },
      { title: 'Brigada', key: 'numBrigada', align: 'center' },
      { title: 'Cantidad de Trabajadores', key: 'cantTrabajadores', align: 'center' },
      { title: 'Cantidad', key: 'cant', align: 'center' },
    ],
    headersMes: [
      {
        title: 'Día',
        key: 'dia',
        align: 'start'
      },
      { title: 'Día de la semana', key: 'dia_semana', align: 'start' },
      { title: 'Total trabajadores', key: 'tot_trabajadores', align: 'start' },
      {
        title: 'Análisis', align: 'center', children: [
          { title: 'Producción total', key: 'prodTotal', align: 'start' },
          { title: 'Plan Diario', key: 'planDiario', align: 'start' },
          { title: 'Porcentaje de Cumplimiento', key: 'porcentaje', align: 'center' },
        ]
      },
    ],

  }),
  created() {
  },

  methods: {
    async initializeDia() {
      const response = await axios.get(`http://127.0.0.1:8000/api/produccions/dia=${this.fecha.getDate()}/mes=${this.fecha.getMonth() + 1}/anno=${this.fecha.getFullYear()}`)

      this.itemsDia = response.data.producciones.map(produccion => {
        return {
          nombreVitola: produccion.vitola.nombre,
          categoria: produccion.vitola.categoria,
          cantTrabajadores: produccion.cant_trabajadores,
          cant: produccion.cant_producida,
          numBrigada: produccion.brigada.numero
        };
      })
      this.calcularProduccionTotalDia()


    },
    async initializeMes() {
      try {

        const response = await axios.get(`http://127.0.0.1:8000/api/produccions/mes=${this.fecha.getMonth() + 1}/anno=${this.fecha.getFullYear()}`);

        this.itemsMes = Object.keys(response.data.dias).map(key => {
          const diaInfo = response.data.dias[key];

          // Puedes calcular el día de la semana aquí usando el objeto Date
          const fechaDia = new Date(this.fecha.getFullYear(), this.fecha.getMonth(), diaInfo.dia);
          const opcionesDiaSemana = { weekday: 'long' }; // Ajusta las opciones según tus necesidades
          const diaSemana = fechaDia.toLocaleDateString('es-ES', opcionesDiaSemana);

          return {
            dia: diaInfo.dia,
            dia_semana: diaSemana,
            tot_trabajadores: diaInfo.cant_trabajadores,
            prodTotal: diaInfo.cant_producida,
            planDiario: response.data.planDiario,
            porcentaje: `${diaInfo.porcentaje_cumplimiento} %`,
          };
        });
      } catch (error) {
        console.error(error);
      }
    },
    async selected() {
      try {

        const fecha = new Date(`${this.fechaSelected}T00:00:00`)
        this.fecha = fecha
        this.initializeDia()
        this.initializeMes()
        this.obtenerPlanificacionMensual()
        const responsefecha = await axios.get(`http://127.0.0.1:8000/api/fechas/dia=${fecha.getDate()}/mes=${fecha.getMonth() + 1}/anno=${fecha.getFullYear()}`);
        const response = await axios.get(`http://127.0.0.1:8000/api/produccions/porcentajeDiario/${responsefecha.data.fecha_id}`);
        this.valuePorcentajeCumpDiario = response.data
        const cantTotalMes = await axios.get(`http://127.0.0.1:8000/api/produccionsTotalMes/mes=${fecha.getMonth() + 1}/anno=${fecha.getFullYear()}`);
        this.valueProduccionTotal = cantTotalMes.data.suma_produccion_mes
        if (this.valuePlanificacionMensual !== 0) {
          this.valuePorcentajeCumpMensual = ((this.valueProduccionTotal / this.valuePlanificacionMensual) * 100).toFixed(2)
        }
      } catch (error) {
        console.error(error)
      }
    },
    async obtenerPlanificacionMensual() {
      try {
        const responseMensual = await axios.get(`http://127.0.0.1:8000/api/planificacions/mes=${this.fecha.getMonth() + 1}/anno=${this.fecha.getFullYear()}`);
        const responseDiaria = await axios.get(`http://127.0.0.1:8000/api/planificacions/${responseMensual.data.id}`);
        this.valuePlanificacionMensual = responseMensual.data.planificacionMensual
        this.valuePlanificacionDiaria = responseDiaria.data
      } catch (error) {
        console.error(error)
      }
    },

    calcularProduccionTotalDia() {
      let cantidadTotal = 0
      this.itemsDia.forEach(element => {
        cantidadTotal += element.cant
      });
      console.log(cantidadTotal)

      this.valueProduccionTotalDia = cantidadTotal
    }

  },

}
</script>