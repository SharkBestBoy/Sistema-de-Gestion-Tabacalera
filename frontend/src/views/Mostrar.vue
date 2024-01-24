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
          <v-btn color="primary">
            Reset
          </v-btn>
        </template>
      </v-data-table>

      <v-data-table v-else :headers="headersMes" :items="itemsMes" :sort-by="[{ key: 'calories', order: 'asc' }]">
        <template v-slot:no-data>
          <v-btn color="primary">
            Reset
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
              <v-card>
                <v-card-title class="text-center pb-0">
                  <h3>Planificación</h3>
                </v-card-title>
                <v-card-title class="text-center pt-0 pb-0 ">
                  <h5>Mensual</h5>
                </v-card-title>
                <v-card-text class="text-center">6520</v-card-text>
                <v-card-title class="text-center pt-0 pb-0 ">
                  <h5>Diaria</h5>
                </v-card-title>
                <v-card-text class="text-center">200</v-card-text>

              </v-card>
            </v-col>

            <v-col cols="4">
              <v-card height="150">
                <v-card-title class="text-center">
                  <h3>Producción Total del Día</h3>
                </v-card-title>
                <v-card-text class="text-center">200</v-card-text>
              </v-card>
            </v-col>

            <v-col cols="4">
              <v-card height="150">
                <v-card-title class="text-center">
                  <h3>Producción hasta la fecha</h3>
                </v-card-title>
                <v-card-text class="text-center">871</v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
        <v-container>

          <v-row>
            <v-col cols="6">
              <v-card v-if="toggleEscogido === 0" height="150">
                <v-card-title class="text-center">Porcentaje de Cumplimiento del Plan Diario</v-card-title>
                <v-card-text class="text-center">
                  <v-progress-circular :rotate="360" :size="90" :width="15" :model-value="valuePorcentajeCumpDiario" color="success">
                    <template v-slot:default> {{ valuePorcentajeCumpDiario }} % </template>
                  </v-progress-circular> </v-card-text>
              </v-card>
            </v-col>

            <v-col cols="6">
              <v-card height="150">
                <v-card-title class="text-center">Porcentaje de Cumplimiento del Plan Mensual</v-card-title>
                <v-card-text class="text-center">
                  <v-progress-circular :rotate="360" :size="90" :width="15" :model-value="value" color="success">
                    <template v-slot:default> {{ value }} % </template>
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
    valuePorcentajeCumpDiario: 0,
    toggleEscogido: 1,
    value: 60,
    fechaSelected: null,
    fecha: null,
    dialog: false,
    dialogDelete: false,
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
    itemsDia: [],
    headersMes: [
      {
        title: 'Día',
        key: 'dia',
        align: 'center'
      },
      { title: 'Día de la semana', key: 'dia_semana', align: 'center' },
      { title: 'Total trabajadores', key: 'tot_trabajadores', align: 'center' },
      {
        title: 'Análisis', align: 'center', children: [
          { title: 'Producción total', key: 'prodTotal', align: 'center' },
          { title: 'Plan Diario', key: 'planDiario', align: 'center' },
          { title: 'Porcentaje de Cumplimiento', key: 'porcentaje', align: 'center' },
        ]
      },
    ],
    itemsMes: [],
    editedIndex: -1,
    editedItem: {
      name: '',
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0,
    },
    defaultItem: {
      name: '',
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0,
    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
    },
  },

  watch: {

  },

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

    },
    async initializeMes() {
      try {

        const response = await axios.get(`http://127.0.0.1:8000/api/produccions/mes=${this.fecha.getMonth() + 1}/anno=${this.fecha.getFullYear()}`);

        console.log(response.data);

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
            porcentaje: diaInfo.porcentaje_cumplimiento,
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
        const responsefecha = await axios.get(`http://127.0.0.1:8000/api/fechas/dia=${fecha.getDate()}/mes=${fecha.getMonth() + 1}/anno=${fecha.getFullYear()}`);
        const response = await axios.get(`http://127.0.0.1:8000/api/produccions/porcentajeDiario/${responsefecha.data.fecha_id}`);
        this.valuePorcentajeCumpDiario = response.data
      } catch (error) {
        console.error(error)
      }
    },

    editItem(item) {
      this.editedIndex = this.itemsDia.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem(item) {
      this.editedIndex = this.itemsDia.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },

    deleteItemConfirm() {
      this.itemsDia.splice(this.editedIndex, 1)
      this.closeDelete()
    },

    close() {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    closeDelete() {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    save() {
      if (this.editedIndex > -1) {
        Object.assign(this.itemsDia[this.editedIndex], this.editedItem)
      } else {
        this.itemsDia.push(this.editedItem)
      }
      this.close()
    },
  },

}
</script>