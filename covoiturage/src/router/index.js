import { createRouter, createWebHistory } from 'vue-router';
import UserEdit from '../pages/users/UserEdit.vue';
import AddUser from '../pages/users/AddUser.vue';
import AddTrajet from '../pages/trajets/AddTrajet.vue';
import AddVehicule from '../pages/trajets/AddVehicule.vue';
import TrajetDetails from '../pages/trajets/TrajetDetails.vue';

// Création du routeur avec l'historique HTML5
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // Routes avec mise en page par défaut (avec barre de navigation)
    {
      path: '/',
      component: () => import('../layouts/default.vue'),
      children: [
        {
          path: '',
          redirect: 'login',
        },
        {
          path: 'dashboard',
          component: () => import('../pages/dashboard.vue'),
        },
        {
          path: 'account-settings',
          name: 'account-settings',
          component: () => import('../pages/users/FormProfil.vue'),
        },
        {
          path: 'typography',
          component: () => import('../pages/typography.vue'),
        },
        {
          path: 'icons',
          component: () => import('../pages/icons.vue'),
        },
        {
          path: 'cards',
          component: () => import('../pages/cards.vue'),
        },
        {
          path: 'tables',
          component: () => import('../pages/tables.vue'),
        },
        {
          path: 'form-layouts',
          component: () => import('../pages/form-layouts.vue'),
        },
        {
          path: 'gestion-utilisateurs',
          name: 'gestion-utilisateurs',
          component: () => import('../pages/users/userManagement.vue'),
          meta: { requiresAuth: true, requiredRole: 'Administrateur' },
        },
        {
          path: 'user-edit/:id',
          name: 'user-edit',
          component: UserEdit,
          meta: { requiresAuth: true, requiredRole: 'Administrateur' },
        },
        {
          path: 'addUser',
          name: 'add-user',
          component: AddUser,
          meta: { requiresAuth: true, requiredRole: 'Administrateur' },
        },
        {
          path: 'trajet-list',
          name: 'trajet-list',
          component: () => import('../pages/trajets/trajetManagement.vue'),
          meta: { requiresAuth: true, requiredRole: 'Conducteur' },
        },
        {
          path: 'createTrajet',
          name: 'trajet-create',
          component: () => import('../pages/trajets/TrajetEdit.vue'),
          meta: { requiresAuth: true, requiredRole: 'Conducteur' },
        },
        {
          path: 'editTrajet/:id',
          name: 'trajet-edit',
          component: () => import('../pages/trajets/TrajetEdit.vue'),
          meta: { requiresAuth: true, requiredRole: 'Conducteur' },
        },
        {
          path: 'addtrajet',
          name: 'add-trajet',
          component: AddTrajet,
          meta: { requiresAuth: true, requiredRole: 'Conducteur' },
        },
        {
          path: 'user/:userId',
          name: 'user-detail',
          component: () => import('../pages/users/UserDetail.vue'),
        },

        {
          path: '/addVehicule',
          name: 'AddVehicule',
          component: AddVehicule,
        },

        {
          path: '/gestion-reservations',
          name: 'GestionReservations',
            component: () => import('../pages/ReservationList.vue'),
          },

          {
            path: '/trajet-details/:id',
            name: 'trajet-details',
            component: TrajetDetails,
          },
      ],
    },
    
    // Routes avec mise en page minimale (pour les pages de connexion, inscription, et erreurs)
    {
      path: '/',
      component: () => import('../layouts/blank.vue'),
      children: [
        {
          path: 'login',
          component: () => import('../pages/login.vue'),
        },
        {
          path: 'register',
          component: () => import('../pages/register.vue'),
        },
        {
          path: '/:pathMatch(.*)*',
          component: () => import('../pages/[...all].vue'),
        },
      ],
    },
  ],
});

// Export du routeur pour l'utiliser dans l'application Vue
export default router;
