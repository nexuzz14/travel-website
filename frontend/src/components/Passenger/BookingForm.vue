<template>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
      <h2 class="text-2xl font-bold mb-6">Formulir Pemesanan Tiket</h2>

      <div v-if="schedule" class="bg-blue-50 rounded-lg p-6 mb-6">
        <h3 class="font-bold text-lg mb-2">Detail Perjalanan</h3>
        <div class="space-y-1 text-sm">
          <p><span class="font-medium">Tujuan:</span> {{ schedule.destination }}</p>
          <p><span class="font-medium">Tanggal:</span> {{ formatDate(schedule.departure_date) }}</p>
          <p><span class="font-medium">Waktu:</span> {{ schedule.departure_time }}</p>
          <p><span class="font-medium">Harga per tiket:</span> Rp {{ formatPrice(schedule.price) }}</p>
          <p><span class="font-medium">Kuota tersedia:</span> {{ schedule.available_quota }}</p>
        </div>
      </div>

      <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ error }}
      </div>

      <form @submit.prevent="submitBooking" class="bg-white rounded-lg shadow p-6">
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Nama Penumpang *</label>
          <input
            v-model="form.passenger_name"
            type="text"
            required
            class="w-full px-3 py-2 border rounded-md"
            placeholder="Nama lengkap"
          />
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Email *</label>
          <input
            v-model="form.passenger_email"
            type="email"
            required
            class="w-full px-3 py-2 border rounded-md"
            placeholder="email@example.com"
          />
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Nomor Telepon *</label>
          <input
            v-model="form.passenger_phone"
            type="tel"
            required
            class="w-full px-3 py-2 border rounded-md"
            placeholder="08xxxxxxxxxx"
          />
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium mb-2">Jumlah Tiket *</label>
          <input
            v-model.number="form.quantity"
            type="number"
            min="1"
            :max="schedule?.available_quota"
            required
            class="w-full px-3 py-2 border rounded-md"
          />
          <p class="text-xs text-gray-500 mt-1">
            Maksimal {{ schedule?.available_quota }} tiket
          </p>
        </div>

        <div class="bg-gray-50 p-4 rounded-md mb-6">
          <div class="flex justify-between text-lg font-bold">
            <span>Total Pembayaran:</span>
            <span class="text-blue-600">Rp {{ formatPrice(totalPrice) }}</span>
          </div>
        </div>

        <div class="flex gap-3">
          <button
            type="button"
            @click="$router.back()"
            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md"
          >
            Kembali
          </button>
          <button
            type="submit"
            :disabled="loading"
            class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md disabled:opacity-50"
          >
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
          alert('Pemesanan berhasil! Silakan lakukan pembayaran.');
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
