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
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
          </div>
          Pembayaran Tiket
        </h1>
        <p class="text-gray-600">Selesaikan pembayaran untuk mengkonfirmasi pemesanan Anda</p>
      </div>

      <div v-if="order" class="space-y-6">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
          <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 text-white">
            <h3 class="text-2xl font-bold flex items-center gap-2">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              Detail Pemesanan
            </h3>
          </div>

          <div class="p-8">
            <div class="space-y-4">
              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <span class="text-gray-600 font-medium">🎯 Tujuan</span>
                <span class="font-bold text-gray-800">{{ order.travel_schedule.destination }}</span>
              </div>
              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <span class="text-gray-600 font-medium">📅 Tanggal Keberangkatan</span>
                <span class="font-bold text-gray-800">{{ formatDate(order.travel_schedule.departure_date) }}</span>
              </div>
              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <span class="text-gray-600 font-medium">⏰ Waktu</span>
                <span class="font-bold text-gray-800">{{ order.travel_schedule.departure_time }} WIB</span>
              </div>
              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <span class="text-gray-600 font-medium">👤 Nama Penumpang</span>
                <span class="font-bold text-gray-800">{{ order.passenger_name }}</span>
              </div>
              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <span class="text-gray-600 font-medium">🎫 Jumlah Tiket</span>
                <span class="font-bold text-gray-800">{{ order.quantity }} tiket</span>
              </div>

              <div class="pt-4 border-t-2 border-gray-200">
                <div class="flex items-center justify-between p-5 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border-2 border-green-200">
                  <span class="text-lg font-bold text-gray-800">💰 Total Pembayaran</span>
                  <span class="text-3xl font-bold text-green-600">
                    Rp {{ formatPrice(order.total_price) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
          <h3 class="text-2xl font-bold mb-6 flex items-center gap-2 text-gray-800">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Metode Pembayaran
          </h3>

          <div v-if="error" class="bg-red-50 border-2 border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6 flex items-start gap-3">
            <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
              <p class="font-semibold mb-1">Terjadi Kesalahan</p>
              <p class="text-sm">{{ error }}</p>
            </div>
          </div>

          <form @submit.prevent="submitPayment">
            <div class="mb-8">
              <label class="block text-sm font-semibold text-gray-700 mb-4">Pilih Metode Pembayaran *</label>
              <div class="space-y-3">

                <label class="flex items-center p-5 border-2 rounded-xl cursor-pointer hover:bg-green-50 hover:border-green-500 transition-all group"
                       :class="paymentMethod === 'Bank Transfer' ? 'bg-green-50 border-green-500 shadow-lg' : 'border-gray-200'">
                  <input
                    v-model="paymentMethod"
                    type="radio"
                    value="Bank Transfer"
                    class="w-5 h-5 text-green-600 focus:ring-2 focus:ring-green-500"
                    required
                  />
                  <div class="ml-4 flex items-center gap-4 flex-1">
                    <div class="bg-blue-100 p-3 rounded-lg">
                      <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                      </svg>
                    </div>
                    <div>
                      <p class="font-bold text-gray-800">Bank Transfer</p>
                      <p class="text-sm text-gray-500">BCA, Mandiri, BNI, BRI</p>
                    </div>
                  </div>
                </label>

                <label class="flex items-center p-5 border-2 rounded-xl cursor-pointer hover:bg-green-50 hover:border-green-500 transition-all group"
                       :class="paymentMethod === 'E-Wallet' ? 'bg-green-50 border-green-500 shadow-lg' : 'border-gray-200'">
                  <input
                    v-model="paymentMethod"
                    type="radio"
                    value="E-Wallet"
                    class="w-5 h-5 text-green-600 focus:ring-2 focus:ring-green-500"
                    required
                  />
                  <div class="ml-4 flex items-center gap-4 flex-1">
                    <div class="bg-purple-100 p-3 rounded-lg">
                      <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                      </svg>
                    </div>
                    <div>
                      <p class="font-bold text-gray-800">E-Wallet</p>
                      <p class="text-sm text-gray-500">OVO, GoPay, Dana, ShopeePay</p>
                    </div>
                  </div>
                </label>

                <label class="flex items-center p-5 border-2 rounded-xl cursor-pointer hover:bg-green-50 hover:border-green-500 transition-all group"
                       :class="paymentMethod === 'Cash' ? 'bg-green-50 border-green-500 shadow-lg' : 'border-gray-200'">
                  <input
                    v-model="paymentMethod"
                    type="radio"
                    value="Cash"
                    class="w-5 h-5 text-green-600 focus:ring-2 focus:ring-green-500"
                    required
                  />
                  <div class="ml-4 flex items-center gap-4 flex-1">
                    <div class="bg-green-100 p-3 rounded-lg">
                      <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                      </svg>
                    </div>
                    <div>
                      <p class="font-bold text-gray-800">Cash</p>
                      <p class="text-sm text-gray-500">Bayar langsung di counter</p>
                    </div>
                  </div>
                </label>
              </div>
            </div>

            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-2xl p-5 mb-8 flex items-start gap-3">
              <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div>
                <p class="font-bold text-yellow-800 mb-1">💡 Informasi Pembayaran</p>
                <p class="text-sm text-yellow-700">
                  Ini adalah simulasi pembayaran. Setelah konfirmasi, status pembayaran akan otomatis menjadi "Lunas" dan invoice akan tersedia untuk diunduh.
                </p>
              </div>
            </div>

            <div class="flex gap-4">
              <button
                type="button"
                @click="$router.back()"
                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-4 rounded-xl font-semibold transition-all flex items-center justify-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Kembali
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
          alert('✅ Pembayaran berhasil! Invoice Anda sudah tersedia.');
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
