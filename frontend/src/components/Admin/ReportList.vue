<template>
  <div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Laporan Penumpang</h2>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium mb-2">Tanggal Mulai</label>
          <input
            v-model="filters.start_date"
            type="date"
            class="w-full px-3 py-2 border rounded"
          />
        </div>
        <div>
          <label class="block text-sm font-medium mb-2">Tanggal Akhir</label>
          <input
            v-model="filters.end_date"
            type="date"
            class="w-full px-3 py-2 border rounded"
          />
        </div>
        <div class="flex items-end">
          <button
            @click="loadReport"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
          >
            Tampilkan Laporan
          </button>
        </div>
      </div>
    </div>

    <!-- Summary Cards -->
    <div v-if="summary" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">Total Jadwal</p>
        <p class="text-2xl font-bold">{{ summary.total_schedules }}</p>
      </div>
      <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">Total Pemesanan</p>
        <p class="text-2xl font-bold">{{ summary.total_orders }}</p>
      </div>
      <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">Total Penumpang</p>
        <p class="text-2xl font-bold">{{ summary.total_passengers }}</p>
      </div>
      <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">Total Pendapatan</p>
        <p class="text-2xl font-bold text-green-600">
          Rp {{ formatPrice(summary.total_revenue) }}
        </p>
      </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Tujuan
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Tanggal Keberangkatan
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Waktu
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Kuota
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Total Pemesanan
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Total Penumpang
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              Pendapatan
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="item in report" :key="item.schedule_id">
            <td class="px-6 py-4 whitespace-nowrap">{{ item.destination }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(item.departure_date) }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ item.departure_time }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              {{ item.available_quota }} / {{ item.quota }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center">{{ item.total_orders }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-center">{{ item.total_passengers }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              Rp {{ formatPrice(item.total_revenue) }}
            </td>
          </tr>
          <tr v-if="report.length === 0">
            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
              Tidak ada data untuk ditampilkan
            </td>
          </tr>
        </tbody>
      </table>
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
    // Set default date range (current month)
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
        alert('Gagal memuat laporan');
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
