<template>
  <v-container fluid class="fill-height">
    <v-row align="center" justify="center">
      <v-col xs="12" sm="12" md="12" lg="12">
        <v-card class="elevation-12">
          <v-card-title class="text-h5">
            <v-icon class="mr-2">mdi-account</v-icon>
            Inicio de sesión
          </v-card-title>
          <v-card-text>
            <v-form @submit.prevent="login">
              <v-row align="center">
                <v-col cols="12">
                  <v-text-field v-model="username" label="Usuario" prepend-icon="mdi-account" required></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="password"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showPassword ? 'text' : 'password'"
                    label="Contraseña"
                    prepend-icon="mdi-lock"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-row align="center">
                    <v-col>
                      <v-switch v-model="showPassword" label="Mostrar contraseña"></v-switch>
                    </v-col>
                    <v-col>
                      <v-btn type="submit" color="primary" block>Iniciar sesión</v-btn>
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
    }
  }
};
</script>

<style scoped>
.fill-height {
  height: 100%;
}

</style>