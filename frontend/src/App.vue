<template>
  <v-app>
    <NavBar v-if="currentView === 'login'"></NavBar>
    <p>La vista actual es: {{ $route.name }}</p>

    <v-content>
      <router-view/>
    </v-content>
  </v-app>
</template>

<script>
import NavBar from '@/components/NavBar.vue'
export default {
  name: 'App',
  components: {
    NavBar
  },
  data(){
    return{
      currentView: this.$route.name
      
    }
  }
}
</script>
