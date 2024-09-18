<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import RegisterAdmin from './addUser/registerAdmin.vue'
import RegisterConducteur from './addUser/registerConducteur.vue'
import RegisterPassager from './addUser/registerPassager.vue'

const route = useRoute()
const activeTab = ref(route.params.tab)

// tabs
const tabs = [
  {
    title: 'RegisterAdmin',
    icon: 'bx-user',
    tab: 'registerAdmin',
  },
  {
    title: 'RegisterConducteur',
    icon: 'bx-lock-open',
    tab: 'registerConducteur',
  },
  {
    title: 'RegisterPassager',
    icon: 'bx-bell',
    tab: 'registerPassager',
  },
]
</script>

<template>
  <div>
    <VTabs
      v-model="activeTab"
      show-arrows
    >
      <VTab
        v-for="item in tabs"
        :key="item.icon"
        :value="item.tab"
      >
        <VIcon
          size="20"
          start
          :icon="item.icon"
        />
        {{ item.title }}
      </VTab>
    </VTabs>
    <VDivider />

    <VWindow
      v-model="activeTab"
      class="mt-5 disable-tab-transition"
    >
      <!-- Account -->
      <VWindowItem value="registerAdmin">
        <RegisterAdmin />
      </VWindowItem>

      <!-- Security -->
      <VWindowItem value="registerConducteur">
        <RegisterConducteur />
      </VWindowItem>

      <!-- Notification -->
      <VWindowItem value="registerPassager">
        <RegisterPassager />
      </VWindowItem>
    </VWindow>
  </div>
</template>
