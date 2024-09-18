// store/modules/vehicules.js
import axios from 'axios';

const state = {
  vehicules: [],
};

const mutations = {
  SET_VEHICULES(state, vehicules) {
    state.vehicules = vehicules;
  },
};

const actions = {
  async fetchVehiculesByConducteur({ commit }, { conducteurId }) {
    try {
      const response = await axios.get(`/api/vehicules?conducteur_id=${conducteurId}`);
      commit('SET_VEHICULES', response.data);
      return response.data;
    } catch (error) {
      console.error('Erreur lors de la récupération des véhicules:', error);
      throw error;
    }
  },

  async getVehiculeByConducteur({ dispatch }, { conducteurId }) {
    const vehicules = await dispatch('fetchVehiculesByConducteur', { conducteurId });
    if (vehicules.length > 0) {
      return vehicules[0];
    } else {
      throw new Error('Aucun véhicule trouvé pour ce conducteur.');
    }
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
