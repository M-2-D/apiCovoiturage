<template>
  <VContainer fluid>
    <VCard v-if="trajet">
      <VCardTitle class="text-2xl font-weight-bold">Détails du Trajet</VCardTitle>
      <VCardText>
        <p><strong>ID:</strong> {{ trajet.id }}</p>
        <p><strong>Lieu de Départ:</strong> {{ trajet.lieu_depart }}</p>
        <p><strong>Lieu d'Arrivée:</strong> {{ trajet.lieu_arrivee }}</p>
        <p><strong>Heure de Départ:</strong> {{ trajet.heure_depart }}</p>
        <p><strong>Date de Départ:</strong> {{ trajet.date_depart }}</p>
        <p><strong>Nombre de Places Disponibles:</strong> {{ trajet.nombre_places }}</p>
        <p><strong>Prix:</strong> {{ trajet.prix }} Fcfa</p>
      </VCardText>

      <VCardSubtitle class="text-xl">Conducteur</VCardSubtitle>
      <VCardText v-if="trajet.conducteur && trajet.conducteur.user">
        <p><strong>Nom:</strong> {{ trajet.conducteur.user.nom }}</p>
        <p><strong>Prénom:</strong> {{ trajet.conducteur.user.prenom }}</p>
        <p><strong>Téléphone:</strong> {{ trajet.conducteur.user.telephone }}</p>
      </VCardText>
      <VCardText v-else>
        <p>Informations sur le conducteur non disponibles.</p>
      </VCardText>

      <VCardSubtitle class="text-xl">Véhicule</VCardSubtitle>
      <VCardText v-if="trajet.vehicule">
        <p><strong>Marque et Modèle:</strong> {{ trajet.vehicule.marque }} {{ trajet.vehicule.modele }}</p>
        <p><strong>Numéro d'Immatriculation:</strong> {{ trajet.vehicule.immatriculation }}</p>
      </VCardText>
      <VCardText v-else>
        <p>Informations sur le véhicule non disponibles.</p>
      </VCardText>

      <!-- <VCardActions>
        <VBtn @click="reserveTrajet" color="primary">Réserver</VBtn>
        <VBtn @click="contactConducteur" color="secondary">Contacter le Conducteur</VBtn>
        <VBtn @click="goBack" color="secondary">Retour</VBtn>
      </VCardActions> -->

      <v-card-actions>
      <v-btn 
        @click="goBack" 
        color="primary" 
        class="ma-2"
        large
        :loading="isLoading"
      >
        Retour à la liste des trajets
      </v-btn>
    </v-card-actions>
    </VCard>

    <VCard v-else>
      <VCardText>
        <p>Chargement des détails du trajet...</p>
      </VCardText>
    </VCard>
  </VContainer>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useStore } from 'vuex';
import { useRoute, useRouter } from 'vue-router';

const store = useStore();
const route = useRoute();
const router = useRouter();
const trajet = ref(null);

onMounted(async () => {
  const trajetId = route.params.id;
  try {
    trajet.value = await store.dispatch('trajets/fetchTrajetById', trajetId);
    console.log('Détails du trajet:', trajet.value);
  } catch (error) {
    console.error('Erreur lors de la récupération des détails du trajet:', error);
    router.push({ name: 'home' }); // Redirection vers une page d'accueil ou d'erreur
  }
});

const reserveTrajet = () => {
  console.log('Réserver le trajet', trajet.value);
};

const contactConducteur = () => {
  console.log('Contacter le conducteur');
};

const goBack = () => {
  router.go(-1);
};
</script>

<style scoped>
/* Ajoutez des styles spécifiques pour le composant ici */
</style>
