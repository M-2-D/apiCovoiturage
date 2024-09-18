// import axios from 'axios';

// const state = {
//   reservations: []
// };

// const mutations = {
//   SET_RESERVATIONS(state, reservations) {
//     state.reservations = reservations;
//   },
//   ADD_RESERVATION(state, reservation) {
//     state.reservations.push(reservation);
//   },
//   REMOVE_RESERVATION(state, id) {
//     state.reservations = state.reservations.filter(reservation => reservation.id !== id);
//   },
//   UPDATE_RESERVATION(state, updatedReservation) {
//     const index = state.reservations.findIndex(reservation => reservation.id === updatedReservation.id);
//     if (index !== -1) {
//       state.reservations.splice(index, 1, updatedReservation);
//     }
//   }
// };

// const actions = {
//   async fetchReservations({ commit }) {
//     try {
//       const response = await axios.get('http://localhost:8000/api/reservations');
//       commit('SET_RESERVATIONS', response.data);
//     } catch (error) {
//       console.error('Error fetching reservations:', error);
//     }
//   },
//   async addReservation({ commit }, reservation) {
//     try {
//       const response = await axios.post('/api/reservations', reservation);
//       commit('ADD_RESERVATION', response.data);
//     } catch (error) {
//       console.error('Failed to add reservation:', error);
//     }
//   },
//   async deleteReservation({ commit }, id) {
//     try {
//       await axios.delete(`/api/reservations/${id}`);
//       commit('REMOVE_RESERVATION', id);
//     } catch (error) {
//       console.error('Failed to delete reservation:', error);
//     }
//   },
//   async updateReservation({ commit }, reservation) {
//     try {
//       const response = await axios.put(`/api/reservations/${reservation.id}`, reservation);
//       commit('UPDATE_RESERVATION', response.data);
//     } catch (error) {
//       console.error('Failed to update reservation:', error);
//     }
//   }
// };

// const getters = {
//   reservations: state => state.reservations
// };

// export default {
//   namespaced: true,
//   state,
//   mutations,
//   actions,
//   getters
// };


// src/store/modules/reservations.js
// src/store/modules/reservations.js
import axios from '../../axios.js';

const state = {
  reservations: [],
};

const mutations = {
  SET_RESERVATIONS(state, reservations) {
    state.reservations = reservations;
  },
};

const actions = {
  async fetchReservations({ commit }) {
    try {
      const response = await axios.get('/api/reservations', {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });
      commit('SET_RESERVATIONS', response.data);
    } catch (error) {
      console.error('Error fetching reservations:', error);
    }
  },
};

const getters = {
  allReservations: (state) => state.reservations,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
