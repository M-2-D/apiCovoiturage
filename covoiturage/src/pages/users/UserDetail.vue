<template>
  <v-card v-if="user">
    <v-card-title>
      <h2>Détails de l'utilisateur</h2>
    </v-card-title>
    <v-card-text>
      <v-row>
        <v-col cols="12" md="6">
          <strong>Nom:</strong> {{ user.nom }}
        </v-col>
        <v-col cols="12" md="6">
          <strong>Prénom:</strong> {{ user.prenom }}
        </v-col>
        <v-col cols="12" md="6">
          <strong>Téléphone:</strong> {{ user.telephone }}
        </v-col>
        <v-col cols="12" md="6">
          <strong>Email:</strong> {{ user.email }}
        </v-col>
        <!-- <v-col cols="12" md="6">
          <strong>Adresse:</strong> {{ user.adresse }} 
        </v-col> -->
        <v-col cols="12" md="6">
          <strong>Rôle:</strong> {{ user.role }}
        </v-col>
        <!-- Vérifiez si le rôle est Conducteur et si les données du véhicule sont disponibles -->
        <v-col cols="12" md="6" v-if="user.role === 'Conducteur' && user.conducteur">
          <strong>Numéro de permis:</strong> {{ user.conducteur.numero_permis }}
        </v-col>
        <v-col cols="12" md="6" v-if="user.role === 'Conducteur' && user.conducteur">
          <strong>CIN:</strong> {{ user.conducteur.CIN }}
        </v-col>
        <v-col cols="12" md="6" v-if="user.role === 'Conducteur' && user.conducteur.vehicule">
          <strong>Véhicule:</strong> {{ user.conducteur.vehicule.marque }} - {{ user.conducteur.vehicule.modele }}
        </v-col>
        <v-col cols="12" md="6" v-if="user.role === 'Conducteur' && user.conducteur.vehicule">
          <strong>Immatriculation:</strong> {{ user.conducteur.vehicule.immatriculation }}
        </v-col>
        <v-col cols="12" md="6" v-if="user.role === 'Conducteur' && user.conducteur.vehicule">
          <strong>Couleur:</strong> {{ user.conducteur.vehicule.couleur }}
        </v-col>
      </v-row>
    </v-card-text>

    <!-- Button to return to the user list -->
    <v-card-actions>
      <v-btn 
        @click="goBack" 
        color="primary" 
        class="ma-2"
        large
        :loading="isLoading"
      >
        Retour à la liste des utilisateurs
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useStore } from 'vuex';
import { useRoute, useRouter } from 'vue-router';

const store = useStore();
const route = useRoute();
const router = useRouter();
const user = ref(null);
const isLoading = ref(false);

onMounted(async () => {
  isLoading.value = true;
  await store.dispatch('users/fetchUserDetail', route.params.userId);
  user.value = store.getters['users/userDetail'];
  isLoading.value = false;
});

function goBack() {
  router.push({ name: 'gestion-utilisateurs' });
}
</script>

<style scoped>
/* Aucune modification de style nécessaire */
</style>
