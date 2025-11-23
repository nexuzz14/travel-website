<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 px-4 py-8">
    <div class="container mx-auto max-w-6xl">
      <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2 flex items-center gap-3">
          <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
          </div>
          Riwayat Pemesanan
        </h1>
        <p class="text-gray-600">Lihat dan kelola semua pemesanan tiket Anda</p>
      </div>

      <div v-if="loading" class="flex items-center justify-center py-20">
        <div class="text-center">
          <div class="animate-spin rounded-full h-16 w-16 border-4 border-green-200 border-t-green-600 mx-auto mb-4"></div>
          <p class="text-gray-600 font-medium">Memuat riwayat pemesanan...</p>
        </div>
      </div>

      <div v-else-if="orders.length > 0" class="space-y-6">
        <div
          v-for="order in orders"
          :key="order.id"
          class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden"
        >
          <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 opacity-10">
              <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
              </svg>
            </div>

            <div class="relative z-10 flex justify-between items-start">
              <div>
                <h3 class="text-2xl font-bold mb-1">{{ order.destination }}</h3>
                <p class="text-green-100 text-sm">Order ID: #{{ order.id }}</p>
              </div>
              <div class="text-right space-y-2">
                <span
                  :class="getStatusClass(order.status)"
                  class="inline-block px-4 py-2 rounded-xl text-xs font-bold shadow-lg"
                >
                  {{ getStatusText(order.status) }}
                </span>
                <br />
                <span
                  :class="getPaymentStatusClass(order.payment_status)"
                  class="inline-block px-4 py-2 rounded-xl text-xs font-bold shadow-lg"
                >
                  {{ getPaymentStatusText(order.payment_status) }}
                </span>
              </div>
            </div>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="space-y-4">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-xl border border-blue-100">
                  <p class="text-xs text-gray-600 font-medium mb-1">📅 Tanggal Keberangkatan</p>
                  <p class="font-bold text-gray-800">{{ formatDate(order.departure_date) }}</p>
                  <p class="text-sm text-gray-600 mt-1">⏰ {{ order.departure_time }} WIB</p>
                </div>
              </div>

              <div class="space-y-4">
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-4 rounded-xl border border-purple-100">
                  <p class="text-xs text-gray-600 font-medium mb-1">👤 Nama Penumpang</p>
                  <p class="font-bold text-gray-800">{{ order.passenger_name }}</p>
                  <p class="text-sm text-gray-600 mt-1">🎫 {{ order.quantity }} tiket</p>
                </div>
              </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center pt-6 border-t-2 border-gray-200 gap-4">
              <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-4 rounded-xl border-2 border-green-200 w-full md:w-auto">
                <p class="text-sm text-gray-600 font-medium mb-1">💰 Total Pembayaran</p>
                <p class="text-3xl font-bold text-green-600">
                  Rp {{ formatPrice(order.total_price) }}
                </p>
              </div>

              <div class="flex gap-3 w-full md:w-auto">
                <button
                  v-if="order.status === 'pending' && order.payment_status === 'pending'"
                  @click="payOrder(order.id)"
                  class="flex-1 md:flex-initial bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                  </svg>
                  Bayar Sekarang
                </button>

                <button
                  v-if="order.payment_status === 'paid'"
                  @click="downloadInvoice(order.id)"
                  class="flex-1 md:flex-initial bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                  Download Invoice
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-20">
        <div class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-6">
          <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mb-3">Belum Ada Pemesanan</h3>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
          Anda belum memiliki riwayat pemesanan. Mulai perjalanan Anda dengan memesan tiket travel sekarang!
        </p>
        <router-link
          to="/passenger/dashboard/travels"
          class="inline-flex items-center gap-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-8 py-4 rounded-xl font-semibold shadow-xl hover:shadow-2xl transition-all"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          Lihat Jadwal Travel
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import ticketOrderService from '@/services/ticketOrder';
import paymentService from '@/services/payment';

export default {
  name: 'OrderHistory',
  data() {
    return {
      orders: [],
      loading: false
    };
  },
  mounted() {
    this.loadOrders();
  },
  methods: {
    async loadOrders() {
      this.loading = true;
      try {
        const response = await ticketOrderService.getOrders();
        this.orders = response.data;
      } catch (error) {
        console.error('Error loading orders:', error);
      } finally {
        this.loading = false;
      }
    },
    payOrder(orderId) {
      this.$router.push({ name: 'Payment', params: { orderId } });
    },
    async downloadInvoice(orderId) {
      try {
        await paymentService.downloadInvoice(orderId);
      } catch (error) {
        alert('❌ Gagal mengunduh invoice');
      }
    },
    getStatusClass(status) {
      const classes = {
        pending: 'bg-yellow-400 text-yellow-900 border-2 border-yellow-500',
        confirmed: 'bg-green-400 text-green-900 border-2 border-green-500',
        cancelled: 'bg-red-400 text-red-900 border-2 border-red-500'
      };
      return classes[status] || 'bg-gray-400 text-gray-900';
    },
    getStatusText(status) {
      const texts = {
        pending: '⏳ Menunggu',
        confirmed: '✅ Terkonfirmasi',
        cancelled: '❌ Dibatalkan'
      };
      return texts[status] || status;
    },
    getPaymentStatusClass(status) {
      const classes = {
        pending: 'bg-orange-400 text-orange-900 border-2 border-orange-500',
        paid: 'bg-green-400 text-green-900 border-2 border-green-500',
        failed: 'bg-red-400 text-red-900 border-2 border-red-500'
      };
      return classes[status] || 'bg-gray-400 text-gray-900';
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
