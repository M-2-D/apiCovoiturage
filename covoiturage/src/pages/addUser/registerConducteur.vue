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
                v-model="form.CIN" 
                autofocus 
                label="CIN" 
                placeholder="CIN" 
                :error-messages="errors.CIN"
              />
            </VCol>
            <VCol cols="12">
              <VTextField 
                v-model="form.numero_permis" 
                autofocus 
                label="Numéro de Permis" 
                placeholder="Numéro de Permis" 
                :error-messages="errors.numero_permis"
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
                label="Password" 
                placeholder="············" 
                :type="isPasswordVisible ? 'text' : 'password'" 
                :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'" 
                @click:append-inner="isPasswordVisible = !isPasswordVisible" 
                :error-messages="errors.password" 
              />
            </VCol>
            <VCol cols="12">
              <VFileInput
                v-model="form.photo"
                label="Photo de Profil (optionnel)"
                placeholder="Choisir une photo"
                accept="image/*"
                :error-messages="errors.photo"
                outlined
              />
            </VCol>
            <VCol cols="12" class="d-flex align-center mt-1 mb-4">
              <VCheckbox id="privacy-policy" v-model="form.privacyPolicies" inline />
              <VLabel for="privacy-policy" style="opacity: 1;">
                <span class="me-1">J'accepte les</span>
                <a href="javascript:void(0)" class="text-primary">politiques de confidentialité & termes</a>
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
  numero_permis: '',
  CIN: '',
  privacyPolicies: false,
  photo_profil: null
});

const isPasswordVisible = ref(false);
const generalErrorMessage = ref('');
const errors = ref({
  nom: '',
  prenom: '',
  telephone: '',
  email: '',
  password: '',
  numero_permis: '',
  CIN: '',
  photo_profil: ''
});

const store = useStore();
const router = useRouter();

const validateForm = () => {
  let isValid = true;
  if (!form.value.nom) {
    errors.value.nom = 'Nom est requis';
    isValid = false;
  } else {
    errors.value.nom = '';
  }

  if (!form.value.prenom) {
    errors.value.prenom = 'Prénom est requis';
    isValid = false;
  } else {
    errors.value.prenom = '';
  }

  if (!form.value.telephone) {
    errors.value.telephone = 'Téléphone est requis';
    isValid = false;
  } else {
    errors.value.telephone = '';
  }
  if (!form.value.CIN) {
    errors.value.CIN = 'CIN est requis';
    isValid = false;
  } else {
    errors.value.CIN = '';
  }
  if (!form.value.numero_permis) {
    errors.value.numero_permis = 'Numéro de Permis est requis';
    isValid = false;
  } else {
    errors.value.numero_permis = '';
  }

  if (!form.value.email) {
    errors.value.email = 'Email est requis';
    isValid = false;
  } else if (!/\S+@\S+\.\S+/.test(form.value.email)) {
    errors.value.email = 'Email invalide';
    isValid = false;
  } else {
    errors.value.email = '';
  }

  if (!form.value.password) {
    errors.value.password = 'Mot de passe est requis';
    isValid = false;
  } else if (form.value.password.length < 6) {
    errors.value.password = 'Le mot de passe doit contenir au moins 6 caractères';
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
    const formData = new FormData();
    formData.append('nom', form.value.nom);
    formData.append('prenom', form.value.prenom);
    formData.append('telephone', form.value.telephone);
    formData.append('email', form.value.email);
    formData.append('password', form.value.password);
    formData.append('numero_permis', form.value.numero_permis);
    formData.append('CIN', form.value.CIN);
    if (form.value.photo) {
      formData.append('photo_profil', form.value.photo_profil);
    }

    await store.dispatch('registerConducteur', formData);
    router.push('/');
  } catch (error) {
    generalErrorMessage.value = 'Une erreur s\'est produite lors de l\'inscription. Veuillez réessayer.';
    console.error('Une erreur s\'est produite lors de l\'inscription:', error);
  }
};
</script>

<style lang="scss">
@use "@core/scss/template/pages/page-auth.scss";
</style>
