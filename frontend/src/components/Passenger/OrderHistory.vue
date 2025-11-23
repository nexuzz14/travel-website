<template>
  <div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Riwayat Pemesanan</h2>

    <div v-if="loading" class="text-center py-8">
      <p class="text-gray-600">Loading...</p>
    </div>

    <div v-else-if="orders.length > 0" class="space-y-4">
      <div
        v-for="order in orders"
        :key="order.id"
        class="bg-white rounded-lg shadow p-6"
      >
        <div class="flex justify-between items-start mb-4">
          <div>
            <h3 class="text-lg font-bold">{{ order.destination }}</h3>
            <p class="text-sm text-gray-600">Order #{{ order.id }}</p>
          </div>
          <div class="text-right">
            <span
              :class="getStatusClass(order.status)"
              class="px-3 py-1 rounded-full text-xs font-semibold"
            >
              {{ getStatusText(order.status) }}
            </span>
            <br />
            <span
              :class="getPaymentStatusClass(order.payment_status)"
              class="px-3 py-1 rounded-full text-xs font-semibold mt-1 inline-block"
            >
              {{ getPaymentStatusText(order.payment_status) }}
            </span>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
          <div>
            <p class="text-sm text-gray-600">Tanggal Keberangkatan</p>
            <p class="font-medium">{{ formatDate(order.departure_date) }}</p>
            <p class="text-sm">{{ order.departure_time }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Nama Penumpang</p>
            <p class="font-medium">{{ order.passenger_name }}</p>
            <p class="text-sm">{{ order.quantity }} tiket</p>
          </div>
        </div>

        <div class="flex justify-between items-center pt-4 border-t">
          <div>
            <p class="text-sm text-gray-600">Total Pembayaran</p>
            <p class="text-lg font-bold text-blue-600">
              Rp {{ formatPrice(order.total_price) }}
            </p>
          </div>
          <div class="space-x-2">
            <button
              v-if="order.status === 'pending' && order.payment_status === 'pending'"
              @click="payOrder(order.id)"
              class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm"
            >
              Bayar Sekarang
            </button>
            <button
              v-if="order.payment_status === 'paid'"
              @click="downloadInvoice(order.id)"
              class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm"
            >
              Download Invoice
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-12">
      <p class="text-gray-600">Belum ada riwayat pemesanan</p>
      <router-link
        to="/passenger/travels"
        class="inline-block mt-4 bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded"
      >
        Lihat Jadwal Travel
      </router-link>
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
        alert('Gagal mengunduh invoice');
      }
    },
    getStatusClass(status) {
      const classes = {
        pending: 'bg-yellow-100 text-yellow-800',
        confirmed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800'
      };
      return classes[status] || 'bg-gray-100 text-gray-800';
    },
    getStatusText(status) {
      const texts = {
        pending: 'Menunggu',
        confirmed: 'Terkonfirmasi',
        cancelled: 'Dibatalkan'
      };
      return texts[status] || status;
    },
    getPaymentStatusClass(status) {
      const classes = {
        pending: 'bg-orange-100 text-orange-800',
        paid: 'bg-green-100 text-green-800',
        failed: 'bg-red-100 text-red-800'
      };
      return classes[status] || 'bg-gray-100 text-gray-800';
    },
    getPaymentStatusText(status) {
      const texts = {
        pending: 'Belum Bayar',
        paid: 'Lunas',
        failed: 'Gagal'
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
