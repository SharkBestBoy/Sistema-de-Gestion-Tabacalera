import { createRouter, createWebHashHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Principal from '../views/Principal.vue';
import ProdDiaria from '../views/ProdDiaria.vue';
import GestionarBrigada from '@/views/GestionarBrigada.vue';
import Planificacion from '@/views/Planificacion';
import GestionarUsuarios from '@/views/GestionarUsuarios';
import authService from '@/services/authService';


const notFoundRoute = {
  path: '/:catchAll(.*)',
  redirect: '/login',
};

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login
   
  },
    {
    path: '/home',
    name: 'Principal',
    component: Principal,
    meta: { requiresAuth: true }, // Esta ruta requiere autenticación

  },
 
  {
    path: '/addProduccion',
    name: 'prodDiaria',
    component: ProdDiaria,
    meta: { requiresAuth: true }, // Esta ruta requiere autenticación

  },
  {
    path: '/gestionarUsuarios',
    name: 'gestionarUsuarios',
    component: GestionarUsuarios,
    meta: { requiresAuth: true }, // Esta ruta requiere autenticación

  },
  {
    path: '/gestionarBrigada',
    name: 'gestionarBrigada',
    component: GestionarBrigada,
    meta: { requiresAuth: true }, // Esta ruta requiere autenticación

  },
  {
    path: '/gestionarEmpleados',
    name: 'AddEmpleado',
    component: function () {
      return import(/* webpackChunkName: "about" */ '../views/AddEmpleado')  
    },
    meta: { requiresAuth: true }, // Esta ruta requiere autenticación

  },
  {
    path: '/mostrarProducciones',
    name: 'mostrar',
    component: function () {
      return import(/* webpackChunkName: "about" */ '../views/Mostrar')
    },
    meta: { requiresAuth: true }, // Esta ruta requiere autenticación

  },
{
    path: '/gestionarPlanificacion',
    name: 'planificacion',
    component: Planificacion,
    meta: { requiresAuth: true }, // Esta ruta requiere autenticación

  },
  notFoundRoute,
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    // Verificar si el usuario está autenticado
    const isAuthenticated = authService.isAuthenticated();

    if (!isAuthenticated) {
      // Si no está autenticado, redirigir a la página de inicio de sesión
      next('/login');
    } else {
      // Si está autenticado, permitir el acceso a la ruta protegida
      next();
    }
  } else {
    // Para rutas que no requieren autenticación, permitir el acceso
    next();
  }
});

export default router;