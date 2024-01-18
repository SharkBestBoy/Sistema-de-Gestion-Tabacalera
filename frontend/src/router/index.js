import { createRouter, createWebHashHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Principal from '../views/Principal.vue';
import ProdDiaria from '../views/ProdDiaria.vue';
import GestionarBrigada from '@/views/GestionarBrigada.vue';
import Planificacion from '@/views/Planificacion';


const routes = [
  {
    path: '/',
    name: 'login',
    component: Login
   
  },
  

    {
    path: '/p',
    name: 'Principal',
    component: Principal,
  },
 
  {
    path: '/pd',
    name: 'prodDiaria',
    component: ProdDiaria,
  },
  {
    path: '/pl',
    name: 'planificacion',
    component: Planificacion,
  },
  {
    path: '/Prueba',
    name: 'prueba',
  },
  {
    path: '/gb',
    name: 'gestionarBrigada',
    component: GestionarBrigada,
  },
  
  {
    path: '/pd',
    name: 'prodDiaria',
    component: ProdDiaria,
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
 
    
  
{
    path: '/pl',
    name: 'planificacion',
    component: Planificacion,
  },
  {
    path: '/Prueba',
    name: 'prueba',
  },
  {
    path: '/gb',
    name: 'gestionarBrigada',
    component: GestionarBrigada,
  },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

export default router;
