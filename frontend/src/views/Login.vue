<template>
  <v-container fluid class="fill-height">
    <v-row align="center" justify="center" class="fill-height">
      <v-col xs="12" sm="8" md="6" lg="4" class="mt-16">
        <v-card class="elevation-12">
          <v-card-title class="text-h5 text-center" style="color: rgba(165, 42, 42, 0.829);">
            <v-icon class="mr-2" color="brown">mdi-account</v-icon>
            Iniciar Sesión
          </v-card-title>
          <v-card-text>
            <v-form @submit.prevent="login">
              <v-row>
                <v-col cols="12">
                  <v-text-field style="color: rgba(165, 42, 42, 0.829);" v-model="username" label="Usuario" prepend-icon="mdi-account" required></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    style="color: rgba(165, 42, 42, 0.829);"
                    v-model="password"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showPassword ? 'text' : 'password'"
                    label="Contraseña"
                    prepend-icon="mdi-lock"
                    required
                    @click:append="togglePasswordVisibility"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-row align="center">
                    <v-col>
                      <v-btn type="submit" color="brown" block class="mt-4">Continuar</v-btn>
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  mounted() {
    // Emitir evento para indicar que no se debe mostrar el NavBar
    this.$emit('mostrarNavBarEvent', false);
  },
  data() {
    return {
      username: '',
      password: '',
      showPassword: false
    };
  },
  methods: {
    login() {
      console.log('Intento de inicio de sesión:', this.username, this.password);
      // Puedes agregar aquí la lógica de autenticación si es necesario
    },
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword;
    }
  }
};
</script>

<style scoped>
.fill-height {
  height: 100vh;
  margin:o;
}

</style>
