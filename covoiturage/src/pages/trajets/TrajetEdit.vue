<template>
  <v-card>
    <v-card-title class="text-2xl font-weight-bold p-4">
      Modifier un Trajet
    </v-card-title>

    <v-card-text>
      <v-form @submit.prevent="submitForm">
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field
              label="Lieu de Départ"
              v-model="trajet.lieu_depart"
              required
              outlined
              dense
            />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              label="Lieu d'Arrivée"
              v-model="trajet.lieu_arrivee"
              required
              outlined
              dense
            />
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" md="6">
            <v-text-field
              label="Heure de Départ"
              v-model="trajet.heure_depart"
              type="time"
              required
              outlined
              dense
            />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              label="Date de Départ"
              v-model="trajet.date_depart"
              type="date"
              required
              outlined
              dense
            />
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" md="6">
            <v-text-field
              label="Nombre de Places"
              v-model.number="trajet.nombre_places"
              type="number"
              required
              outlined
              dense
            />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              label="Prix"
              v-model.number="trajet.prix"
              type="number"
              required
              outlined
              dense
            />
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" class="d-flex justify-end mt-4">
            <v-btn color="primary" type="submit" class="me-2">
              <v-icon left>mdi-check</v-icon>
              Enregistrer
            </v-btn>
            <v-btn color="error" @click="cancelEdit">
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
import { onMounted, ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter, useRoute } from 'vue-router';

const store = useStore();
const router = useRouter();
const route = useRoute();

const trajet = ref({
  lieu_depart: '',
  lieu_arrivee: '',
  heure_depart: '',
  date_depart: '',
  nombre_places: 0,
  prix: 0
});

const fetchTrajet = async () => {
  const id = route.params.id;
  if (id) {
    try {
      const data = await store.dispatch('trajets/fetchTrajetById', id);
      trajet.value = data;
    } catch (error) {
      console.error('Erreur lors de la récupération du trajet:', error);
    }
  }
};

onMounted(fetchTrajet);

const submitForm = async () => {
  try {
    // Vérification des données
    if (!trajet.value.lieu_depart || !trajet.value.lieu_arrivee || !trajet.value.heure_depart || !trajet.value.date_depart || trajet.value.nombre_places <= 0 || trajet.value.prix < 0) {
      throw new Error('Veuillez remplir tous les champs correctement.');
    }

    // Formatage de l'heure en H:i:s
    const heure = new Date(`1970-01-01T${trajet.value.heure_depart}:00`).toTimeString().split(' ')[0];
    trajet.value.heure_depart = heure;

    // Assurez-vous que date_depart est bien formatée en Y-m-d
    const date = new Date(trajet.value.date_depart).toISOString().split('T')[0];
    trajet.value.date_depart = date;

    console.log('Données envoyées:', trajet.value);
    
    if (route.params.id) {
      await store.dispatch('trajets/updateTrajet', trajet.value);
    } else {
      await store.dispatch('trajets/createTrajet', trajet.value);
    }
    router.push('/trajet-list'); // Rediriger vers la liste des trajets après ajout
  } catch (error) {
    console.error('Erreur lors de l\'enregistrement du trajet:', error.message);
  }
};

const cancelEdit = () => {
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
