<template>
  <v-container>
    <v-form @submit.prevent="submitVehiculeForm">
      <v-card>
        <v-card-title class="headline">Ajouter un Véhicule</v-card-title>
        <v-card-text>
          <v-text-field v-model="vehicule.marque" label="Marque" required />
          <v-text-field v-model="vehicule.modele" label="Modèle" required />
          <v-text-field v-model="vehicule.immatriculation" label="Immatriculation" required />
          <v-text-field v-model="vehicule.couleur" label="Couleur" required />
          <v-text-field v-model="vehicule.types_confort" label="Types de Confort" required />
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" type="submit">Ajouter</v-btn>
          <v-btn color="secondary" @click="goBack">Annuler</v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
  </v-container>
</template>

<script setup>
import { ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();
const router = useRouter();

const vehicule = ref({
  marque: '',
  modele: '',
  immatriculation: '',
  couleur: '',
  types_confort: '',
});

const submitVehiculeForm = async () => {
  try {
    await store.dispatch('vehicules/addVehicule', vehicule.value);
    router.push('/addTrajet'); // Redirige vers le formulaire d'ajout de trajet après ajout du véhicule
  } catch (error) {
    console.error('Erreur lors de l\'ajout du véhicule:', error);
  }
};

const goBack = () => {
  router.push('/trajets');
};
</script>

<style scoped>
/* Ajoutez des styles personnalisés ici */
</style>
