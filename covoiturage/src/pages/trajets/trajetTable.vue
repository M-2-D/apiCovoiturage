<template>
  <VContainer fluid>
    <VRow>
      <VCol cols="12">
        <VRow>
          <VCol cols="9">
            <VCard class="text-2xl font-weight-bold p-4">
              <VCardTitle class="text-2xl font-weight-bold">Liste des Trajets</VCardTitle>
            </VCard>
          </VCol>
          <VCol cols="3" class="d-flex justify-end align-center">
            <VBtn title="Ajouter un trajet" color="primary" @click="() => router.push('/addTrajet')">
              <VIcon left>mdi-plus</VIcon>
              Ajouter un trajet
            </VBtn>
          </VCol>
        </VRow>
        <VCard>
          <VCardText>
            <VTable class="trajet-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Lieu de Départ</th>
                  <th>Lieu d'Arrivée</th>
                  <th>Heure de Départ</th>
                  <th>Date de Départ</th>
                  <th>Nombre de Places</th>
                  <th>Prix</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="trajet in allTrajets" :key="trajet.id">
                  <td>{{ trajet.id }}</td>
                  <td>{{ trajet.lieu_depart }}</td>
                  <td>{{ trajet.lieu_arrivee }}</td>
                  <td>{{ trajet.heure_depart }}</td>
                  <td>{{ trajet.date_depart }}</td>
                  <td>{{ trajet.nombre_places }}</td>
                  <td>{{ trajet.prix }}</td>
                  <td>
                    <div class="action-icons">
                      <VIcon @click="viewDetails(trajet.id)" color="info" small>mdi-eye</VIcon>
                      <VIcon @click="updateTrajet(trajet.id)" color="warning" small>mdi-pencil</VIcon>
                      <VIcon @click="confirmDeleteTrajet(trajet.id)" color="error" small>mdi-delete</VIcon>
                    </div>
                  </td>
                </tr>
              </tbody>
            </VTable>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>

    <!-- Boîte de dialogue de confirmation de suppression -->
    <VDialog v-model="showDeleteDialog" max-width="500">
      <VCard>
        <VCardTitle class="headline">Confirmer la Suppression</VCardTitle>
        <VCardText>
          Êtes-vous sûr de vouloir supprimer ce trajet ?
        </VCardText>
        <VCardActions>
          <VSpacer></VSpacer>
          <VBtn color="secondary" @click="showDeleteDialog = false">Annuler</VBtn>
          <VBtn color="error" @click="deleteTrajet">Supprimer</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </VContainer>
</template>

<script setup>
import { onMounted, computed, ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();
const router = useRouter();
const showDeleteDialog = ref(false);
const trajetToDelete = ref(null);

onMounted(() => {
  store.dispatch('trajets/fetchTrajets');
});

const allTrajets = computed(() => store.getters['trajets/allTrajets']);

const viewDetails = (trajetId) => {
  router.push({ name: 'trajet-details', params: { id: trajetId } });
};

const updateTrajet = (trajetId) => {
  router.push({ name: 'trajet-edit', params: { id: trajetId } });
};

const confirmDeleteTrajet = (trajetId) => {
  trajetToDelete.value = trajetId;
  showDeleteDialog.value = true;
};

const deleteTrajet = async () => {
  try {
    await store.dispatch('trajets/deleteTrajet', trajetToDelete.value);
    showDeleteDialog.value = false;
    store.dispatch('trajets/fetchTrajets');
  } catch (error) {
    console.error('Erreur lors de la suppression du trajet:', error);
    // Afficher un message d'erreur à l'utilisateur
  }
};
</script>

<style scoped>
.trajet-table {
  width: 100%;
}

.action-icons {
  display: flex;
  gap: 8px; /* Espacement entre les icônes */
}

.d-flex {
  display: flex;
}

.justify-end {
  justify-content: flex-end;
}

.align-center {
  align-items: center;
}
</style>
