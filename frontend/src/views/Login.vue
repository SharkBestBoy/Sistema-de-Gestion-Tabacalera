<template>
  <v-container>
    <v-row justify="center" class="mt-16">
      <v-col  sm="8" md="5">
        <v-card class="elevation-12 rounded-xl">
          <v-container align="center" class="pb-0">
            <v-img src="../images/logo.jpg"
            class="rounded-circle"           
             width="110"
            ></v-img>
          </v-container>
          <v-card-title align="center" class="text-h5">
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
                  <v-text-field v-model="password" 
                  @click:append="cambiarVisibilidadPass"
                  :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showPassword ? 'text' : 'password'" 
                    label="Contraseña"
                    prepend-icon="mdi-lock"
                    required></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-row justify="center">
                    <v-col cols="8">
                      <v-btn type="submit" color="primary"
                      append-icon="mdi-login" 
                      block>Iniciar sesión</v-btn>
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
    },
    
    cambiarVisibilidadPass() {
      this.showPassword = !this.showPassword;
    }
  }
};
</script>

<style scoped>
.fill-height {
  height: 100vh;
}
</style>