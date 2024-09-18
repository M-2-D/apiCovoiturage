import axios from "axios";

export default {
  namespaced: true,
  state: {
    user: {},
  },
  mutations: {
    SET_USER(state, user) {
      state.user = user;
    },
  },
  actions: {
    async fetchUserProfile({ commit }) {
      try {
        const response = await axios.get("/api/users/showProfile");
        commit("SET_USER", response.data);
      } catch (error) {
        console.error("Erreur lors de la récupération du profil de l'utilisateur", error);
      }
    },
    async updateUserProfile({ commit }, user) {
      try {
        const response = await axios.put(`/api/users/${user.id}`, user);
        commit("SET_USER", response.data);
      } catch (error) {
        console.error("Erreur lors de la mise à jour du profil de l'utilisateur", error);
        throw error;
      }
    },
  },
  getters: {
    user: (state) => state.user,
  },
};
