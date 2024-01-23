<template>
  <v-data-table :headers="headers" :items="desserts" >
    <template v-slot:top >
      <v-toolbar color="brown" flat class="rounded-xl" >
        <v-toolbar-title>Lista de Empleados</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ props }">
            <v-btn dark class="mb-2" v-bind="props">
              <v-icon class="mr-2">mdi-account-plus</v-icon>
              Nuevo Empleado
            </v-btn>
          </template>
          <v-card >
            <v-card-title>
              <span class="text-h5">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" sm="8" md="8">
                    <v-text-field v-model="editedItem.nombre" label="Nombre del empleado"></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="12" md="12">
                    <v-text-field v-model="editedItem.apellidos" label="Apellidos del empleado"></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="10" md="10">
                    <v-text-field v-model="editedItem.ci" label="CI"></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="10" md="10">
                    <v-text-field v-model="editedItem.direccionLocal" label="Direccion local "></v-text-field>
                  </v-col>

                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="close">
                Cancelar
              </v-btn>
              <v-btn color="blue-darken-1" variant="text" @click="save">
                Salvar
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5">¿Desea eliminar este empleado?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="closeDelete">Cancelar</v-btn>
              <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">Eliminar</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon size="small" class="me-2" @click="editItem(item)">
        mdi-pencil
      </v-icon>
      <v-icon size="small" @click="deleteItem(item)">
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize">
        Reset
      </v-btn>

    </template>

  </v-data-table>
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
import axios from 'axios'

export default {
  data: () => ({
    snackbar: false,
    mensaje: '',
    dialog: false,
    dialogDelete: false,
    originalCi:'',
    headers: [
      {
        title: 'CI',
        align: 'start',
        sortable: false,
        key: 'ci',
      },
      { title: 'Nombre del Empleado', key: 'nombre' },
      { title: 'Apellidos del Empleado', key: 'apellidos' },
      { title: 'Direccion Local', key: 'direccionLocal' },
      { title: 'Actions', key: 'actions', sortable: false },
    ],
    desserts: [],
    editedIndex: -1,
    editedItem: {
      ci: '',
      nombre: '',
      apellidos: '',
      direccionLocal: ''
    },
    defaultItem: {
      ci: '',
      nombre: '',
      apellidos: '',
      direccionLocal: '',

    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Añadir Empleado' : 'Editar Empleado'
    },
  },

  watch: {
    dialog(val) {
      val || this.close()
    },
    dialogDelete(val) {
      val || this.closeDelete()
    },
  },

  created() {
    this.initialize()
  },

  methods: {
    async initialize() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/empleados')
        console.log(response)
        this.desserts = response.data.empleados
      } catch (error) {
        console.error('Error al obtener los empleados', error)
      }
    },

    editItem(item) {
      this.editedIndex = this.desserts.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.originalCi=item.ci
      this.dialog = true
    },

    deleteItem(item) {
      this.editedIndex = this.desserts.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },

    async deleteItemConfirm() {
      try {
        await axios.delete(`http://127.0.0.1:8000/api/empleados/${this.editedItem.ci}`)
        this.desserts.splice(this.editedIndex, 1)
        this.closeDelete()
        this.snackbar = true
        this.mensaje = 'Empleado eliminado!'
      } catch (error) {
        console.error('Error al eliminar el empleado', error)
      }
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

    async save() {
      try {
        if (this.editedIndex > -1) {

          if (this.editedItem.ci === '' || this.editedItem.nombre === '' || this.editedItem.apellidos === '' || this.editedItem.direccionLocal === '') {
            this.snackbar = true
            this.mensaje = 'Llena todos los campos!'
          } else {

            const response = await axios.post(`http://127.0.0.1:8000/api/empleados-update/${this.originalCi}`, this.editedItem)
            console.log(response)
            Object.assign(this.desserts[this.editedIndex], this.editedItem)
            this.close()

          }
        } else {
          if (this.editedItem.ci === '' || this.editedItem.nombre === '' || this.editedItem.apellidos === '' || this.editedItem.direccionLocal === '') {
            this.snackbar = true
            this.mensaje = 'Llena todos los campos!'
          } else {
            await axios.post('http://127.0.0.1:8000/api/empleados', this.editedItem)
            this.desserts.push(this.editedItem)
            this.close()
          }
        }
      } catch (error) {
        console.error("Error al guardar los datos")
      }
    }
  },
}
</script>