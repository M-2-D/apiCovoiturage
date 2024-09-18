import axios from 'axios';

const state = {
  lieux: []
};

const mutations = {
  setLieux(state, lieux) {
    state.lieux = lieux;
  }
};

const actions = {
  async fetchLieux({ commit }) {
    try {
      const response = await axios.get('http://localhost:8000/api/lieux'); // Remplacez par votre URL API réelle
      commit('setLieux', response.data);
    } catch (error) {
      console.error('Erreur lors de la récupération des lieux:', error.response?.data?.error || error.message);
    }
  }
};

const getters = {
  lieux: (state) => state.lieux,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
};
