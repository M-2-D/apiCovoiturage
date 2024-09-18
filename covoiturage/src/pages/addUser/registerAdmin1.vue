<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import logo from '@images/logo.svg?raw';

const form = ref({
  nom: '',
  prenom: '',
  telephone: '',
  email: '',
  password: '',
  privacyPolicies: false,
  photo_profil: null,
});

const isPasswordVisible = ref(false);
const generalErrorMessage = ref('');
const errors = ref({
  nom: '',
  prenom: '',
  telephone: '',
  email: '',
  password: '',
  photo_profil: null,
  privacyPolicies: false,
});

const store = useStore();
const router = useRouter();

const validateForm = () => {
  let isValid = true;
  if (!form.value.nom) {
    errors.value.nom = 'Nom is required';
    isValid = false;
  } else {
    errors.value.nom = '';
  }

  if (!form.value.prenom) {
    errors.value.prenom = 'Prénom is required';
    isValid = false;
  } else {
    errors.value.prenom = '';
  }

  if (!form.value.telephone) {
    errors.value.telephone = 'Téléphone is required';
    isValid = false;
  } else {
    errors.value.telephone = '';
  }

  if (!form.value.email) {
    errors.value.email = 'Email is required';
    isValid = false;
  } else if (!/\S+@\S+\.\S+/.test(form.value.email)) {
    errors.value.email = 'Email is invalid';
    isValid = false;
  } else {
    errors.value.email = '';
  }

  if (!form.value.password) {
    errors.value.password = 'Password is required';
    isValid = false;
  } else if (form.value.password.length < 6) {
    errors.value.password = 'Password must be at least 6 characters';
    isValid = false;
  } else {
    errors.value.password = '';
  }

  return isValid;
};

const handleRegister = async () => {
  generalErrorMessage.value = '';
  if (!validateForm()) {
    return;
  }
  
  try {
    const userData = {
      nom: form.value.nom,
      prenom: form.value.prenom,
      telephone: form.value.telephone,
      email: form.value.email,
      password: form.value.password,
      photo_profil: form.value.photo_profil
    };

    // Envoi de la requête d'inscription
    await store.dispatch('registerAdmin', userData);
    router.push('/');
  } catch (error) {
    generalErrorMessage.value = 'An error occurred during registration. Please try again.';
    console.error('An error occurred during registration:', error);
  }
};
</script>

<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <VCard class="auth-card pa-4 pt-7" max-width="448">
      <VCardItem class="justify-center">
        <template #prepend>
          <div class="d-flex">
            <div class="d-flex text-primary" v-html="logo" />
          </div>
        </template>
        <VCardTitle class="text-2xl font-weight-bold">sneat</VCardTitle>
      </VCardItem>

      <VCardText class="pt-2">
        <h5 class="text-h5 mb-1">Adventure starts here 🚀</h5>
        <p class="mb-0">Make your app management easy and fun!</p>
      </VCardText>

      <VCardText>
        <VForm @submit.prevent="handleRegister">
          <VRow>
            <!-- Nom -->
            <VCol cols="12">
              <VTextField
                v-model="form.nom"
                autofocus
                label="Nom"
                placeholder="DIENG"
                :error-messages="errors.nom"
              />
            </VCol>
            <!-- Prénom -->
            <VCol cols="12">
              <VTextField
                v-model="form.prenom"
                autofocus
                label="Prénom"
                placeholder="Mame"
                :error-messages="errors.prenom"
              />
            </VCol>
            <!-- Téléphone -->
            <VCol cols="12">
              <VTextField
                v-model="form.telephone"
                autofocus
                label="Téléphone"
                placeholder="Tel"
                :error-messages="errors.telephone"
              />
            </VCol>
            <!-- Email -->
            <VCol cols="12">
              <VTextField
                v-model="form.email"
                label="Email"
                placeholder="johndoe@email.com"
                type="email"
                :error-messages="errors.email"
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
                :error-messages="errors.password"
              />
            </VCol>
            <!-- Photo -->
            <VCol cols="12">
              <VFileInput
                v-model="form.photo_profil"
                label="Photo (optionnelle)"
                placeholder="Choisir un fichier"
                accept="image/*"
                @change="onFileChange"
              />
            </VCol>
            <!-- Politique de confidentialité -->
            <VCol cols="12">
              <div class="d-flex align-center mt-1 mb-4">
                <VCheckbox
                  id="privacy-policy"
                  v-model="form.privacyPolicies"
                  inline
                />
                <VLabel
                  for="privacy-policy"
                  style="opacity: 1;"
                >
                  <span class="me-1">I agree to</span>
                  <a
                    href="javascript:void(0)"
                    class="text-primary"
                  >privacy policy & terms</a>
                </VLabel>
              </div>
              <div v-if="generalErrorMessage" class="text-red-500">{{ generalErrorMessage }}</div>
            </VCol>
            <!-- Bouton d'inscription -->
            <VCol cols="12">
              <VBtn
                block
                type="submit"
              >
                Sign up
              </VBtn>
            </VCol>
            <!-- Connexion -->
            <VCol cols="12" class="text-center text-base">
              <span>Already have an account?</span>
              <RouterLink
                class="text-primary ms-2"
                to="/login"
              >
                Sign in instead
              </RouterLink>
            </VCol>
            <!-- Diviseur -->
            <VCol cols="12" class="d-flex align-center">
              <VDivider />
              <span class="mx-4">or</span>
              <VDivider />
            </VCol>
            <!-- Auth providers -->
            <VCol cols="12" class="text-center">
             
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </div>
</template>

<style lang="scss">
@use "@core/scss/template/pages/page-auth.scss";
</style>
