<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 px-4 py-8">
    <div class="container mx-auto max-w-7xl">
      <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2 flex items-center gap-3">
          <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
          </div>
          Jadwal Travel Tersedia
        </h1>
        <p class="text-gray-600">Temukan perjalanan terbaik untuk Anda</p>
      </div>

      <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border-t-4 border-green-600">
        <div class="flex items-center gap-2 mb-4">
          <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
          </svg>
          <h3 class="text-lg font-bold text-gray-800">Filter Pencarian</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">🎯 Tujuan</label>
            <input
              v-model="filters.destination"
              type="text"
              placeholder="Cari tujuan..."
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all outline-none"
            />
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">📅 Tanggal</label>
            <input
              v-model="filters.date"
              type="date"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all outline-none"
            />
          </div>
          <div class="flex items-end">
            <button
              @click="searchSchedules"
              class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              Cari Jadwal
            </button>
          </div>
        </div>
      </div>

      <div v-if="loading" class="flex items-center justify-center py-20">
        <div class="text-center">
          <div class="animate-spin rounded-full h-16 w-16 border-4 border-green-200 border-t-green-600 mx-auto mb-4"></div>
          <p class="text-gray-600 font-medium">Mencari jadwal travel...</p>
        </div>
      </div>

      <div v-else-if="schedules.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="schedule in schedules"
          :key="schedule.id"
          class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group transform hover:-translate-y-1"
        >

          <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 opacity-10">
              <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
              </svg>
            </div>
            <div class="relative z-10">
              <div class="flex justify-between items-start mb-2">
                <h3 class="text-2xl font-bold">{{ schedule.destination }}</h3>
                <span
                  :class="schedule.is_available ? 'bg-white text-green-600' : 'bg-red-500 text-white'"
                  class="px-3 py-1 rounded-full text-xs font-bold shadow-lg"
                >
                  {{ schedule.is_available ? '✓ Tersedia' : '✗ Penuh' }}
                </span>
              </div>
            </div>
          </div>

          <div class="p-6">
            <div class="space-y-3 mb-6">
              <div class="flex items-center gap-3 text-gray-700">
                <div class="bg-green-100 p-2 rounded-lg">
                  <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-xs text-gray-500 font-medium">Tanggal Keberangkatan</p>
                  <p class="font-semibold">{{ formatDate(schedule.departure_date) }}</p>
                </div>
              </div>

              <div class="flex items-center gap-3 text-gray-700">
                <div class="bg-blue-100 p-2 rounded-lg">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-xs text-gray-500 font-medium">Waktu Keberangkatan</p>
                  <p class="font-semibold">{{ schedule.departure_time }} WIB</p>
                </div>
              </div>

              <div class="flex items-center gap-3 text-gray-700">
                <div class="bg-purple-100 p-2 rounded-lg">
                  <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-xs text-gray-500 font-medium">Kuota Tersedia</p>
                  <p class="font-semibold">
                    <span class="text-green-600">{{ schedule.available_quota }}</span> / {{ schedule.quota }} kursi
                  </p>
                </div>
              </div>
            </div>

            <!-- Price & Button -->
            <div class="pt-4 border-t border-gray-200">
              <div class="flex items-center justify-between mb-4">
                <div>
                  <p class="text-xs text-gray-500 font-medium">Harga per Tiket</p>
                  <p class="text-2xl font-bold text-green-600">
                    Rp {{ formatPrice(schedule.price) }}
                  </p>
                </div>
              </div>

              <button
                @click="bookTravel(schedule)"
                :disabled="!schedule.is_available"
                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed flex items-center justify-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                {{ schedule.is_available ? 'Pesan Sekarang' : 'Tiket Habis' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
          <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Tidak Ada Jadwal Tersedia</h3>
        <p class="text-gray-600 mb-6">Coba ubah filter pencarian atau periksa kembali nanti</p>
        <button
          @click="filters.destination = ''; filters.date = ''; searchSchedules()"
          class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all"
        >
          Reset Filter
        </button>
      </div>
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
