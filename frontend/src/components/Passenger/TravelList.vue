<template>
  <div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Jadwal Travel Tersedia</h2>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium mb-2">Tujuan</label>
          <input
            v-model="filters.destination"
            type="text"
            placeholder="Cari tujuan..."
            class="w-full px-3 py-2 border rounded-md"
          />
        </div>
        <div>
          <label class="block text-sm font-medium mb-2">Tanggal</label>
          <input
            v-model="filters.date"
            type="date"
            class="w-full px-3 py-2 border rounded-md"
          />
        </div>
        <div class="flex items-end">
          <button
            @click="searchSchedules"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md"
          >
            Cari Jadwal
          </button>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="text-center py-8">
      <p class="text-gray-600">Loading...</p>
    </div>

    <!-- Schedule List -->
    <div v-else-if="schedules.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="schedule in schedules"
        :key="schedule.id"
        class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition"
      >
        <div class="flex justify-between items-start mb-4">
          <h3 class="text-lg font-bold">{{ schedule.destination }}</h3>
          <span
            :class="schedule.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
            class="px-2 py-1 rounded text-xs font-semibold"
          >
            {{ schedule.is_available ? 'Tersedia' : 'Penuh' }}
          </span>
        </div>

        <div class="space-y-2 mb-4">
          <p class="text-sm text-gray-600">
            <span class="font-medium">Tanggal:</span>
            {{ formatDate(schedule.departure_date) }}
          </p>
          <p class="text-sm text-gray-600">
            <span class="font-medium">Waktu:</span>
            {{ schedule.departure_time }}
          </p>
          <p class="text-sm text-gray-600">
            <span class="font-medium">Kuota Tersedia:</span>
            {{ schedule.available_quota }} / {{ schedule.quota }}
          </p>
          <p class="text-lg font-bold text-blue-600">
            Rp {{ formatPrice(schedule.price) }}
          </p>
        </div>

        <button
          @click="bookTravel(schedule)"
          :disabled="!schedule.is_available"
          class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md disabled:bg-gray-400 disabled:cursor-not-allowed"
        >
          Pesan Sekarang
        </button>
      </div>
    </div>

    <!-- No Data -->
    <div v-else class="text-center py-12">
      <p class="text-gray-600">Tidak ada jadwal tersedia</p>
    </div>
  </div>
</template>

<script>
import travelScheduleService from '@/services/travelSchedule';

export default {
  name: 'TravelList',
  data() {
    return {
      schedules: [],
      filters: {
        destination: '',
        date: '',
        available: true
      },
      loading: false
    };
  },
  mounted() {
    this.loadSchedules();
  },
  methods: {
    async loadSchedules() {
      this.loading = true;
      try {
        const response = await travelScheduleService.getAvailableSchedules(this.filters);
        this.schedules = response.data;
      } catch (error) {
        console.error('Error loading schedules:', error);
      } finally {
        this.loading = false;
      }
    },
    searchSchedules() {
      this.loadSchedules();
    },
    bookTravel(schedule) {
      this.$router.push({ name: 'Booking', params: { scheduleId: schedule.id } });
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price);
    }
  }
};
</script>
