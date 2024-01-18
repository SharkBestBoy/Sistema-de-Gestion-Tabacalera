<template>
    <v-container>
        <v-row>
            <v-col cols="12" class="py-2 text-center">
                <v-container>

                    <v-btn-toggle color="brown">
                        <v-btn v-for=" i in brigadas" :key="i" >
                            Brig
                        </v-btn>
                        
                    </v-btn-toggle>
                </v-container>

                <v-spacer></v-spacer>

                <v-btn color="green" @click="crearNuevaBrigada" class="ml-auto">
                    Crear Nueva Brigada
                </v-btn>

            </v-col>

            <v-col cols="5">
                <v-card title="Empleados" subtitle="Brigada:" class="mx-auto" max-width="300">
                    <v-divider :thickness="8" class="border-opacity-50" color="success"></v-divider>
                    <v-list color="green" :items="items" item-title="name" item-value="id"></v-list>
                </v-card>
            </v-col>

            <v-col cols="2" class="d-flex flex-column align-center mt-4">
                <v-spacer></v-spacer>


                <v-btn color="green" class="mb-2">
                    <v-icon>mdi-chevron-right</v-icon>
                </v-btn>
                <v-btn color="green" class="mb-2">
                    <v-icon> mdi-delete </v-icon>
                </v-btn>
                <v-btn color="green" class="mb-2">
                    <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn color="green" class="mx-auto">
                    Aceptar
                </v-btn>

            </v-col>

            <v-col cols="5">
                <v-card title="Empleados" subtitle="Sin brigadas" class="mx-auto" max-width="300">
                    <v-divider :thickness="8" class="border-opacity-50" color="success"></v-divider>
                    <v-list color="green" :items="item" item-title="name" item-value="id"></v-list>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
  
<script>
export default {
    data: () => ({
        text: null,
        brigadas: [],
        items: [
            { name: 'Item #1', id: 1 },
            { name: 'Item #2', id: 2 },
            { name: 'Item #3', id: 3 },
            { name: 'Item #4', id: 4 },
            { name: 'Item #5', id: 5 },
        ],
        item: [
            { name: 'Item #1', id: 1 },
            { name: 'Item #2', id: 2 },
            { name: 'Item #3', id: 3 },
            { name: 'Item #4', id: 4 },
            { name: 'Item #5', id: 5 },
        ],
    }),
    methods: {
        crearNuevaBrigada() {
            const nuevaBrigada = { name: 'Brigada', id: this.items.length + 1 };

            this.brigadas.push(nuevaBrigada);
        },
    },
};
</script>
  