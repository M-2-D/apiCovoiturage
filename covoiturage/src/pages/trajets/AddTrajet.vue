<template>
  <v-card>
    <v-card-title class="text-2xl font-weight-bold p-4">
      Ajouter un Trajet
    </v-card-title>

    <v-card-text>
      <v-form @submit.prevent="submitForm">
        <v-row>
          <v-col cols="12" md="6">
           <v-select
              v-model="trajet.lieu_depart"
              :items="lieux"
              item-value="id"
              item-text="nom"
              label="Lieu de Départ"
              outlined
              dense
              required
              :disabled="lieux.length === 0"
            />

          </v-col>
          <v-col cols="12" md="6">
            <v-select
              v-model="trajet.lieu_arrivee"
              :items="lieux"
              item-value="id"
              item-text="nom"
              label="Lieu d'Arrivée"
              outlined
              dense
              required
              :disabled="lieux.length === 0"
            />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              v-model="trajet.heure_depart"
              label="Heure de Départ"
              type="time"
              outlined
              dense
              required
            />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              v-model="trajet.date_depart"
              label="Date de Départ"
              type="date"
              outlined
              dense
              required
            />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              v-model="trajet.nombre_places"
              label="Nombre de Places"
              type="number"
              outlined
              dense
              required
            />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              v-model="trajet.prix"
              label="Prix"
              type="number"
              outlined
              dense
              required
            />
          </v-col>
          <v-col cols="12" class="d-flex justify-end mt-4">
            <v-btn type="submit" color="primary" class="me-2">Enregistrer</v-btn>
            <v-btn color="error" @click="cancelForm">
              <v-icon left>mdi-cancel</v-icon>
              Annuler
            </v-btn>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
  </v-card>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const router = useRouter();

const trajet = ref({
  lieu_depart: null,
  lieu_arrivee: null,
  heure_depart: '',
  date_depart: '',
  nombre_places: 0,
  prix: 0,
});

const lieux = ref([]);

// Fetch lieux from store
const fetchLieux = async () => {
  try {
    await store.dispatch('lieux/fetchLieux');
    lieux.value = store.getters['lieux/lieux'];
    
    // Ajoutez une option par défaut pour s'assurer que l'utilisateur sélectionne un lieu
    lieux.value.unshift({ id: null, nom: 'Sélectionnez un lieu' });
    
    console.log('Lieux après fetch:', lieux.value);
    console.log('Format des lieux:', JSON.stringify(lieux.value, null, 2));
  } catch (error) {
    console.error('Erreur lors de la récupération des lieux :', error.response?.data?.error || error.message);
  }
};


onMounted(() => {
  fetchLieux();
});

const submitForm = async () => {
  console.log('Adding trip:', trajet.value);
  try {
    const heure = new Date(`1970-01-01T${trajet.value.heure_depart}:00`).toTimeString().split(' ')[0];
    trajet.value.heure_depart = heure;
    const date = new Date(trajet.value.date_depart).toISOString().split('T')[0];
    trajet.value.date_depart = date;

    await store.dispatch('trajets/addTrajet', trajet.value);
    console.log('Trip added successfully');
    router.push('/trajet-list');
  } catch (error) {
    console.error('Erreur lors de l\'ajout du trajet :', error.response?.data?.error || error.message);
  }
};

const cancelForm = () => {
  console.log('Cancel add clicked');
  router.push('/trajet-list');
};
</script>

<style scoped>
.v-card {
  padding: 16px;
}

.v-card-title {
  font-size: 1.5rem;
  font-weight: bold;
}

.v-col {
  margin-bottom: 16px;
}

.d-flex {
  display: flex;
}

.justify-end {
  justify-content: flex-end;
}

.me-2 {
  margin-right: 8px;
}

.mt-4 {
  margin-top: 16px;
}
</style>
