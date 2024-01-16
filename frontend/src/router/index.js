import { createRouter, createWebHashHistory } from 'vue-router'
import Login from '../views/Login.vue'

const routes = [
  {
    path: '/',
    name: 'login',
    component: Login
  },
  {
    path: '/about',
    name: 'about',

    component: function () {
      return import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
    }
  },
 
  {
    path: '/add',
    name: 'login',
    component: Login
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
    
  }
 
    
  
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
