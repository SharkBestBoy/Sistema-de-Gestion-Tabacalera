<template>
    <v-data-table :headers="headers" :items="users">
        <template v-slot:top>
            <v-toolbar color="brown" flat class="rounded-xl">
                <v-toolbar-title>Lista de Usuarios</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="500px">
                    <template v-slot:activator="{ props }">
                        <v-btn dark class="mb-2" v-bind="props">
                            <v-icon class="mr-2">mdi-account-plus</v-icon>
                            Nuevo Usuario
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="text-h5">Añadir Usuario</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12" sm="8" md="8">
                                        <v-text-field v-model="editedItem.name" label="Nombre de Usuario"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="12">
                                        <v-text-field v-model="editedItem.email" label="Correo"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="10" md="10">
                                        <v-text-field v-model="editedItem.password" label="Contraseña"
                                            type="password"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="10" md="10">
                                        <v-text-field v-model="editedItem.password_confirmation"
                                            label="Confirmar Contraseña" type="password"></v-text-field>
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
                        <v-card-title class="text-h5">¿Desea eliminar este usuario?</v-card-title>
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
        headers: [
            {
                title: 'Nombre de Usuario',
                align: 'start',
                sortable: false,
                key: 'name',
            },
            { title: 'Correo', key: 'email', align: 'center', sortable: false, },
            { title: 'Actions', key: 'actions', align: 'center', sortable: false },
        ],
        users: [],
        editedIndex: -1,
        editedItem: {
            name: '',
            email: '',
            password: '',
            password_confirmation: ''
        },
        defaultItem: {
            name: '',
            email: '',
            password: '',
            password_confirmation: ''
        },
    }),

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
                const response = await axios.get('http://127.0.0.1:8000/api/users')
                console.log(response)
                this.users = response.data.users
            } catch (error) {
                console.error('Error al obtener los usuarios', error)
            }
        },
        deleteItem(item) {
            this.editedIndex = this.users.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },
        async deleteItemConfirm() {
            try {
                if (this.editedItem.email == 'admin@gmail.com') {
                    this.snackbar = true
                    this.mensaje = 'El usuario admin no se puede eliminar'
                } else {
                    await axios.delete(`http://127.0.0.1:8000/api/users-delete/${this.editedItem.name}`)
                    this.users.splice(this.editedIndex, 1)
                    this.closeDelete()
                    this.snackbar = true
                    this.mensaje = 'Usuario eliminado!'
                }
            } catch (error) {
                console.error('Error al eliminar el usuario', error)
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
                if (this.editedItem.name === '' || this.editedItem.email === '' || this.editedItem.password === '' || this.editedItem.password_confirmation === '') {
                    this.snackbar = true
                    this.mensaje = 'Llena todos los campos!'
                } else if (this.editedItem.password !== this.editedItem.password_confirmation) {
                    this.snackbar = true;
                    this.mensaje = 'Las contraseñas no coinciden';
                } else {
                    await axios.post('http://127.0.0.1:8000/api/register', this.editedItem)
                    this.users.push(this.editedItem)
                    this.close()
                }

            } catch (error) {
                console.error("Error al guardar los datos", error)
            }
        }
    },
}
</script>