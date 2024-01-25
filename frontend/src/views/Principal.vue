<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="6" align="center">
                <h1>{{ dia }} de {{ mes }} del {{ anno }}</h1>
            </v-col>

        </v-row>
        <v-row justify="center">
            <v-col cols="3">
                <v-card elevation="5" align="center">
                    <v-card-title>
                        <h2>Resumen de Brigadas</h2>
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <p>Cantidad de Brigadas: {{ cantBrigadas }}</p>
                                <p>Cantidad de Empleados: {{ cantEmpleados }}</p>
                                <p>Cantidad de Empleados sin Brigada: {{ cantEmpleadosSinBrigada }}</p>
                                <!-- Otros datos resumen sobre brigadas -->
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="3">
                <v-card elevation="5" align="center">
                    <v-card-title>
                        <h2>Resumen de Vitolas</h2>
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <p>Número de Vitolas: {{ cantVitolas }}</p>
                                <p>Cantidad de tabacos de categoría IX: {{ cantIX }}</p>
                                <p>Cantidad de tabacos de categoría VIII: {{ cantVIII }}</p>
                                <p>Cantidad de tabacos de categoría VII: {{ cantVII }}</p>
                                <!-- Otros datos resumen sobre vitolas -->
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="3">
                <v-card elevation="5" align="center">
                    <v-card-title>
                        <h2>Resumen de Producción</h2>
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <p>Total producido: {{ cantidadTotalProducida }}</p>
                                <p>Total producido en el mes: {{ cantProducidaMes }}</p>
                                <p>Plan Mensual: {{ planMensual }}</p>
                                <p>Cumplimiento del Plan Mensual: {{ cumplimientoPlan }}%</p>
                                <!-- Otros datos resumen sobre vitolas -->
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col> </v-row>
    </v-container>
</template>
  
<script>
import axios from 'axios';
import { resolveComponent } from 'vue';

export default {
    data() {
        return {
            planMensual: 0,
            cantIX: 0,
            cantVIII: 0,
            cantVII: 0,
            cantBrigadas: 0,
            cantEmpleados: 0,
            cantEmpleadosSinBrigada: 0,
            cantVitolas: 0,
            cantidadTotalProducida: 0,
            cantProducidaMes: 0,
            cumplimientoPlan: 0,
            meses: ['Enero', 'Febrero', 'Marzo',
                'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre',
                'octubre', 'Noviembre', 'Diciembre'],
            dia: null,
            mes: null,
            anno: null,
            cantidadBrigadas: 0, // Obtén esta información desde tu API o vuex store
            cantidadVitolas: 0, // Obtén esta información desde tu API o vuex store
            // Otros datos resumen
        };
    },
    // Puedes utilizar created o mounted para cargar los datos al iniciar la página
    mounted() {
        this.obtenerResumen();
        this.obtenerDatosVitolas()
        this.obtenerDatosProduccion()
    },
    methods: {
        async obtenerResumen() {
            try {
                const fechaActual = new Date();
                this.dia = fechaActual.getDate()
                this.anno = fechaActual.getFullYear();

                const mesActual = parseInt(fechaActual.getMonth());
                this.mes = this.meses[mesActual]

                const cantBrigadas = await axios.get(`http://127.0.0.1:8000/api/brigadas`);
                this.cantBrigadas = cantBrigadas.data.brigadas.length
                const cantEmpleados = await axios.get(`http://127.0.0.1:8000/api/empleados`);
                this.cantEmpleados = cantEmpleados.data.empleados.length
                const cantEmpleadosSinBrigada = await axios.get(`http://127.0.0.1:8000/api/empleados-sinBrigada`);
                this.cantEmpleadosSinBrigada = cantEmpleadosSinBrigada.data.empleados.length
                const cantVitolas = await axios.get(`http://127.0.0.1:8000/api/vitolas`);
                this.cantVitolas = cantVitolas.data.vitolas.length

            } catch (error) {
                console.error(error)
            }
        },
        async obtenerDatosProduccion() {
            const fechaActual = new Date();
            const mesActual = parseInt(fechaActual.getMonth());
            const produccion = await axios.get(`http://127.0.0.1:8000/api/produccions`);
            const sumaTotal = produccion.data.produccions.map(produccion => produccion.cant_producida)
            let cantidadTotalProducida = 0
            sumaTotal.forEach(element => {
                cantidadTotalProducida += element;
            });
            this.cantidadTotalProducida = cantidadTotalProducida
            const cantTotalMes = await axios.get(`http://127.0.0.1:8000/api/produccionsTotalMes/mes=${mesActual + 1}/anno=${this.anno}`);
            this.cantProducidaMes = cantTotalMes.data.suma_produccion_mes

            const planMensual = await axios.get(`http://127.0.0.1:8000/api/planificacions/mes=${mesActual + 1}/anno=${this.anno}`);
            this.planMensual = planMensual.data.planificacionMensual
            if (planMensual.data.existe) {

                this.cumplimientoPlan = ((this.cantProducidaMes / this.planMensual) * 100).toFixed(2)
            } else {
                this.cumplimientoPlan = '-'
            }

        },
        async obtenerDatosVitolas() {

            const cantIX = await axios.get(`http://127.0.0.1:8000/api/produccions_IX`);
            const cantVIII = await axios.get(`http://127.0.0.1:8000/api/produccions_VIII`);
            const cantVII = await axios.get(`http://127.0.0.1:8000/api/produccions_VII`);
            this.cantIX = cantIX.data.suma_produccion_vitola_ix
            this.cantVIII = cantVIII.data.suma_produccion_vitola_viii
            this.cantVII = cantVII.data.suma_produccion_vitola_vii
        }
    },
};
</script>
  