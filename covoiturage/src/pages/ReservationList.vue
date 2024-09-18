<template>
  <div>
    <h1>Liste des Réservations</h1>
    <ul>
      <li v-for="reservation in reservations" :key="reservation.id">
        <pre>{{ reservation }}</pre>
        <button @click="deleteReservation(reservation.id)">Supprimer</button>
      </li>
    </ul>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
  name: 'ReservationList',
  computed: {
    ...mapGetters(['allReservations']),
  },
  methods: {
    ...mapActions('reservations', ['fetchReservations']),
  },
  created() {
    this.fetchReservations();
  },
};
</script>
<style scoped>
/* Ajoutez du style ici si nécessaire */
</style>
