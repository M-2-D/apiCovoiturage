<template>
  <div>
    <VCard class="pa-4">
      <VCardTitle class="text-2xl font-weight-bold">Gestion des Utilisateurs</VCardTitle>

      <VDataTable
        :headers="headers"
        :items="users"
        item-key="id"
        class="elevation-1"
      >
        <template v-slot:top>
          <VBtn color="primary" @click="openCreateDialog">Ajouter un utilisateur</VBtn>
        </template>

        <template >
          <VBtn small @click="editUser(item)">Modifier</VBtn>
          <VBtn small @click="deleteUser(item.id)" class="ms-2" color="red">Supprimer</VBtn>
        </template>
      </VDataTable>

      <VDialog v-model:show="dialog.show" max-width="500px">
        <VCard>
          <VCardTitle class="text-h5">{{ dialog.title }}</VCardTitle>
          <VCardText>
            <VForm @submit.prevent="saveUser">
              <VTextField v-model="form.nom" label="Nom" required />
              <VTextField v-model="form.prenom" label="Prénom" required />
              <VTextField v-model="form.telephone" label="Téléphone" required />
              <VTextField v-model="form.email" label="Email" type="email" required />
              <VTextField v-model="form.password" label="Mot de passe" type="password" v-if="!isEdit" />
              <VSelect v-model="form.role" :items="roles" label="Rôle" required />
              <VFileInput v-model="form.photo_profil" label="Photo de profil" />
              <VBtn type="submit" color="primary">{{ dialog.buttonText }}</VBtn>
            </VForm>
          </VCardText>
        </VCard>
      </VDialog>
    </VCard>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from '@/axios'

const users = ref([])
const headers = [
  { text: 'Nom', value: 'nom' },
  { text: 'Prénom', value: 'prenom' },
  { text: 'Téléphone', value: 'telephone' },
  { text: 'Email', value: 'email' },
  { text: 'Rôle', value: 'role' },
  { text: 'Actions', value: 'actions', sortable: false }
]
const roles = ['Administrateur', 'Conducteur', 'Passager']
const dialog = ref({
  show: false,
  title: 'Ajouter un utilisateur',
  buttonText: 'Ajouter',
  user: null
})
const form = ref({
  nom: '',
  prenom: '',
  telephone: '',
  email: '',
  password: '',
  role: '',
  photo_profil: null
})
const isEdit = ref(false)

const fetchUsers = async () => {
  try {
    const response = await axios.get('/users')
    users.value = response.data
  } catch (error) {
    console.error('Error fetching users:', error)
  }
}

const openCreateDialog = () => {
  dialog.value.show = true
  dialog.value.title = 'Ajouter un utilisateur'
  dialog.value.buttonText = 'Ajouter'
  form.value = { nom: '', prenom: '', telephone: '', email: '', password: '', role: '', photo_profil: null }
  isEdit.value = false
}

const editUser = (user) => {
  form.value = { ...user, password: '' }
  dialog.value.show = true
  dialog.value.title = 'Modifier l\'utilisateur'
  dialog.value.buttonText = 'Modifier'
  isEdit.value = true
}

const saveUser = async () => {
  const formData = new FormData()
  for (const key in form.value) {
    formData.append(key, form.value[key])
  }

  try {
    if (isEdit.value) {
      await axios.put(`/users/${form.value.id}`, formData)
    } else {
      await axios.post('/users', formData)
    }
    dialog.value.show = false
    fetchUsers()
  } catch (error) {
    console.error('Error saving user:', error)
  }
}

const deleteUser = async (userId) => {
  try {
    await axios.delete(`/users/${userId}`)
    fetchUsers()
  } catch (error) {
    console.error('Error deleting user:', error)
  }
}

fetchUsers()
</script>

<style scoped>
/* Style personnalisé */
</style>
