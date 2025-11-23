<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 px-4 py-8">
    <div class="container mx-auto max-w-3xl">

      <div class="mb-8">
        <button
          @click="$router.back()"
          class="group flex items-center gap-2 text-gray-600 hover:text-green-600 font-semibold transition-all mb-4"
        >
          <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Kembali
        </button>

        <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center gap-3">
          <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
          </div>
          Formulir Pemesanan Tiket
        </h1>
        <p class="text-gray-600">Lengkapi data Anda untuk melanjutkan pemesanan</p>
      </div>

      <div v-if="schedule" class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-8 mb-6 text-white shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 opacity-10">
          <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24">
            <path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
          </svg>
        </div>

        <div class="relative z-10">
          <h3 class="text-2xl font-bold mb-4 flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Detail Perjalanan
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white bg-opacity-20 backdrop-blur-sm p-4 rounded-xl">
              <p class="text-green-400 text-sm mb-1">🎯 Tujuan</p>
              <p class="font-bold text-xl text-green-500">{{ schedule.destination }}</p>
            </div>
            <div class="bg-white bg-opacity-20 backdrop-blur-sm p-4 rounded-xl">
              <p class="text-green-400 text-sm mb-1">📅 Tanggal</p>
              <p class="font-bold text-green-500">{{ formatDate(schedule.departure_date) }}</p>
            </div>
            <div class="bg-white bg-opacity-20 backdrop-blur-sm p-4 rounded-xl">
              <p class="text-green-400 text-sm mb-1">⏰ Waktu</p>
              <p class="font-bold text-green-500">{{ schedule.departure_time }} WIB</p>
            </div>
            <div class="bg-white bg-opacity-20 backdrop-blur-sm p-4 rounded-xl">
              <p class="text-green-400 text-sm mb-1">💰 Harga per Tiket</p>
              <p class="font-bold text-xl text-green-500">Rp {{ formatPrice(schedule.price) }}</p>
            </div>
          </div>
          <div class="mt-4 bg-yellow-400 bg-opacity-20 backdrop-blur-sm p-3 rounded-xl border border-yellow-300 border-opacity-30">
            <p class="text-sm flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span><strong>Kuota tersedia:</strong> {{ schedule.available_quota }} kursi</span>
            </p>
          </div>
        </div>
      </div>

      <div v-if="error" class="bg-red-50 border-2 border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6 flex items-start gap-3 shadow-lg">
        <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
          <p class="font-semibold mb-1">Terjadi Kesalahan</p>
          <p class="text-sm">{{ error }}</p>
        </div>
      </div>

      <form @submit.prevent="submitBooking" class="bg-white rounded-2xl shadow-2xl p-8">
        <h3 class="text-xl font-bold mb-6 flex items-center gap-2 text-gray-800">
          <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
          Informasi Penumpang
        </h3>

        <div class="space-y-5">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">👤 Nama Lengkap *</label>
            <input
              v-model="form.passenger_name"
              type="text"
              required
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all outline-none"
              placeholder="Masukkan nama lengkap"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">📧 Email *</label>
            <input
              v-model="form.passenger_email"
              type="email"
              required
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all outline-none"
              placeholder="email@example.com"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">📱 Nomor Telepon *</label>
            <input
              v-model="form.passenger_phone"
              type="tel"
              required
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all outline-none"
              placeholder="08xxxxxxxxxx"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">🎫 Jumlah Tiket *</label>
            <input
              v-model.number="form.quantity"
              type="number"
              min="1"
              :max="schedule?.available_quota"
              required
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all outline-none"
            />
            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Maksimal {{ schedule?.available_quota }} tiket per pemesanan
            </p>
          </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-2xl mt-8 border-2 border-green-200">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-sm text-gray-600 mb-1">Total Pembayaran</p>
              <p class="text-xs text-gray-500">{{ form.quantity }} tiket × Rp {{ formatPrice(schedule?.price || 0) }}</p>
            </div>
            <div class="text-right">
              <p class="text-3xl font-bold text-green-600">Rp {{ formatPrice(totalPrice) }}</p>
            </div>
          </div>
        </div>

        <div class="flex gap-4 mt-8">
          <button
            type="button"
            @click="$router.back()"
            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-4 rounded-xl font-semibold transition-all flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Batal
          </button>
          <button
            type="submit"
            :disabled="loading"
            class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
          >
            <svg v-if="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <div v-else class="animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent"></div>
            {{ loading ? 'Memproses...' : 'Pesan Tiket' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import travelScheduleService from '@/services/travelSchedule';
import ticketOrderService from '@/services/ticketOrder';

export default {
  name: 'BookingForm',
  data() {
    return {
      schedule: null,
      form: {
        passenger_name: '',
        passenger_email: '',
        passenger_phone: '',
        quantity: 1
      },
      loading: false,
      error: null
    };
  },
  computed: {
    totalPrice() {
      return (this.schedule?.price || 0) * this.form.quantity;
    }
  },
  mounted() {
    this.loadSchedule();
  },
  methods: {
    async loadSchedule() {
      try {
        const scheduleId = this.$route.params.scheduleId;
        const response = await travelScheduleService.getScheduleDetail(scheduleId);
        this.schedule = response.data;
      } catch (error) {
        this.error = 'Gagal memuat detail jadwal';
      }
    },
    async submitBooking() {
      this.loading = true;
      this.error = null;

      try {
        const bookingData = {
          travel_schedule_id: this.schedule.id,
          ...this.form
        };

        const response = await ticketOrderService.createOrder(bookingData);

        if (response.success) {
          alert('✅ Pemesanan berhasil! Silakan lakukan pembayaran.');
          this.$router.push({ name: 'OrderHistory' });
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'Pemesanan gagal. Silakan coba lagi.';
      } finally {
        this.loading = false;
      }
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
