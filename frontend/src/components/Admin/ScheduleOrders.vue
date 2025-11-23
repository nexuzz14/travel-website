<template>
  <div class="container mx-auto px-4 py-8">
    <div class="mb-6">
      <button
        @click="$router.back()"
        class="text-blue-600 hover:text-blue-800 flex items-center"
      >
        ← Kembali ke Jadwal
      </button>
    </div>

    <div v-if="loading" class="text-center py-8">
      <p class="text-gray-600">Loading...</p>
    </div>

    <div v-else-if="scheduleData">
      <!-- Schedule Info -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-2xl font-bold mb-4">Detail Jadwal Travel</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p class="text-sm text-gray-600">Tujuan</p>
            <p class="font-medium text-lg">{{ scheduleData.schedule.destination }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Tanggal Keberangkatan</p>
            <p class="font-medium">{{ formatDate(scheduleData.schedule.departure_date) }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Waktu Keberangkatan</p>
            <p class="font-medium">{{ scheduleData.schedule.departure_time }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Kuota</p>
            <p class="font-medium">
              {{ scheduleData.schedule.available_quota }} / {{ scheduleData.schedule.quota }}
              <span class="text-xs text-gray-500">
                ({{ scheduleData.schedule.quota - scheduleData.schedule.available_quota }} terpesan)
              </span>
            </p>
          </div>
        </div>
      </div>

      <!-- Orders List -->
      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-bold">Daftar Penumpang ({{ scheduleData.orders.length }})</h3>
        </div>

        <div v-if="scheduleData.orders.length > 0" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Penumpang</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Tiket</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Harga</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Order</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Bayar</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Order</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(order, index) in scheduleData.orders" :key="order.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ index + 1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ order.passenger_name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ order.passenger_email }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ order.passenger_phone }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span class="text-sm font-medium">{{ order.quantity }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  Rp {{ formatPrice(order.total_price) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="getOrderStatusClass(order.status)"
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  >
                    {{ getOrderStatusText(order.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="getPaymentStatusClass(order.payment_status)"
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
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

        <div v-else class="px-6 py-12 text-center">
          <p class="text-gray-500">Belum ada penumpang yang memesan jadwal ini</p>
        </div>
      </div>

      <!-- Summary -->
      <div v-if="scheduleData.orders.length > 0" class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-50 rounded-lg p-6">
          <p class="text-sm text-blue-600 font-medium">Total Pemesanan</p>
          <p class="text-3xl font-bold text-blue-700">{{ scheduleData.orders.length }}</p>
        </div>
        <div class="bg-green-50 rounded-lg p-6">
          <p class="text-sm text-green-600 font-medium">Total Penumpang</p>
          <p class="text-3xl font-bold text-green-700">{{ getTotalPassengers() }}</p>
        </div>
        <div class="bg-purple-50 rounded-lg p-6">
          <p class="text-sm text-purple-600 font-medium">Total Pendapatan</p>
          <p class="text-2xl font-bold text-purple-700">Rp {{ formatPrice(getTotalRevenue()) }}</p>
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
        alert('Gagal memuat data penumpang');
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
        pending: 'bg-yellow-100 text-yellow-800',
        confirmed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800'
      };
      return classes[status] || 'bg-gray-100 text-gray-800';
    },
    getOrderStatusText(status) {
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
