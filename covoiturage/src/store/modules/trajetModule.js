// // src/store/modules/trajetModule.js
// import axios from 'axios';
// import router from '@/router';

// const state = {
//   trajets: [],
//   currentTrajet: null,
// };

// const getters = {
//   allTrajets: (state) => state.trajets,
//   getTrajetById: (state) => (id) => state.trajets.find(trajet => trajet.id === id),
//   trajetCount: state => state.trajets.length,
//   getCurrentTrajet: (state) => state.currentTrajet,
// };

// const actions = {
//   async fetchTrajets({ commit }) {
//     try {
//       const token = localStorage.getItem('token');
//       if (!token) {
//         throw new Error('No token found');
//       }

//       const response = await axios.get('http://localhost:8000/api/trajets', {
//         headers: {
//           Authorization: `Bearer ${token}`,
//         },
//       });
//       console.log('Fetched trajets:', response.data); // Vérifiez les données ici
//       commit('setTrajets', response.data);
//     } catch (error) {
//       handleError(error);
//     }
//   },
  
//   async fetchTrajetById({ commit }, id) {
//     try {
//       const token = localStorage.getItem('token');
//       if (!token) {
//         throw new Error('No token found');
//       }

//       const response = await axios.get(`http://localhost:8000/api/trajets/${id}`, {
//         headers: {
//           Authorization: `Bearer ${token}`,
//         },
//       });
//       return response.data;
//     } catch (error) {
//       handleError(error);
//       throw error;
//     }
//   },

//   async addTrajet({ commit }, trajet) {
//     try {
//       const token = localStorage.getItem('token');
//       if (!token) {
//         throw new Error('No token found');
//       }
  
//       const response = await axios.post('http://localhost:8000/api/trajets', trajet, {
//         headers: {
//           Authorization: `Bearer ${token}`,
//         },
//       });
//       commit('addTrajet', response.data);
//     } catch (error) {
//       handleError(error);
//       throw error;
//     }
//   },
  
//   // async updateTrajet({ commit }, trajet) {
//   //   try {
//   //     const token = localStorage.getItem('token');
//   //     if (!token) {
//   //       throw new Error('No token found');
//   //     }
  
//   //     const response = await axios.put(`http://localhost:8000/api/trajets/${trajet.id}`, trajet, {
//   //       headers: {
//   //         Authorization: `Bearer ${token}`,
//   //       },
//   //     });
//   //     commit('updateTrajet', response.data);
//   //   } catch (error) {
//   //     handleError(error);
//   //     throw error;
//   //   }
//   // },

//   async updateTrajet({ commit }, trajet) {
//     try {
//       const token = localStorage.getItem('token');
//       if (!token) {
//         throw new Error('No token found');
//       }
  
//       const response = await axios.put(`http://localhost:8000/api/trajets/${trajet.id}`, trajet, {
//         headers: {
//           Authorization: `Bearer ${token}`,
//         },
//       });
//       commit('updateTrajet', response.data);
//     } catch (error) {
//       handleError(error);
//       throw error;
//     }
//   },
  
//   async deleteTrajet({ commit }, trajetId) {
//     try {
//       const token = localStorage.getItem('token');
//       if (!token) {
//         throw new Error('No token found');
//       }
  
//       await axios.delete(`http://localhost:8000/api/trajets/${trajetId}`, {
//         headers: {
//           Authorization: `Bearer ${token}`,
//         },
//       });
//       commit('removeTrajet', trajetId);
//     } catch (error) {
//       handleError(error);
//       throw error;
//     }
//   },
// };

// const mutations = {
//   setTrajets(state, trajets) {
//     console.log('Setting trajets:', trajets); // Vérifiez les données avant la mise à jour
//     state.trajets = trajets;
//   },
//   addTrajet(state, trajet) {
//     state.trajets.push(trajet);
//   },
//   updateTrajet(state, updatedTrajet) {
//     const index = state.trajets.findIndex((trajet) => trajet.id === updatedTrajet.id);
//     if (index !== -1) {
//       state.trajets.splice(index, 1, updatedTrajet);
//     }
//   },
//   removeTrajet(state, trajetId) {
//     state.trajets = state.trajets.filter((trajet) => trajet.id !== trajetId);
//   },
// };

// function handleError(error) {
//   if (error.response) {
//     if (error.response.status === 403) {
//       console.error('Forbidden: You do not have the right permissions to access this resource.');
//     } else if (error.response.status === 401) {
//       console.error('Unauthorized: Invalid or expired token.');
//       router.push({ name: 'login' });
//     } else {
//       console.error(`Error ${error.response.status}: ${error.response.data.message}`);
//     }
//   } else {
//     console.error('An error occurred:', error);
//   }
// }

// export default {
//   namespaced: true,
//   state,
//   getters,
//   actions,
//   mutations,
// };


// src/store/modules/trajetModule.js
import axios from 'axios';
import router from '@/router';

const state = {
  trajets: [],
  currentTrajet: null,
};

const getters = {
  allTrajets: (state) => state.trajets,
  getTrajetById: (state) => (id) => state.trajets.find(trajet => trajet.id === id),
  trajetCount: (state) => state.trajets.length,
  getCurrentTrajet: (state) => state.currentTrajet,
};

const actions = {
  async fetchTrajets({ commit }) {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('Aucun token trouvé');
      }

      const response = await axios.get('http://localhost:8000/api/trajets', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      console.log('Trajets récupérés :', response.data); // Vérifiez les données ici
      commit('setTrajets', response.data);
    } catch (error) {
      handleError(error);
    }
  },
  
  async fetchTrajetById({ commit }, id) {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('Aucun token trouvé');
      }

      const response = await axios.get(`http://localhost:8000/api/trajets/${id}`, {
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

  async addTrajet({ commit }, trajet) {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('Aucun token trouvé');
      }
  
      const response = await axios.post('http://localhost:8000/api/trajets', trajet, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      commit('addTrajet', response.data);
    } catch (error) {
      handleError(error);
      throw error;
    }
  },
  
  async updateTrajet({ commit }, trajet) {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('Aucun token trouvé');
      }
  
      const response = await axios.put(`http://localhost:8000/api/trajets/${trajet.id}`, trajet, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      commit('updateTrajet', response.data);
    } catch (error) {
      handleError(error);
      throw error;
    }
  },
  
  async deleteTrajet({ commit }, trajetId) {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('Aucun token trouvé');
      }
  
      await axios.delete(`http://localhost:8000/api/trajets/${trajetId}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      commit('removeTrajet', trajetId);
    } catch (error) {
      handleError(error);
      throw error;
    }
  },
};

const mutations = {
  setTrajets(state, trajets) {
    console.log('Mise à jour des trajets :', trajets); // Vérifiez les données avant la mise à jour
    state.trajets = trajets;
  },
  addTrajet(state, trajet) {
    state.trajets.push(trajet);
  },
  updateTrajet(state, updatedTrajet) {
    const index = state.trajets.findIndex((trajet) => trajet.id === updatedTrajet.id);
    if (index !== -1) {
      state.trajets.splice(index, 1, updatedTrajet);
    }
  },
  removeTrajet(state, trajetId) {
    state.trajets = state.trajets.filter((trajet) => trajet.id !== trajetId);
  },
};

function handleError(error) {
  if (error.response) {
    if (error.response.status === 403) {
      console.error('Interdit : Vous n\'avez pas les permissions pour accéder à cette ressource.');
    } else if (error.response.status === 401) {
      console.error('Non autorisé : Token invalide ou expiré.');
      router.push({ name: 'login' });
    } else {
      console.error(`Erreur ${error.response.status} : ${error.response.data.message}`);
    }
  } else {
    console.error('Une erreur est survenue :', error);
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
