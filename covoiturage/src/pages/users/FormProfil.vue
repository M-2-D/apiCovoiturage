<template>
  <VRow>
    <VCol cols="12">
      <VCard title="Détails du compte">
        <VCardText class="d-flex">
          <!-- Avatar -->
          <VAvatar
            rounded="lg"
            size="100"
            class="me-6"
            :image="userAvatar"
          />

          <!-- Upload Photo -->
          <form class="d-flex flex-column justify-center gap-5">
            <div class="d-flex flex-wrap gap-2">
              <VBtn
                color="primary"
                @click="refInputEl?.click()"
              >
                <VIcon
                  icon="bx-cloud-upload"
                  class="d-sm-none"
                />
                <span class="d-none d-sm-block">Télécharger une nouvelle photo</span>
              </VBtn>

              <input
                ref="refInputEl"
                type="file"
                name="file"
                accept=".jpeg,.png,.jpg,GIF"
                hidden
                @input="changeAvatar"
              >

              <VBtn
                type="reset"
                color="error"
                variant="tonal"
                @click="resetAvatar"
              >
                <span class="d-none d-sm-block">Réinitialiser</span>
                <VIcon
                  icon="bx-refresh"
                  class="d-sm-none"
                />
              </VBtn>
            </div>

            <p class="text-body-1 mb-0">
              JPG, GIF ou PNG autorisés. Taille maximale de 800K
            </p>
          </form>
        </VCardText>

        <VDivider />

        <VCardText>
          <!-- Formulaire -->
          <VForm class="mt-6">
            <VRow>
              <!-- nom -->
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="accountDataLocal.nom"
                  placeholder="John"
                  label="Nom"
                />
              </VCol>

              <!-- Prenom -->
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="accountDataLocal.prenom"
                  placeholder="Doe"
                  label="Prénom"
                />
              </VCol>

              <!-- Email -->
              <VCol
                cols="12"
                md="6"
              >
                <VTextField
                  v-model="accountDataLocal.email"
                  label="E-mail"
                  mailto:placeholder="johndoe@gmail.com"
                  type="email"
                />
              </VCol>

              <!-- Téléphone -->
              <VCol
                cols="12"
                md="6"
              >
                <VTextField
                  v-model="accountDataLocal.telephone"
                  label="Numéro de téléphone"
                  placeholder="+1 (917) 543-9876"
                />
              </VCol>

              <!-- Mot de passe -->
              <VCol
                cols="12"
                md="6"
              >
                <VTextField
                  v-model="accountDataLocal.newPassword"
                  label="Nouveau mot de passe"
                  type="password"
                />
              </VCol>

              <!-- Confirmation du mot de passe -->
              <VCol
                cols="12"
                md="6"
              >
                <VTextField
                  v-model="accountDataLocal.passwordConfirmation"
                  label="Confirmez le mot de passe"
                  type="password"
                />
              </VCol>

              <!-- Actions du formulaire -->
              <VCol
                cols="12"
                class="d-flex flex-wrap gap-4"
              >
                <VBtn @click="saveChanges">Enregistrer les modifications</VBtn>

                <VBtn
                  color="secondary"
                  variant="tonal"
                  type="reset"
                  @click.prevent="resetForm"
                >
                  Réinitialiser
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>

    <VCol cols="12">
      <!-- Désactiver le compte -->
      <VCard title="Désactiver le compte">
        <VCardText>
          <div>
            <VCheckbox
              v-model="isAccountDeactivated"
              label="Je confirme la désactivation de mon compte"
            />
          </div>

          <VBtn
            :disabled="!isAccountDeactivated"
            color="error"
            class="mt-3"
           
             @click="desactiveUser"
          >
            Désactiver le compte
          </VBtn>
          <!--  @click="deactivateAccount" -->
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
// import avatarPlaceholder from '@images/avatars/avatar-placeholder.png'
import userAvatar from '@images/myImages/ib.jpg'
const store = useStore()
const router = useRouter()

const refInputEl = ref()
const isAccountDeactivated = ref(false)

// Utilisation d'un getter spécifique pour récupérer l'utilisateur connecté
const accountData = computed(() => store.getters['users/currentUser'])
const accountDataLocal = ref({ ...accountData.value })

const resetForm = () => {
  accountDataLocal.value = { ...accountData.value }
}

const changeAvatar = file => {
  const fileReader = new FileReader()
  const { files } = file.target
  if (files && files.length) {
    fileReader.readAsDataURL(files[0])
    fileReader.onload = () => {
      if (typeof fileReader.result === 'string')
        accountDataLocal.value.avatarImg = fileReader.result
    }
  }
}

const resetAvatar = () => {
  accountDataLocal.value.avatarImg = accountData.value.avatarImg
}

const saveChanges = async () => {
  if (accountDataLocal.value.newPassword !== accountDataLocal.value.passwordConfirmation) {
    alert('Les mots de passe ne correspondent pas')
    return
  }

  try {
     // Ajoutez la validation des mots de passe et la logique de mise à jour ici
    // const { newPassword, passwordConfirmation, ...userData } = accountDataLocal.value;
    // await store.dispatch('users/updateMyProfil', userData)
    // alert('Mot de passe mis à jour avec succès')
    await store.dispatch('users/updateProfile', accountDataLocal.value)
    router.push({ name: 'gestion-utilisateurs' });
  } catch (error) {
    console.error('Failed to save changes:', error)
  }
}

// const deactivateAccount = async (userId) => {
//   try {
//     await store.dispatch('users/deactivateUser', accountDataLocal.value.id)
//     router.push('/login')
//   } catch (error) {
//     console.error('Failed to deactivate account:', error)
//   }
// }
// const desactiveUser = async (userId) => {
  // try {
    // await store.dispatch('users/deactivateUser', userId);

    // const handleLogout = () => {
  // store.dispatch('logout');
// }
// const desactiveUser = async () => {
//   try {
//     const userId = accountDataLocal.value.id;
//     console.log("User ID:", userId); // Utilisez console.log pour le débogage
//     alert(userId); // Vérifiez la valeur avec une alerte
//     await store.dispatch('users/deactivateUser', userId);
//     await store.dispatch('users/fetchCurrentUser'); // Rafraîchissez les données de l'utilisateur
//     store.dispatch('logout');
//     // router.push('/login');
//   } catch (error) {
//     console.error('Error deactivating user:', error);
//   }
// }

onMounted(async () => {
  try {
        // const userData = await store.dispatch('users/fetchCurrentUser');

    const userData = await store.dispatch('users/fetchCurrentUser')
    accountDataLocal.value = { ...userData }
  } catch (error) {
    console.error('Failed to fetch current user:', error)
  }
})

const desactiveUser = async () => {
  try {
    const userId = accountDataLocal.value.id;
    console.log("User ID:", userId); // Vérifiez que vous obtenez l'ID utilisateur
    alert(userId); // Vérifiez l'ID avec une alerte

    await store.dispatch('users/deactivateUser', userId);
    console.log('User deactivated successfully');

    await store.dispatch('users/fetchCurrentUser'); // Rafraîchissez les données utilisateur
    console.log('Current user data refreshed');

    await store.dispatch('logout'); // Déconnexion
    console.log('User logged out');

    // Assurez-vous que la redirection est effectuée
    router.push('/login').catch(err => {
      console.error('Router push error:', err);
    });
  } catch (error) {
    console.error('Error deactivating user:', error);
  }
};

</script>

<style scoped>
/* / Ajoutez ici vos styles personnalisés / */
</style>

