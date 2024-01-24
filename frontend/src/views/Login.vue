<template>
  <v-container>
    <v-row justify="center" class="mt-16">
      <v-col sm="8" md="5">
        <v-card class="elevation-12 rounded-xl">
          <v-container align="center" class="pb-0">
            <v-img src="../images/logo.jpg" class="rounded-circle" width="110"></v-img>
          </v-container>
          <v-card-title align="center" class="text-h5">
            <v-icon class="mr-2">mdi-account</v-icon>
            Inicio de sesión
          </v-card-title>
          <v-card-text>
            <v-form v-model="form" @submit.prevent="login">
              <v-row>
                <v-col cols="12">
                  <v-text-field :rules="[rules.required]" v-model="email" label="Correo" prepend-icon="mdi-email"
                    required></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field :rules="[rules.required]" v-model="password" @click:append="cambiarVisibilidadPass"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'" :type="showPassword ? 'text' : 'password'"
                    label="Contraseña" prepend-icon="mdi-lock" required></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-row justify="center">
                    <span v-if="credencialesIncorrectas" class="error-message">Credenciales incorrectas</span>
                  </v-row>
                  <v-row justify="center">
                    <v-col cols="8">
                      <v-btn :disabled="!form" type="submit" color="primary" append-icon="mdi-login" block>Iniciar
                        sesión</v-btn>
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
import axios from 'axios';
import authService from '../services/authService';

export default {
  mounted() {
    // Emitir evento para indicar que no se debe mostrar el NavBar
    this.$emit('mostrarNavBarEvent', false);
  },
  data() {
    return {
      credencialesIncorrectas: false,
      form: false,
      email: '',
      password: '',
      showPassword: false,
      rules: {
        required: value => !!value || 'Complete el campo de texto',
      },
    };
  },
  methods: {
    async login() {
      try {
        const success = await authService.login(this.email, this.password);

        // Verificar si la respuesta es exitosa
        if (success) {
          // Redirigir a la página después de iniciar sesión (por ejemplo, la página de inicio)
          this.$router.push('/home');
        } else {
          // Mostrar mensaje de error si la respuesta no es exitosa
          console.error("Error en la respuesta del servidor");
          this.credencialesIncorrectas = true;

        }
      } catch (error) {
        // Mostrar mensaje de error si hay un error en la petición
        console.error("Error en la petición:", error);

        // Puedes mostrar un mensaje específico si las credenciales son incorrectas
        console.log("Credenciales incorrectas");
      }
    },

    cambiarVisibilidadPass() {
      this.showPassword = !this.showPassword;
    }
  }
};
</script>

<style scoped>
.error-message {
  color: red;
}
</style>
