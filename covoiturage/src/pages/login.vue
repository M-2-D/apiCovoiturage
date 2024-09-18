<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <VCard class="auth-card pa-4 pt-7" max-width="448">
      <VCardItem class="justify-center">
        <template #prepend>
          <div class="d-flex">
            <div class="d-flex text-primary" v-html="logo" />
          </div>
        </template>

        <VCardTitle class="text-2xl font-weight-bold">
          Covoiturage
        </VCardTitle>
      </VCardItem>

      <VCardText class="pt-2">
        <h5 class="text-h5 mb-1">
          Bienvenue sur notre plateforme ! 👋🏻
        </h5>
        <p class="mb-0">
          Veuillez vous connecter pour commencer à partager ou rechercher des trajets.
        </p>
      </VCardText>

      <VCardText>
        <VForm @submit.prevent="login">
          <VRow>
            <!-- Email -->
            <VCol cols="12">
              <VTextField
                v-model="form.email"
                autofocus
                placeholder="votremail@exemple.com"
                label="Email"
                type="email"
              />
            </VCol>

            <!-- Mot de passe -->
            <VCol cols="12">
              <VTextField
                v-model="form.password"
                label="Mot de passe"
                placeholder="············"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
              />

              <!-- Se souvenir de moi -->
              <div class="d-flex align-center justify-space-between flex-wrap mt-1 mb-4">
                <VCheckbox
                  v-model="form.remember"
                  label="Se souvenir de moi"
                />

                <RouterLink
                  class="text-primary ms-2 mb-1"
                  to="javascript:void(0)"
                >
                  Mot de passe oublié ?
                </RouterLink>
              </div>

              <!-- Bouton de connexion -->
              <VBtn
                block
                type="submit"
              >
                Se connecter
              </VBtn>
            </VCol>

            <!-- Créer un compte -->
            <VCol
              cols="12"
              class="text-center text-base"
            >
              <span>Nouveau sur notre plateforme ?</span>
              <RouterLink
                class="text-primary ms-2"
                to="/register"
              >
                Créer un compte
              </RouterLink>
            </VCol>

            <VCol
              cols="12"
              class="d-flex align-center"
            >
              <VDivider />
              <span class="mx-4">ou</span>
              <VDivider />
            </VCol>

            <!-- Fournisseurs d'authentification -->
            <VCol
              cols="12"
              class="text-center"
            >
              <AuthProvider />
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from '@/axios'; // Assurez-vous que ce chemin est correct

const router = useRouter();

const form = ref({
  email: '',
  password: '',
  remember: false,
});

const isPasswordVisible = ref(false);

const login = async () => {
  try {
    const response = await axios.post('/login', form.value);
    
    // Vérifiez la réponse pour l'état "is_blocked"
    if (response.data.token) {
      const user = response.data.user;
      
      if (user.is_blocked) {
        // Informer l'utilisateur que son compte est bloqué
        alert('Votre compte est bloqué. Veuillez contacter le support.');
        return;
      }
      
      // Enregistrez le token et les informations utilisateur
      localStorage.setItem('token', response.data.token);
      // Optionnel: stockez l'utilisateur dans le store ou un autre emplacement
      router.push('/dashboard');
    }
  } catch (error) {
    console.error(error);
    alert('Échec de la connexion : ' + (error.response?.data?.message || 'Une erreur est survenue.'));
  }
};
</script>

<style lang="scss">
@use "@core/scss/template/pages/page-auth.scss";
</style>
