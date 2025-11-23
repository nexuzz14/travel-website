<template>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
      <h2 class="text-2xl font-bold mb-6">Pembayaran Tiket</h2>

      <div v-if="order" class="space-y-6">
        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="font-bold text-lg mb-4">Detail Pemesanan</h3>
          <div class="space-y-2">
            <div class="flex justify-between">
              <span class="text-gray-600">Tujuan:</span>
              <span class="font-medium">{{ order.destination }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Tanggal Keberangkatan:</span>
              <span class="font-medium">{{ formatDate(order.departure_date) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Waktu:</span>
              <span class="font-medium">{{ order.departure_time }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Nama Penumpang:</span>
              <span class="font-medium">{{ order.passenger_name }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Jumlah Tiket:</span>
              <span class="font-medium">{{ order.quantity }} tiket</span>
            </div>
            <div class="flex justify-between pt-4 border-t text-lg">
              <span class="font-bold">Total Pembayaran:</span>
              <span class="font-bold text-blue-600">
                Rp {{ formatPrice(order.total_price) }}
              </span>
            </div>
          </div>
        </div>

        <!-- Payment Form -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="font-bold text-lg mb-4">Metode Pembayaran</h3>

          <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ error }}
          </div>

          <form @submit.prevent="submitPayment">
            <div class="mb-6">
              <label class="block text-sm font-medium mb-3">Pilih Metode Pembayaran *</label>
              <div class="space-y-2">
                <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50">
                  <input
                    v-model="paymentMethod"
                    type="radio"
                    value="Bank Transfer"
                    class="mr-3"
                    required
                  />
                  <span>Bank Transfer</span>
                </label>
                <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50">
                  <input
                    v-model="paymentMethod"
                    type="radio"
                    value="E-Wallet"
                    class="mr-3"
                    required
                  />
                  <span>E-Wallet (OVO, GoPay, Dana)</span>
                </label>
                <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50">
                  <input
                    v-model="paymentMethod"
                    type="radio"
                    value="Cash"
                    class="mr-3"
                    required
                  />
                  <span>Cash</span>
                </label>
              </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mb-6">
              <p class="text-sm text-yellow-800">
                <strong>Catatan:</strong> Ini adalah simulasi pembayaran.
                Setelah konfirmasi, status pembayaran akan otomatis menjadi "Lunas"
                dan invoice akan tersedia untuk diunduh.
              </p>
            </div>

            <div class="flex gap-3">
              <button
                type="button"
                @click="$router.back()"
                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded"
              >
                Kembali
              </button>
              <button
                type="submit"
                :disabled="loading"
                class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded disabled:opacity-50"
              >
                {{ loading ? 'Memproses...' : 'Konfirmasi Pembayaran' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ticketOrderService from '@/services/ticketOrder';
import paymentService from '@/services/payment';

export default {
  name: 'PaymentForm',
  data() {
    return {
      order: null,
      paymentMethod: '',
      loading: false,
      error: null
    };
  },
  mounted() {
    this.loadOrder();
  },
  methods: {
    async loadOrder() {
      try {
        const orderId = this.$route.params.orderId;
        const response = await ticketOrderService.getOrderDetail(orderId);
        this.order = response.data;
      } catch (error) {
        this.error = 'Gagal memuat detail pemesanan';
      }
    },
    async submitPayment() {
      this.loading = true;
      this.error = null;

      try {
        const response = await paymentService.confirmPayment(this.order.id, {
          payment_method: this.paymentMethod
        });

        if (response.success) {
          alert('Pembayaran berhasil! Invoice Anda sudah tersedia.');
          this.$router.push({ name: 'OrderHistory' });
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'Pembayaran gagal. Silakan coba lagi.';
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
