// src/store/index.js
import { createStore } from 'vuex';
import router from '../router/index.js';
import axios from '../axios.js';
import users from './modules/usersModule.js';
import trajets from './modules/trajetModule';
import conducteurs from './modules/vehicules.js';
import reservations from './modules/reservations.js';
import lieux from './modules/lieux';


export default createStore({
  state: {
    isLoggedIn: !!localStorage.getItem('token'),
    user: localStorage.getItem('user')
      ? JSON.parse(localStorage.getItem('user'))
      : null,
  },

  mutations: {
    LOGIN(state, userData) {
      state.isLoggedIn = true;
      state.user = userData.user;
      localStorage.setItem('token', userData.token);
      localStorage.setItem('user', JSON.stringify(userData.user));
    },
    LOGOUT(state) {
      state.isLoggedIn = false;
      state.user = null;
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    },
    SET_USER(state, user) {
      state.user = user;
    },
  },

  actions: {
    async login({ commit }, credentials) {
      try {
        const response = await axios.post(
          'http://localhost:8000/api/login',
          credentials
        );
        const userData = response.data;
        commit('LOGIN', userData);
        router.push('/'); // Redirect to home page after login
      } catch (error) {
        console.error('An error occurred during login:', error);
        throw error; // Optional: rethrow the error if you want to handle it in the component
      }
    },

    async registerAdmin({ commit }, userData) {
      try {
        const response = await axios.post(
          'http://localhost:8000/api/registerAdmin',
          userData
        );
        const user = response.data;
        // Optionnel : Connecter automatiquement l'utilisateur après l'enregistrement
        // commit('LOGIN', user);
      } catch (error) {
        console.error('An error occurred during registration:', error);
        throw error; // Optional: rethrow the error if you want to handle it in the component
      }
    },

    async registerPassager({ commit }, userData) {
      try {
        const response = await axios.post(
          'http://localhost:8000/api/registerPassager',
          userData
        );
        const user = response.data;
        commit('LOGIN', user);
      } catch (error) {
        console.error('An error occurred during registration:', error);
        throw error; // Optional: rethrow the error if you want to handle it in the component
      }
    },

    async registerConducteur({ commit }, userData) {
      try {
        const response = await axios.post(
          'http://localhost:8000/api/registerConducteur',
          userData
        );
        const user = response.data;
        commit('LOGIN', user);
      } catch (error) {
        console.error('An error occurred during registration:', error);
        throw error; // Optional: rethrow the error if you want to handle it in the component
      }
    },

    async logout({ commit }) {
      try {
        // Assurez-vous d'envoyer le token d'authentification
        const token = localStorage.getItem('token');
        if (!token) {
          throw new Error('No token available');
        }

        // Déconnexion côté serveur en envoyant le token
        await axios.post('http://localhost:8000/api/logout', null, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        // Réinitialisation de l'état local et redirection vers la page de connexion
        commit('LOGOUT');
        router.push({ name: 'login' });
      } catch (error) {
        console.error('An error occurred during logout:', error);
        throw error; // Optionnel : rejet de l'erreur pour la gestion dans le composant
      }
    },

    navigateToLogin() {
      router.push({ name: 'login' });
    },

    async fetchUser({ commit }) {
      try {
        const response = await axios.get('/users');
        commit('SET_USER', response.data);
      } catch (error) {
        console.error('Erreur lors de la récupération de l\'utilisateur:', error);
      }
    },
  },

  getters: {
    isAuthenticated: (state) => state.isLoggedIn,
    getUser: (state) => state.user,
    userDetail: (state) => state.userDetail,
  },

  modules: {
    users,
    trajets, // Assurez-vous que le nom du module correspond au nom utilisé dans les getters et actions
    conducteurs,
    reservations,
    lieux
    
    
  },
});


