<template>
    <v-container>
        <v-row>
            <v-col cols="12" class="py-2 text-center">
                <v-container>

                    <v-btn-toggle v-model="selectedBrigada" color="brown">
                        <v-btn v-for=" i in brigadas" :key="i">
                            Brigada# {{ i.numero }}
                        </v-btn>

                    </v-btn-toggle>
                    <v-btn :disabled="!isEnable" @click="eliminarBrigada" color="red">
                        <v-icon> mdi-delete </v-icon>
                    </v-btn>
                </v-container>

                <v-spacer></v-spacer>

                <v-btn color="green" @click="dialog = true" class="ml-auto">
                    Crear Nueva Brigada
                </v-btn>

            </v-col>

            <v-col cols="5">
                <v-card class="mx-auto" max-width="600">
                    <v-card-title>
                        Empleados
                        <v-card-subtitle>
                            Brigada{{ subtitleText }}:
                        </v-card-subtitle>
                    </v-card-title>
                    <v-divider :thickness="8" class="border-opacity-50" color="brown"></v-divider>
                    <v-list v-model="empleadoConBrigadaSelected" @click:select="onEmpleadoConBrigadaSelected" color="brown" :items="empleadosBrigada" item-title="datos" item-value="id"></v-list>
                </v-card>
            </v-col>

            <v-col cols="2" class="d-flex flex-column align-center mt-4">
                <v-spacer></v-spacer>


                <v-btn @click="asignarBrigada" color="green" class="mb-2">
                    <v-icon>mdi-chevron-left</v-icon>
                </v-btn>

                <v-btn color="green" @click="desAsignarBrigada" class="mb-2">
                    <v-icon>mdi-chevron-right</v-icon>
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn @click="aceptar" color="green" class="mx-auto">
                    Aceptar
                </v-btn>

            </v-col>

            <v-col cols="5">
                <v-card title="Empleados" subtitle="Sin brigadas" class="mx-auto" max-width="600">
                    <v-divider :thickness="8" class="border-opacity-50" color="brown"></v-divider>
                    <v-list v-model="empleadoSinBrigadaSelected" @click:select="onEmpleadoSinBrigadaSelected" color="brown"
                        :items="empleadosSinBrigada" item-title="datos" item-value="id"></v-list>

                    <!-- <v-list-item  color="brown" v-for="empleado in empleadosSinBrigada" :key="empleado.id"
                        @click="btnAsignarEnable=true">
                         @click="selected(empleado)"> -->
                    <!-- <v-list-item-title>{{ empleado.datos }}</v-list-item-title>
                        </v-list-item> -->
                </v-card>
            </v-col>
        </v-row>
    </v-container>

    <v-dialog v-model="dialog" max-width="350px" persistent>
        <v-card>
            <v-card-title>
                <span class="text-h5">Introduzca el # de la brigada</span>
            </v-card-title>

            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="12" sm="12">
                            <v-text-field v-model="numBrigada" label="# Brigada" type="number" min="1"></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue-darken-1" variant="text" @click="dialog = false">
                    Cancelar
                </v-btn>
                <v-btn color="blue-darken-1" variant="text" @click="crearNuevaBrigada">
                    Aceptar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <v-snackbar v-model="snackbar">
        {{ mensaje }}

        <template v-slot:actions>
            <v-btn color="pink" variant="text" @click="snackbar = false">
                Cerrar
            </v-btn>
        </template>
    </v-snackbar>
</template>
  
<script>
import axios from 'axios';

export default {
    data: () => ({
        btnAsignarEnable: false,
        empleadoSinBrigadaSelected: null,
        empleadoConBrigadaSelected: null,
        subtitleText: '',
        numBrigada: '',
        dialog: false,
        isEnable: false,
        selectedBrigada: '',
        text: null,
        brigadas: [],
        empleadosBrigada: [],
        empleadosSinBrigada: [],
        snackbar: false,
        mensaje: '',
    }),
    async created() {
        this.obtenerEmpleadosSinBrigada()
        this.obtenerBrigadas()
    },
    watch: {
        empleadoSinBrigadaSelected(newValue) {
            console.log('Nuevo valor seleccionado:', this.empleadoSinBrigadaSelected);

        },

        selectedBrigada: {
            handler: 'activarEliminar', // Nombre del método a ejecutar
            deep: true, // Observa cambios profundos en la propiedad (útil para arrays u objetos anidados)
        },
    },

    methods: {
        async obtenerBrigadas() {
            const response = await axios.get('http://127.0.0.1:8000/api/brigadas')
            this.brigadas = response.data.brigadas

        },
        async crearNuevaBrigada() {
            try {
                if (this.numBrigada === '') {
                    this.snackbar = true
                    this.mensaje = "Eliga el número de la brigada!"
                } else {
                    const nuevaBrigada = { numero: this.numBrigada };
                    const response = await axios.post('http://127.0.0.1:8000/api/brigadas', nuevaBrigada)
                    this.brigadas.push(nuevaBrigada);
                    this.obtenerBrigadas()
                    this.dialog = false;
                    this.numBrigada = ''
                }
            } catch (error) {
                this.snackbar = true
                this.mensaje = "El número de la brigada ya existe!"
            }
        },

        async obtenerEmpleadosSinBrigada() {

            const response = await axios.get('http://127.0.0.1:8000/api/empleados-sinBrigada')
            const nombre_apellidos = response.data.empleados.map(empleado =>
            ({
                datos: `CI: ${empleado.ci} || ${empleado.nombre} ${empleado.apellidos}`,
                id: empleado.id
            }))
            this.empleadosSinBrigada = nombre_apellidos
        },
        activarEliminar() {
            const hayBrigadasSeleccionadas = this.selectedBrigada >= 0;
            const hayBrigadas = this.brigadas.length > 0;
            this.isEnable = hayBrigadas && hayBrigadasSeleccionadas;
            console.log(hayBrigadasSeleccionadas)
            console.log(hayBrigadas)
            console.log(this.isEnable)
            console.log(this.selectedBrigada)
            if (this.isEnable
                && this.selectedBrigada >= 0
                && this.selectedBrigada !== undefined
                && this.selectedBrigada !== '') {
                this.subtitleText = ` #${this.brigadas[this.selectedBrigada].numero}`
                this.obtenerEmpleadosBrigada()
            } else {
                this.subtitleText = ''
                this.empleadosBrigada= []

            }


        },

        async eliminarBrigada() {
            try {
                const brigadaSeleccionada = this.selectedBrigada
                const brigada_id = this.brigadas[this.selectedBrigada].id
                const response = await axios.delete(`http://127.0.0.1:8000/api/brigadas/${brigada_id}`)
                this.brigadas.splice(brigadaSeleccionada, 1)
                this.selectedBrigada = ''
                this.activarEliminar()
                this.obtenerEmpleadosSinBrigada()
                this.empleadosBrigada = []

            } catch (error) {
                console.error('Error al eliminar la brigadaaaaa', error)
            }
        },
        async obtenerEmpleadosBrigada() {
            try {
                const brigada_id = this.brigadas[this.selectedBrigada].id

                const response = await axios.get(`http://127.0.0.1:8000/api/empleados-conBrigada/${brigada_id}`)

                this.empleadosBrigada = response.data.empleados.map(empleado =>
                ({
                    datos: `CI: ${empleado.ci} || ${empleado.nombre} ${empleado.apellidos}`,
                    id: empleado.id
                }))
            } catch (error) {
                console.error('Error al eliminar la brigadaaaaa', error)
            }
        },
        async asignarBrigada() {
            try {
                console.log(this.selectedBrigada)
             
                if (this.selectedBrigada == undefined) {
                    this.snackbar = true
                    this.mensaje = 'Debe seleccionar una brigada'
                } else {
                    if (this.empleadoSinBrigadaSelected == null) {
                        this.snackbar = true
                        this.mensaje = 'Debe seleccionar una empleado sin brigada'
                    } else {
                        const empleado_id = this.empleadoSinBrigadaSelected
                        const brigada_id = this.brigadas[this.selectedBrigada].id
                        const response = await axios.post(`http://127.0.0.1:8000/api/empleados/brigada_${brigada_id}/empleado_${empleado_id}`)
                        this.obtenerEmpleadosSinBrigada()
                        this.obtenerEmpleadosBrigada()
                    }
                }
            } catch (error) {
                console.error('Error al asignar el empleado a la brigada', error)
            }
        },
        async desAsignarBrigada() {
            try {
                if (this.empleadoConBrigadaSelected == null) {
                    this.snackbar = true
                    this.mensaje = 'Debe seleccionar una empleado de la brigada'
                } else {
                    const empleado_id = this.empleadoConBrigadaSelected
                    const response = await axios.post(`http://127.0.0.1:8000/api/empleados/empleado_${empleado_id}`)
                    this.obtenerEmpleadosSinBrigada()
                    this.obtenerEmpleadosBrigada()
                }

            } catch (error) {
                console.error('Error al asignar el empleado a la brigada', error)
            }
        },

        onEmpleadoSinBrigadaSelected(item) {

            if (item) {
                console.log('Empleado seleccionado:', item.id);
                this.empleadoSinBrigadaSelected = item.id
            } else {
                this.empleadoSinBrigadaSelected = ''
                console.log('Empleado seleccionado:', item);

            }

        },
        onEmpleadoConBrigadaSelected(item) {

            if (item) {
                console.log('Empleado seleccionado:', item.id);
                this.empleadoConBrigadaSelected = item.id
            } else {
                this.empleadoConBrigadaSelected = ''
                console.log('Empleado seleccionado:', item);

            }

        },
        aceptar() {
            console.log(this.empleadoSinBrigadaSelected)
        },

        activarBtnAsignar() {

            if (this.empleadoSinBrigadaSelected === '') {
                this.btnAsignarEnable = !this.btnAsignarEnable
            }
        }
    }
};
</script>
  