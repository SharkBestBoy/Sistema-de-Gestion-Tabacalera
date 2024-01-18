import { createRouter, createWebHashHistory } from 'vue-router'
import Login from '../views/Login.vue'

const routes = [
  {
    path: '/',
    name: 'login',
    component: Login
   
  },
  {
    path: '/Mostrar',
    name: 'mostrar',

    component: function () {
      return import(/* webpackChunkName: "about" */ '../views/Mostrar.vue')
    }
  },
  {
    path: '/AddEmpleado',
    name: 'AddEmpleado',

    component: function () {
      return import(/* webpackChunkName: "about" */ '../views/AddEmpleado')
    }
  },

  {
    path:'/ProdDiaria',
    name: 'prodDiaria',

    component: function () {
      return import(/* webpackChunkName: "produccionDiaria" */ '../views/ProdDiaria.vue')
    }
    
  },
  {
    path: '/Probar',
    name: 'probar',

    component: function () {
      return import(/* webpackChunkName: "about" */ '../views/Probar')
    }
  },
 
    
  
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
