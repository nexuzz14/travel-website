<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 px-4 py-8">
    <div class="container mx-auto max-w-7xl">
      <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2 flex items-center gap-3">
          <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          Laporan Penumpang
        </h1>
        <p class="text-gray-600">Analisis data penumpang dan pendapatan</p>
      </div>

      <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border-t-4 border-blue-600">
        <div class="flex items-center gap-2 mb-4">
          <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
          </svg>
          <h3 class="text-lg font-bold text-gray-800">Filter Laporan</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">📅 Tanggal Mulai</label>
            <input
              v-model="filters.start_date"
              type="date"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
            />
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">📅 Tanggal Akhir</label>
            <input
              v-model="filters.end_date"
              type="date"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
            />
          </div>
          <div class="flex items-end">
            <button
              @click="loadReport"
              class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              Tampilkan Laporan
            </button>
          </div>
        </div>
      </div>

      <div v-if="summary" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 shadow-xl text-white transform hover:scale-105 transition-transform duration-200">
          <div class="flex items-center justify-between mb-3">
            <div>
              <p class="text-blue-100 text-sm font-semibold mb-1">Total Jadwal</p>
              <p class="text-4xl font-bold">{{ summary.total_schedules }}</p>
            </div>
            <div class="p-4 bg-white bg-opacity-20 rounded-xl">
              <svg class="w-8 h-8" fill="none" stroke="blue" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
          </div>
          <div class="h-1 bg-white bg-opacity-30 rounded-full overflow-hidden">
            <div class="h-full bg-white rounded-full" style="width: 100%"></div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl p-6 shadow-xl text-white transform hover:scale-105 transition-transform duration-200">
          <div class="flex items-center justify-between mb-3">
            <div>
              <p class="text-purple-100 text-sm font-semibold mb-1">Total Pemesanan</p>
              <p class="text-4xl font-bold">{{ summary.total_orders }}</p>
            </div>
            <div class="p-4 bg-white bg-opacity-20 rounded-xl">
              <svg class="w-8 h-8" fill="none" stroke="purple" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
          </div>
          <div class="h-1 bg-white bg-opacity-30 rounded-full overflow-hidden">
            <div class="h-full bg-white rounded-full" style="width: 85%"></div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 shadow-xl text-white transform hover:scale-105 transition-transform duration-200">
          <div class="flex items-center justify-between mb-3">
            <div>
              <p class="text-green-100 text-sm font-semibold mb-1">Total Penumpang</p>
              <p class="text-4xl font-bold">{{ summary.total_passengers }}</p>
            </div>
            <div class="p-4 bg-white bg-opacity-20 rounded-xl">
              <svg class="w-8 h-8" fill="none" stroke="green" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
              </svg>
            </div>
          </div>
          <div class="h-1 bg-white bg-opacity-30 rounded-full overflow-hidden">
            <div class="h-full bg-white rounded-full" style="width: 70%"></div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl p-6 shadow-xl text-white transform hover:scale-105 transition-transform duration-200">
          <div class="flex items-center justify-between mb-3">
            <div>
              <p class="text-orange-100 text-sm font-semibold mb-1">Total Pendapatan</p>
              <p class="text-3xl font-bold">Rp {{ formatPrice(summary.total_revenue) }}</p>
            </div>
            <div class="p-4 bg-white bg-opacity-20 rounded-xl">
              <svg class="w-8 h-8" fill="none" stroke="red" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
          <div class="h-1 bg-white bg-opacity-30 rounded-full overflow-hidden">
            <div class="h-full bg-white rounded-full" style="width: 90%"></div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Detail Laporan per Jadwal
          </h3>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                  🎯 Tujuan
                </th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                  📅 Tanggal
                </th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                  ⏰ Waktu
                </th>
                <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                  👥 Kuota
                </th>
                <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                  📋 Pemesanan
                </th>
                <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                  🎫 Penumpang
                </th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                  💰 Pendapatan
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
              <tr v-for="item in report" :key="item.schedule_id"
                  class="hover:bg-blue-50 transition-colors duration-150">
                <td class="px-6 py-4">
                  <span class="font-semibold text-gray-800">{{ item.destination }}</span>
                </td>
                <td class="px-6 py-4 text-gray-700">
                  {{ formatDate(item.departure_date) }}
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                    {{ item.departure_time }}
                  </span>
                </td>
                <td class="px-6 py-4 text-center">
                  <div class="inline-flex flex-col">
                    <span class="text-sm font-bold text-gray-800">
                      {{ item.available_quota }} / {{ item.quota }}
                    </span>
                    <span class="text-xs text-gray-500">
                      ({{ Math.round((item.quota - item.available_quota) / item.quota * 100) }}% terisi)
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 text-center">
                  <span class="inline-flex items-center justify-center w-10 h-10 bg-purple-100 text-purple-700 rounded-full font-bold">
                    {{ item.total_orders }}
                  </span>
                </td>
                <td class="px-6 py-4 text-center">
                  <span class="inline-flex items-center justify-center w-10 h-10 bg-green-100 text-green-700 rounded-full font-bold">
                    {{ item.total_passengers }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <span class="font-bold text-gray-800 text-lg">
                    Rp {{ formatPrice(item.total_revenue) }}
                  </span>
                </td>
              </tr>
              <tr v-if="report.length === 0">
                <td colspan="7" class="px-6 py-16 text-center">
                  <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                  </div>
                  <p class="text-gray-500 font-medium text-lg">Tidak ada data untuk ditampilkan</p>
                  <p class="text-gray-400 text-sm mt-2">Coba ubah filter tanggal untuk melihat data lainnya</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import reportService from '@/services/report';

export default {
  name: 'ReportList',
  data() {
    return {
      report: [],
      summary: null,
      filters: {
        start_date: '',
        end_date: ''
      }
    };
  },
  mounted() {
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);

    this.filters.start_date = firstDay.toISOString().split('T')[0];
    this.filters.end_date = lastDay.toISOString().split('T')[0];

    this.loadReport();
  },
  methods: {
    async loadReport() {
      try {
        const response = await reportService.getPassengerReport(this.filters);
        this.report = response.data;
        this.summary = response.summary;
      } catch (error) {
        console.error('Error loading report:', error);
        alert('❌ Gagal memuat laporan');
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price || 0);
    }
  }
};
</script>
