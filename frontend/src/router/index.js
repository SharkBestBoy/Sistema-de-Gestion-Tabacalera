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
    path: '/gb',
    name: 'gestionarBrigada',
    component: GestionarBrigada,
  },
  {
    path: '/AddEmpleado',
    name: 'AddEmpleado',

    component: function () {
      return import(/* webpackChunkName: "about" */ '../views/AddEmpleado')
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
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

export default router;
