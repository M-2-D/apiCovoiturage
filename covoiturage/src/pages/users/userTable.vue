<template>
  <VContainer fluid>
    <VRow>
      <VCol cols="12">
        <VRow>
          <VCol cols="9">
            <VCard class="text-2xl font-weight-bold p-4">
              <VCardTitle class="text-2xl font-weight-bold">Liste des utilisateurs</VCardTitle>
            </VCard>
          </VCol>
          <VCol cols="3" class="d-flex justify-end align-center">
            <VBtn title="Ajouter un utilisateur" color="primary" @click="() => router.push('/addUser')">
              <VIcon left>mdi-plus</VIcon>
              Ajouter un utilisateur
            </VBtn>
          </VCol>
        </VRow>
        <VCard>
          <VCardText>
            <VTable class="user-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nom</th>
                  <th>Prénom</th>
                  <th>Téléphone</th>
                  <th>Rôle</th>
                  <th>Email</th>
                  <th>Statut</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in allUsers" :key="user.id">
                  <td>{{ user.id }}</td>
                  <td>{{ user.nom }}</td>
                  <td>{{ user.prenom }}</td>
                  <td>{{ user.telephone }}</td>
                  <td>{{ user.role }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.is_blocked ? 'Bloqué' : 'Actif' }}</td>
                  <td>
                    <div class="action-icons">
                      <VIcon @click="viewUser(user.id)" color="info" small>mdi-eye</VIcon>
                      <VIcon @click="updateUser(user.id)" color="warning" small>mdi-pencil</VIcon>
                      <VIcon v-if="!user.is_blocked" @click="confirmBlockUser(user.id)" color="error" small>mdi-block</VIcon>
                      <VIcon v-if="user.is_blocked" @click="confirmUnblockUser(user.id)" color="success" small>mdi-check-circle</VIcon>
                      <VIcon @click="confirmDeleteUser(user.id)" color="error" small>mdi-delete</VIcon>
                    </div>
                  </td>
                </tr>
              </tbody>
            </VTable>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>

    <!-- Boîte de dialogue de confirmation de blocage -->
    <VDialog v-model="showBlockDialog" max-width="500">
      <VCard>
        <VCardTitle class="headline">Confirmer le Blocage</VCardTitle>
        <VCardText>
          Êtes-vous sûr de vouloir bloquer cet utilisateur ?
        </VCardText>
        <VCardActions>
          <VSpacer></VSpacer>
          <VBtn color="secondary" @click="showBlockDialog = false">Annuler</VBtn>
          <VBtn color="error" @click="blockUser">Bloquer</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <!-- Boîte de dialogue de confirmation de déblocage -->
    <VDialog v-model="showUnblockDialog" max-width="500">
      <VCard>
        <VCardTitle class="headline">Confirmer le Déblocage</VCardTitle>
        <VCardText>
          Êtes-vous sûr de vouloir débloquer cet utilisateur ?
        </VCardText>
        <VCardActions>
          <VSpacer></VSpacer>
          <VBtn color="secondary" @click="showUnblockDialog = false">Annuler</VBtn>
          <VBtn color="success" @click="unblockUser">Débloquer</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <!-- Boîte de dialogue de confirmation de suppression -->
    <VDialog v-model="showDeleteDialog" max-width="500">
      <VCard>
        <VCardTitle class="headline">Confirmer la Suppression</VCardTitle>
        <VCardText>
          Êtes-vous sûr de vouloir supprimer cet utilisateur ?
        </VCardText>
        <VCardActions>
          <VSpacer></VSpacer>
          <VBtn color="secondary" @click="showDeleteDialog = false">Annuler</VBtn>
          <VBtn color="error" @click="deleteUser">Supprimer</VBtn>
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

onMounted(() => {
  store.dispatch('users/fetchUsers');
});

const allUsers = computed(() => store.getters['users/allUsers']);

const viewUser = (userId) => {
  router.push({ name: 'user-detail', params: { userId } });
};

const updateUser = (userId) => {
  router.push({ name: 'user-edit', params: { id: userId } });
};

const showDeleteDialog = ref(false);
const userToDelete = ref(null);

const showBlockDialog = ref(false);
const userToBlock = ref(null);

const showUnblockDialog = ref(false);
const userToUnblock = ref(null);

const confirmDeleteUser = (userId) => {
  userToDelete.value = userId;
  showDeleteDialog.value = true;
};

const deleteUser = async () => {
  try {
    await store.dispatch('users/deleteUser', userToDelete.value);
    showDeleteDialog.value = false;
    store.dispatch('users/fetchUsers'); // Rafraîchir la liste des utilisateurs après la suppression
  } catch (error) {
    console.error('Erreur lors de la suppression de l\'utilisateur:', error);
  }
};

const confirmBlockUser = (userId) => {
  userToBlock.value = userId;
  showBlockDialog.value = true;
};

const blockUser = async () => {
  try {
    await store.dispatch('users/blockUser', userToBlock.value);
    showBlockDialog.value = false;
    store.dispatch('users/fetchUsers'); // Rafraîchir la liste des utilisateurs après le blocage
  } catch (error) {
    console.error('Erreur lors du blocage de l\'utilisateur:', error);
  }
};

const confirmUnblockUser = (userId) => {
  userToUnblock.value = userId;
  showUnblockDialog.value = true;
};

const unblockUser = async () => {
  try {
    await store.dispatch('users/unblockUser', userToUnblock.value);
    showUnblockDialog.value = false;
    store.dispatch('users/fetchUsers'); // Rafraîchir la liste des utilisateurs après le déblocage
  } catch (error) {
    console.error('Erreur lors du déblocage de l\'utilisateur:', error);
  }
};
</script>

<style scoped>
.user-table {
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
