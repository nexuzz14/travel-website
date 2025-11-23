<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 px-4 py-8">
    <div class="container mx-auto max-w-7xl">

      <div class="mb-6">
        <button
          @click="$router.back()"
          class="group flex items-center gap-2 text-gray-600 hover:text-blue-600 font-semibold transition-all"
        >
          <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Kembali ke Jadwal
        </button>
      </div>

      <div v-if="loading" class="flex items-center justify-center py-20">
        <div class="text-center">
          <div class="animate-spin rounded-full h-16 w-16 border-4 border-blue-200 border-t-blue-600 mx-auto mb-4"></div>
          <p class="text-gray-600 font-medium">Memuat data...</p>
        </div>
      </div>

      <div v-else-if="scheduleData">
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border-l-4 border-blue-600">
          <div class="flex items-center gap-3 mb-6">
            <div class="p-3 bg-blue-100 rounded-xl">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-800">Detail Jadwal Travel</h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-5 rounded-xl border border-blue-100">
              <p class="text-sm text-gray-600 font-medium mb-1">🎯 Tujuan</p>
              <p class="font-bold text-xl text-gray-800">{{ scheduleData.schedule.destination }}</p>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-5 rounded-xl border border-purple-100">
              <p class="text-sm text-gray-600 font-medium mb-1">📅 Tanggal Keberangkatan</p>
              <p class="font-semibold text-gray-800">{{ formatDate(scheduleData.schedule.departure_date) }}</p>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-5 rounded-xl border border-green-100">
              <p class="text-sm text-gray-600 font-medium mb-1">⏰ Waktu Keberangkatan</p>
              <p class="font-semibold text-gray-800 text-lg">{{ scheduleData.schedule.departure_time }}</p>
            </div>
            <div class="bg-gradient-to-br from-orange-50 to-yellow-50 p-5 rounded-xl border border-orange-100">
              <p class="text-sm text-gray-600 font-medium mb-1">👥 Kuota</p>
              <p class="font-bold text-gray-800">
                <span class="text-2xl">{{ scheduleData.schedule.available_quota }}</span>
                <span class="text-gray-500"> / {{ scheduleData.schedule.quota }}</span>
              </p>
              <p class="text-xs text-gray-500 mt-1">
                ({{ scheduleData.schedule.quota - scheduleData.schedule.available_quota }} terpesan)
              </p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
          <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
            <h3 class="text-2xl font-bold text-white flex items-center gap-3">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
              Daftar Penumpang
              <span class="ml-2 bg-white text-blue-600 px-3 py-1 rounded-full text-sm font-bold">
                {{ scheduleData.orders.length }}
              </span>
            </h3>
          </div>

          <div v-if="scheduleData.orders.length > 0" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">👤 Nama Penumpang</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">📧 Email</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">📱 Telepon</th>
                  <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">🎫 Tiket</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">💰 Total</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">📋 Status Order</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">💳 Pembayaran</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">🕒 Tanggal</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-100">
                <tr v-for="(order, index) in scheduleData.orders" :key="order.id"
                    class="hover:bg-blue-50 transition-colors duration-150">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-full font-bold text-sm">
                      {{ index + 1 }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-semibold text-gray-800">{{ order.passenger_name }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ order.passenger_email }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ order.passenger_phone }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold">
                      {{ order.quantity }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                    Rp {{ formatPrice(order.total_price) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="getOrderStatusClass(order.status)"
                      class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full"
                    >
                      {{ getOrderStatusText(order.status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="getPaymentStatusClass(order.payment_status)"
                      class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full"
                    >
                      {{ getPaymentStatusText(order.payment_status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ formatDateTime(order.created_at) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-else class="px-6 py-16 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
              <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
            </div>
            <p class="text-gray-500 font-medium text-lg">Belum ada penumpang yang memesan jadwal ini</p>
            <p class="text-gray-400 text-sm mt-2">Pemesanan akan muncul di sini setelah penumpang melakukan booking</p>
          </div>
        </div>

        <div v-if="scheduleData.orders.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 shadow-xl text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
              <p class="text-blue-100 font-semibold">Total Pemesanan</p>
              <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="blue" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
            </div>
            <p class="text-5xl font-bold">{{ scheduleData.orders.length }}</p>
            <p class="text-blue-100 text-sm mt-2">Order terdaftar</p>
          </div>

          <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 shadow-xl text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
              <p class="text-green-100 font-semibold">Total Penumpang</p>
              <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="green" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
              </div>
            </div>
            <p class="text-5xl font-bold">{{ getTotalPassengers() }}</p>
            <p class="text-green-100 text-sm mt-2">Kursi terisi</p>
          </div>

          <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl p-6 shadow-xl text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
              <p class="text-purple-100 font-semibold">Total Pendapatan</p>
              <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="purple" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </div>
            <p class="text-3xl font-bold">Rp {{ formatPrice(getTotalRevenue()) }}</p>
            <p class="text-purple-100 text-sm mt-2">Revenue terkumpul</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import travelScheduleService from '@/services/travelSchedule';

export default {
  name: 'ScheduleOrders',
  data() {
    return {
      scheduleData: null,
      loading: false
    };
  },
  mounted() {
    this.loadScheduleOrders();
  },
  methods: {
    async loadScheduleOrders() {
      this.loading = true;
      try {
        const scheduleId = this.$route.params.scheduleId;
        const response = await travelScheduleService.getScheduleOrders(scheduleId);
        this.scheduleData = response.data;
      } catch (error) {
        console.error('Error loading schedule orders:', error);
        alert('❌ Gagal memuat data penumpang');
      } finally {
        this.loading = false;
      }
    },
    getTotalPassengers() {
      return this.scheduleData.orders.reduce((sum, order) => sum + order.quantity, 0);
    },
    getTotalRevenue() {
      return this.scheduleData.orders.reduce((sum, order) => sum + parseFloat(order.total_price), 0);
    },
    getOrderStatusClass(status) {
      const classes = {
        pending: 'bg-yellow-100 text-yellow-700 border border-yellow-200',
        confirmed: 'bg-green-100 text-green-700 border border-green-200',
        cancelled: 'bg-red-100 text-red-700 border border-red-200'
      };
      return classes[status] || 'bg-gray-100 text-gray-700';
    },
    getOrderStatusText(status) {
      const texts = {
        pending: '⏳ Menunggu',
        confirmed: '✅ Terkonfirmasi',
        cancelled: '❌ Dibatalkan'
      };
      return texts[status] || status;
    },
    getPaymentStatusClass(status) {
      const classes = {
        pending: 'bg-orange-100 text-orange-700 border border-orange-200',
        paid: 'bg-green-100 text-green-700 border border-green-200',
        failed: 'bg-red-100 text-red-700 border border-red-200'
      };
      return classes[status] || 'bg-gray-100 text-gray-700';
    },
    getPaymentStatusText(status) {
      const texts = {
        pending: '⏱️ Belum Bayar',
        paid: '💵 Lunas',
        failed: '⛔ Gagal'
      };
      return texts[status] || status;
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },
    formatDateTime(datetime) {
      return new Date(datetime).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price);
    }
  }
};
</script>
