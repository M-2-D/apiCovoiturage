<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <VCard class="auth-card pa-4 pt-7" max-width="448">
      <VCardTitle class="text-center text-2xl font-weight-bold">Créer un compte</VCardTitle>
      
      <!-- Onglets pour choisir le type d'inscription -->
      <VTabGroup v-model:active="currentTab" class="tab-group my-4">
        <VTab 
          v-for="tab in tabs" 
          :key="tab.value" 
          @click="selectTab(tab.value)"
          :class="['tab', { 'tab--active': currentTab === tab.value }]"
        >
          {{ tab.name }}
        </VTab>
      </VTabGroup>

      <!-- Affichage du formulaire basé sur l'onglet sélectionné -->
      <VCardText>
        <component :is="currentTabComponent" />
      </VCardText>
    </VCard>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import RegisterAdmin from '../pages/addUser/registerAdmin.vue'
import RegisterConducteur from '../pages/addUser/registerConducteur.vue'
import RegisterPassager from '../pages/addUser/registerPassager.vue'

const currentTab = ref('admin')

const tabs = [
  { name: 'Admin', value: 'admin' },
  { name: 'Conducteur', value: 'conducteur' },
  { name: 'Passager', value: 'passager' }
]

const selectTab = (tab) => {
  currentTab.value = tab
}

const currentTabComponent = computed(() => {
  switch (currentTab.value) {
    case 'admin':
      return RegisterAdmin
    case 'conducteur':
      return RegisterConducteur
    case 'passager':
      return RegisterPassager
    default:
      return RegisterAdmin
  }
})
</script>

<style lang="scss">
@use "@core/scss/template/pages/page-auth.scss";

.tab-group {
  display: flex;
  border-bottom: 2px solid #e0e0e0;
}

.tab {
  padding: 10px 20px;
  cursor: pointer;
  border-bottom: 2px solid transparent;
  transition: color 0.3s ease, border-color 0.3s ease;
  font-weight: normal;
}

.tab:hover {
  color: #1976d2;
}

.tab--active {
  border-color: #1976d2;
  color: #1976d2;
  font-weight: bold;
}
</style>
