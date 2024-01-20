<template>
    <v-app>
    <NavBar v-if="mostrarNavBar"></NavBar>
    
    <v-container>
      <v-content>
        <router-view></router-view>
      </v-content>
    </v-container>
  </v-app>
</template>

<script>
import NavBar from '@/components/NavBar.vue';

export default {
  components: {
    NavBar,
  },
  data() {
    return {
      mostrarNavBar: true,
    };
  },
  watch: {
    '$route'(to, from) {
      this.mostrarNavBar = !['login'].includes(to.name);
    },
  },
};
</script>