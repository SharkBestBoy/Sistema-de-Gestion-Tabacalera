
<template>
  <v-toolbar v-if="mostrarNavBar" color="brown" dark>
    <v-app-bar-nav-icon color="white" @click="drawer = !drawer"></v-app-bar-nav-icon>
    <v-toolbar-title class="text-h5">Sistema
      de Gestión Tabacalera | Tabacuba</v-toolbar-title>
    <v-btn rounded="xl" size="x-large"
      @click="logoutUser">
      <v-icon>mdi-logout</v-icon>
    </v-btn>
  </v-toolbar>


  <v-navigation-drawer app v-model="drawer" temporary
    style="width: 300px; height: 100%; margin: 0; padding: 0; overflow-y: auto;">
    <v-layout style="height: 100%; margin: 0; padding: 0;">

      <v-navigation-drawer color="brown" theme="dark" permanent style="height: 100%; width: 100%; margin: 0; padding: 0;">
        <v-list color="transparent">
          <v-container align="center" class="pb-0">
            <v-img src="../images/logo.jpg" class="rounded-circle" width="80"></v-img>
          </v-container>
          <v-list>
            <v-list-item @click="goToHome" prepend-icon="mdi-home">
              Principal
            </v-list-item>
            <v-list-group prepend-icon="mdi-factory" value="Producción">
              <template v-slot:activator="{ props }">
                <v-list-item v-bind="props" title="Producción"></v-list-item>
              </template>
              <v-list-item @click="goToAgregarProduccion" prepend-icon="mdi-plus-outline">
                Agregar producción
              </v-list-item>
              <v-list-item @click="goToMostrarProducciones" prepend-icon="mdi-plus-outline">
                Mostrar producciones
              </v-list-item>

            </v-list-group>

            <v-list-group prepend-icon='mdi-account-group' value="Brigadas">
              <template v-slot:activator="{ props }">
                <v-list-item v-bind="props" title="Brigadas"></v-list-item>
              </template>

              <v-list-item @click="goToGestionarBrigada" prepend-icon="mdi-plus-outline">
                Gestionar brigada
              </v-list-item>
              <v-list-item @click="goToGestionarEmpleado" prepend-icon="mdi-plus-outline">
                Gestionar empleados
              </v-list-item>
            </v-list-group>

            <v-list-group prepend-icon='mdi-calendar-multiselect' value="Planificación">
              <template v-slot:activator="{ props }">
                <v-list-item v-bind="props" title="Planificación"></v-list-item>
              </template>

              <v-list-item @click="goToGestionarPlanificacion" prepend-icon="mdi-plus-outline">
                Gestionar planificación
              </v-list-item>
            </v-list-group>
          </v-list>


        </v-list>



        <template v-slot:append>
          <div class="pa-2">
            <v-container>
              <v-row justify="center">
                <v-col cols="auto">
                  <v-btn @click="goToGestionarUsuario" density="compact" color="white">Gestionar Usuario</v-btn>
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
import axios from 'axios';
import authService from '@/services/authService'; // Ajusta la ruta según tu estructura de archivos

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
    logoutUser() {
      authService.logout(); // Llamada a la función logout del servicio de autenticación
      this.$router.push('/login'); // Redirige al usuario a la página de inicio de sesión

    },
    actualizarMostrarNavBar(valor) {
      this.mostrarNavBar = valor;
    },
    goBack() {
      this.drawer = false;
    },
    goToAgregarProduccion() {
      this.$router.push("addProduccion")
    },
    goToMostrarProducciones() {
      this.$router.push("mostrarProducciones")
    },
    goToGestionarEmpleado() {
      this.$router.push("gestionarEmpleados")

    },
    goToGestionarUsuario() {
      this.$router.push("gestionarUsuarios")

    },
    goToGestionarBrigada() {
      this.$router.push("gestionarBrigada")

    },
    goToGestionarPlanificacion() {
      this.$router.push("gestionarPlanificacion")

    },
    goToHome() {
      this.$router.push("home")

    }


  },
};
</script>

