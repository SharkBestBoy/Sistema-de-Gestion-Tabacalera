<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="6" align="center">
                <h1>{{ dia }} de {{mes}} del {{anno}}</h1>
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
                                <p>Cantidad de Brigadas: {{ cantidadBrigadas }}</p>
                                <p>Cantidad de Empleados: {{ cantidadBrigadas }}</p>
                                <p>Cantidad de Empleados sin Brigada: {{ cantidadBrigadas }}</p>
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
                                <p>Cantidad de Vitolas: {{ cantidadVitolas }}</p>
                                <p>Cantidad de tabacos de categoría IX: {{ cantidadVitolas }}</p>
                                <p>Cantidad de tabacos de categoría VIII: {{ cantidadVitolas }}</p>
                                <p>Cantidad de tabacos de categoría VII: {{ cantidadVitolas }}</p>
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
                                <p>Total producido: {{ cantidadVitolas }}</p>
                                <p>Total producido en el mes: {{ cantidadVitolas }}</p>
                                <p>Cumplimiento del Plan Mensual: {{ cantidadVitolas }}%</p>
                                <!-- Otros datos resumen sobre vitolas -->
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col> </v-row>
    </v-container>
</template>
  
<script>
export default {
    data() {
        return {
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
    created() {
        this.obtenerDatosResumen();
    },
    methods: {
        async obtenerDatosResumen() {
            const fechaActual = new Date();
            this.dia = fechaActual.getDate()
            this.anno = fechaActual.getFullYear();
            const mesActual = parseInt(fechaActual.getMonth()); // Sumar 1 para obtener un número entre 1 y 12
            this.mes = this.meses[mesActual]

            // Realiza las llamadas a tu API o vuex store para obtener los datos resumen
            // Actualiza los valores de cantidadBrigadas, cantidadVitolas, etc.
            // Ejemplo ficticio con axios:
            try {
                const responseBrigadas = await axios.get('http://tu-api.com/obtener-cantidad-brigadas');
                this.cantidadBrigadas = responseBrigadas.data.cantidad;

                const responseVitolas = await axios.get('http://tu-api.com/obtener-cantidad-vitolas');
                this.cantidadVitolas = responseVitolas.data.cantidad;

                // Actualiza otros datos resumen
            } catch (error) {
                console.error('Error al obtener datos resumen:', error);
            }
        },
    },
};
</script>
  