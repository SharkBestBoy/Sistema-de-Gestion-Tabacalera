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
    component: Login,
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
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

export default router;
