<template>
  <v-card>
    <v-card-title>
      <h2>Ajouter un utilisateur</h2>
    </v-card-title>
    <v-card-text>
      <v-form @submit.prevent="submitForm">
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field v-model="form.nom" label="Nom" outlined dense required />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field v-model="form.prenom" label="Prénom" outlined dense required />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field v-model="form.telephone" label="Téléphone" outlined dense required />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field v-model="form.email" label="Email" outlined dense type="email" required />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field v-model="form.password" label="Mot de passe" outlined dense type="password" required />
          </v-col>
          <v-col cols="12" md="6">
            <v-select v-model="form.role" :items="roles" label="Rôle" outlined dense required @change="handleRoleChange" />
          </v-col>

          <!-- Champs conditionnels pour Conducteur -->
          <v-col cols="12" md="6" v-if="form.role === 'Conducteur'">
            <v-text-field v-model="form.numero_permis" label="Numéro de permis" outlined dense required />
          </v-col>
          <v-col cols="12" md="6" v-if="form.role === 'Conducteur'">
            <v-text-field v-model="form.CIN" label="CIN" outlined dense required />
          </v-col>
          
          <v-col cols="12" class="d-flex justify-end mt-4">
            <v-btn type="submit" color="primary" class="me-2">Ajouter l'utilisateur</v-btn>
            <v-btn color="error" @click="cancel">
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
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const router = useRouter();

const form = ref({
  nom: '',
  prenom: '',
  telephone: '',
  email: '',
  password: '',
  role: '',
  numero_permis: '',
  CIN: '',
});

const roles = ['Passager', 'Conducteur', 'Administrateur'];

const handleRoleChange = () => {
  if (form.value.role !== 'Conducteur') {
    form.value.numero_permis = '';
    form.value.CIN = '';
  }
};

const submitForm = async () => {
  console.log('Adding user:', form.value); // Ajouté pour débogage
  try {
    await store.dispatch('users/addUser', form.value);
    console.log('User added successfully'); // Ajouté pour débogage
    router.push({ name: 'gestion-utilisateurs' });
  } catch (error) {
    console.error('Erreur lors de l\'ajout de l\'utilisateur :', error);
  }
};

const cancel = () => {
  console.log('Cancel add clicked'); // Ajouté pour débogage
  router.push({ name: 'gestion-utilisateurs' });
};
</script>

<style scoped>
.v-card {
  padding: 16px;
}

.v-card-title h2 {
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
