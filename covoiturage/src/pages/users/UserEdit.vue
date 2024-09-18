<template>
  <VCard>
    <VCardTitle>
      <h2>Modifier l'utilisateur</h2>
    </VCardTitle>
    <VCardText>
      <VForm @submit.prevent="updateUser">
        <!-- Form fields for user edit -->
        <VRow>
          <VCol cols="12" md="6">
            <VTextField v-model="user.nom" label="Nom" outlined />
          </VCol>
          <VCol cols="12" md="6">
            <VTextField v-model="user.prenom" label="Prénom" outlined />
          </VCol>
          <VCol cols="12" md="6">
            <VTextField v-model="user.telephone" label="Téléphone" outlined />
          </VCol>
          <VCol cols="12" md="6">
            <VTextField v-model="user.email" label="Email" outlined />
          </VCol>
          <VCol cols="12" md="6">
            <VSelect
              v-model="user.role"
              :items="roles"
              label="Rôle"
              outlined
            />
          </VCol>
          <VCol cols="12" class="d-flex justify-end mt-4">
            <VBtn type="submit" color="primary" class="me-2">Mettre à jour</VBtn>
            <!-- <VBtn @click="cancelEdit" color="secondary">Annuler</VBtn> -->
            <v-btn color="error" @click="cancelEdit">
                    <v-icon left>mdi-cancel</v-icon>
                    Annuler
                  </v-btn>
          </VCol>
        </VRow>
      </VForm>
    </VCardText>
  </VCard>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

const route = useRoute();
const router = useRouter();
const store = useStore();
const user = ref({});
const roles = ['Administrateur', 'Conducteur', 'Passager']; // Exemple de rôles

const fetchUser = async () => {
  const id = route.params.id;
  try {
    const userData = await store.dispatch('users/fetchUserById', id);
    user.value = userData;
  } catch (error) {
    console.error('Error fetching user:', error);
  }
};

onMounted(fetchUser);

const updateUser = async () => {
  console.log('Updating user:', user.value); // Ajouté pour débogage
  try {
    await store.dispatch('users/updateUser', user.value);
    console.log('User updated successfully'); // Ajouté pour débogage
    router.push({ name: 'gestion-utilisateurs' }); // Redirection après la mise à jour
  } catch (error) {
    console.error('Error updating user:', error);
  }
};

const cancelEdit = () => {
  console.log('Cancel edit clicked'); // Ajouté pour débogage
  router.push({ name: 'gestion-utilisateurs' }); // Redirection vers la liste des utilisateurs
};
</script>
