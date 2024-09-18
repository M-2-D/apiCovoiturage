import axios from 'axios';
import router from '@/router';

const state = {
  users: [],
  currentUser: null,
  userDetail: null,
};

const getters = {
  allUsers: (state) => state.users,
  getUserById: (state) => (id) => state.users.find(user => user.id === id),
  userCount: (state) => state.users.length,
  getCurrentUser: (state) => state.currentUser,
  userDetail: (state) => state.userDetail,
};

const actions = {
  async fetchUserDetail({ commit }, userId) {
    try {
      const token = localStorage.getItem('token');
      const response = await axios.get(`http://localhost:8000/api/users/${userId}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      commit('SET_USER_DETAIL', response.data);
    } catch (error) {
      handleError(error);
    }
  },

  async fetchUsers({ commit }) {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('No token found');
      }

      const response = await axios.get('http://localhost:8000/api/users', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      commit('setUsers', response.data);
    } catch (error) {
      handleError(error);
    }
  },

  async fetchCurrentUser({ commit }) {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('No token found');
      }

      const response = await axios.get('http://localhost:8000/api/users/showProfile', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      commit('setCurrentUser', response.data);
      return response.data;
    } catch (error) {
      handleError(error);
      throw error;
    }
  },

  async fetchUserById({ commit }, id) {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('No token found');
      }

      const response = await axios.get(`http://localhost:8000/api/users/${id}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      return response.data;
    } catch (error) {
      handleError(error);
      throw error;
    }
  },

  async addUser({ commit }, user) {
    try {
      let response;
      switch (user.role) {
        case 'Administrateur':
          response = await axios.post('http://localhost:8000/api/registerAdmin', user);
          break;
        case 'Conducteur':
          response = await axios.post('http://localhost:8000/api/registerConducteur', user);
          break;
        case 'Passager':
          response = await axios.post('http://localhost:8000/api/registerPassager', user);
          break;
        default:
          throw new Error('Role non valide');
      }
      commit('addUser', response.data);
    } catch (error) {
      throw error;
    }
  },

  async updateUser({ commit }, user) {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('No token found');
      }

      const response = await axios.put(`http://localhost:8000/api/users/${user.id}`, user, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      commit('updateUser', response.data);
    } catch (error) {
      handleError(error);
      throw error;
    }
  },

  async updateProfile({ commit }, userData) {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('No token found');
      }

      const response = await axios.put('http://localhost:8000/api/users/updateProfile', userData, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      commit('setCurrentUser', response.data);
    } catch (error) {
      handleError(error);
      throw error;
    }
  },

  async deleteUser({ commit }, userId) {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('No token found');
      }

      await axios.delete(`http://localhost:8000/api/users/${userId}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      commit('removeUser', userId);
    } catch (error) {
      handleError(error);
      throw error;
    }
  },

  async blockUser({ dispatch }, userId) {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('No token found');
      }

      await axios.post(`http://localhost:8000/api/users/${userId}/toggle-block`, {}, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      dispatch('fetchUsers'); // Rafraîchir la liste des utilisateurs après le blocage
    } catch (error) {
      console.error('Erreur lors du blocage de l\'utilisateur:', error);
      // Vous pouvez ajouter des actions supplémentaires en cas d'erreur, comme afficher un message à l'utilisateur
    }
  },
  
  async unblockUser({ dispatch }, userId) {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('No token found');
      }

      await axios.post(`http://localhost:8000/api/users/${userId}/toggle-block`, {}, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      dispatch('fetchUsers'); // Rafraîchir la liste des utilisateurs après le déblocage
    } catch (error) {
      console.error('Erreur lors du déblocage de l\'utilisateur:', error);
      // Vous pouvez ajouter des actions supplémentaires en cas d'erreur, comme afficher un message à l'utilisateur
    }
  }
};

const mutations = {
  setUsers(state, users) {
    state.users = users;
  },
  addUser(state, user) {
    state.users.push(user);
  },
  setCurrentUser(state, user) {
    state.currentUser = user;
  },
  updateUser(state, updatedUser) {
    const index = state.users.findIndex((user) => user.id === updatedUser.id);
    if (index !== -1) {
      state.users.splice(index, 1, updatedUser);
    }
  },
  removeUser(state, userId) {
    state.users = state.users.filter((user) => user.id !== userId);
  },
  SET_USER_DETAIL(state, userDetail) {
    state.userDetail = userDetail;
  },
  updateUserStatus(state, userId) {
    const user = state.users.find(user => user.id === userId);
    if (user) {
      user.is_blocked = !user.is_blocked; // Basculer l'état de blocage
    }
  },
};

function handleError(error) {
  if (error.response) {
    if (error.response.status === 403) {
      console.error('Forbidden: You do not have the right permissions to access this resource.');
    } else if (error.response.status === 401) {
      console.error('Unauthorized: Invalid or expired token.');
      router.push({ name: 'login' });
    } else {
      console.error(`Error ${error.response.status}: ${error.response.data.message}`);
    }
  } else {
    console.error('An error occurred:', error);
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
