
<template>
    <v-toolbar v-if="mostrarNavBar" color="brown" dark>
      <v-app-bar-nav-icon color="white" @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title class="headline"
        :style="{ color: 'white', fontStyle: 'italic', textShadow: '2px 2px 4px rgba(0, 0, 0, 0.2)', textAlign: 'center', margin: '0 ', fontSize: '24px', fontWeight: 'bold', }">Sistema
        de Gestión Tabacalera | Tabacuba</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn class="headline" :style="{ color: 'white', fontStyle: 'italic' }" rounded="xl" size="large">
        <v-icon left>mdi-logout</v-icon>
      </v-btn>
    </v-toolbar>


    <v-navigation-drawer app v-model="drawer" temporary
      style="width: 300px; height: 100%; margin: 0; padding: 0; overflow-y: auto;">
      <v-layout style="height: 100%; margin: 0; padding: 0;">

        <v-navigation-drawer color="brown" theme="dark" permanent
          style="height: 100%; width: 100%; margin: 0; padding: 0;">
          <v-list color="transparent">


            <v-list>
              <v-list-group prepend-icon="mdi-factory" value="Producción">
                <template v-slot:activator="{ props }">
                  <v-list-item v-bind="props" title="Producción"></v-list-item>
                </template>

                <v-list-item v-for="([title, icon], i) in produccion" :key="i" :title="title" :prepend-icon="icon"
                  :value="title"></v-list-item>
              </v-list-group>

              <v-list-group prepend-icon='mdi-account-group' value="Brigadas">
                <template v-slot:activator="{ props }">
                  <v-list-item v-bind="props" title="Brigadas"></v-list-item>
                </template>

                <v-list-item v-for="([title, icon], i) in brigada" :key="i" :value="title" :title="title"
                  :prepend-icon="icon"></v-list-item>
              </v-list-group>

              <v-list-group prepend-icon='mdi-calendar-multiselect' value="Planificación">
                <template v-slot:activator="{ props }">
                  <v-list-item v-bind="props" title="Planificación"></v-list-item>
                </template>

                <v-list-item v-for="([title, icon], i) in planificacion" :key="i" :value="title" :title="title"
                  :prepend-icon="icon"></v-list-item>
              </v-list-group>
            </v-list>


          </v-list>



          <template v-slot:append>
            <div class="pa-2">
              <v-container>
                <v-row justify="center">
                  <v-col cols="auto">
                    <v-btn density="compact" color="white">Gestionar Usuario</v-btn>
                  </v-col>

                  <v-col cols="auto">
                    <v-btn density="comfortable" color="white">Gestionar Empleado</v-btn>
                  </v-col>

                  <v-col cols="auto">
                    <v-btn @click="goBack" density="default" color="white" block rounded="xl" size="large">Atrás</v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </div>
          </template>
        </v-navigation-drawer>
      </v-layout>
    </v-navigation-drawer>

<!-- 
    <div class="text-center" style="position: fixed; bottom: 20px; width: 100%;">
      <v-btn append-icon="mdi-open-in-new" color="brown" @click="overlay = !overlay">
        Aplicacion de Inicio
      </v-btn>

      <v-overlay :model-value="overlay" class="align-center justify-center">
        <v-progress-circular color="primary" indeterminate size="64"></v-progress-circular>
      </v-overlay>
    </div> -->

</template>


<script>
export default {
  data() {
    return {
      overlay: false,
      drawer: false,
      open: ['Producción'],
      produccion: [
        ['Agregar Producción', 'mdi-plus-outline'],
        ['Ver Producción', 'mdi-eye'],
        ['Modificar Producción', 'mdi-pencil'],
      ],
      brigada: [
        ['Agregar Brigada', 'mdi-plus-outline'],
        ['Modificar Brigada', 'mdi-pencil'],
      ],
      planificacion: [
        ['Agregar Planificación del mes', 'mdi-plus-outline'],
      ],
      mostrarNavBar: true,
    };
  },
  watch: {
    overlay(val) {
      if (val) {
        setTimeout(() => {
          this.overlay = false;
        }, 3000);
      }
    },
    '$route'(to, from) {
      this.actualizarMostrarNavBar(to);
    },
  },
  methods: {
    actualizarMostrarNavBar(valor) {
      this.mostrarNavBar = valor;
    },
    goBack() {
      this.drawer = false;
    },
    // ... otras funciones ...
  },
};
</script>

