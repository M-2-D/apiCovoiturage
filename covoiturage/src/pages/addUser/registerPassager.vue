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
        <h5 class="text-h5 mb-1">Rejoignez le voyage 🚗</h5>
        <p class="mb-0">Rendez vos trajets plus faciles et amusants !</p>
      </VCardText>

      <VCardText>
        <VForm @submit.prevent="handleRegister">
          <VRow>
            <VCol cols="12">
              <VTextField 
                v-model="form.nom" 
                autofocus 
                label="Nom" 
                placeholder="Entrer votre nom" 
                :error-messages="errors.nom" 
              />
            </VCol>
            <VCol cols="12">
              <VTextField 
                v-model="form.prenom" 
                autofocus 
                label="Prénom" 
                placeholder="Entrer votre prénom" 
                :error-messages="errors.prenom" 
              />
            </VCol>
            <VCol cols="12">
              <VTextField 
                v-model="form.telephone" 
                autofocus 
                label="Téléphone" 
                placeholder="Entrer votre téléphone" 
                :error-messages="errors.telephone" 
              />
            </VCol>
            <VCol cols="12">
              <VTextField 
                v-model="form.email" 
                label="Email" 
                placeholder="mame@email.com" 
                type="email" 
                :error-messages="errors.email" 
              />
            </VCol>
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
            <VCol cols="12">
              <VFileInput
                v-model="form.photo_profil"
                label="Photo de Profil (optionnel)"
                placeholder="Choisir une photo"
                accept="image/*"
                :error-messages="errors.photo_profil"
                outlined
              />
              <div v-if="form.photo_profil" class="mt-2">
                <img :src="form.photo_profil" alt="Aperçu" width="100" height="100" />
              </div>
            </VCol>
            <VCol cols="12" class="d-flex align-center mt-1 mb-4">
              <VCheckbox id="privacy-policy" v-model="form.privacyPolicies" inline />
              <VLabel for="privacy-policy" style="opacity: 1;">
                <span class="me-1">J'accepte les</span>
                <a href="javascript:void(0)" class="text-primary">politiques de confidentialité et les conditions</a>
              </VLabel>
            </VCol>
            <VCol cols="12">
              <VBtn block type="submit">S'inscrire</VBtn>
            </VCol>
            <VCol cols="12" class="text-center text-base">
              <span>Vous avez déjà un compte?</span>
              <RouterLink class="text-primary ms-2" to="/login">Se connecter</RouterLink>
            </VCol>
            <VCol cols="12" class="d-flex align-center">
              <VDivider />
              <span class="mx-4">ou</span>
              <VDivider />
            </VCol>
            <VCol cols="12" class="text-center">
              <AuthProvider />
            </VCol>
          </VRow>
        </VForm>
        <div v-if="generalErrorMessage" class="error-message">{{ generalErrorMessage }}</div>
      </VCardText>
    </VCard>
  </div>
</template>

<script setup>
import { useStore } from 'vuex';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import logo from '@images/logo.svg?raw';

const form = ref({
  nom: '',
  prenom: '',
  telephone: '',
  email: '',
  password: '',
  privacyPolicies: false,
  photo_profil: ''
});

const isPasswordVisible = ref(false);
const generalErrorMessage = ref('');
const errors = ref({
  nom: '',
  prenom: '',
  telephone: '',
  email: '',
  password: '',
  photo_profil: ''
});

const store = useStore();
const router = useRouter();

const validateForm = () => {
  let isValid = true;
  if (!form.value.nom) {
    errors.value.nom = 'Le nom est requis';
    isValid = false;
  } else {
    errors.value.nom = '';
  }

  if (!form.value.prenom) {
    errors.value.prenom = 'Le prénom est requis';
    isValid = false;
  } else {
    errors.value.prenom = '';
  }

  if (!form.value.telephone) {
    errors.value.telephone = 'Le téléphone est requis';
    isValid = false;
  } else {
    errors.value.telephone = '';
  }

  if (!form.value.email) {
    errors.value.email = 'L\'email est requis';
    isValid = false;
  } else if (!/\S+@\S+\.\S+/.test(form.value.email)) {
    errors.value.email = 'L\'email est invalide';
    isValid = false;
  } else {
    errors.value.email = '';
  }

  if (!form.value.password) {
    errors.value.password = 'Le mot de passe est requis';
    isValid = false;
  } else if (form.value.password.length < 6) {
    errors.value.password = 'Le mot de passe doit comporter au moins 6 caractères';
    isValid = false;
  } else {
    errors.value.password = '';
  }

  return isValid;
};

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      form.value.photo_profil = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const handleRegister = async () => {
  generalErrorMessage.value = '';
  if (!validateForm()) {
    return;
  }
  
  try {
    const userData = new FormData();
    userData.append('nom', form.value.nom);
    userData.append('prenom', form.value.prenom);
    userData.append('telephone', form.value.telephone);
    userData.append('email', form.value.email);
    userData.append('password', form.value.password);
    if (form.value.photo_profil) {
      userData.append('photo_profil', form.value.photo_profil);
    }
    
    await store.dispatch('registerPassager', userData);
    router.push('/');
  } catch (error) {
    generalErrorMessage.value = 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.';
    console.error('Une erreur est survenue lors de l\'inscription:', error);
  }
};
</script>

<style lang="scss">
@use "@core/scss/template/pages/page-auth.scss";
</style>
