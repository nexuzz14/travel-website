<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 px-4 py-8">
    <div class="container mx-auto max-w-7xl">

      <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
              Manajemen Jadwal Travel
            </h1>
            <p class="text-gray-600">Kelola jadwal perjalanan dengan mudah</p>
          </div>
          <button
            @click="showForm = true"
            class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 justify-center"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Jadwal
          </button>
        </div>
      </div>

      <div v-if="showForm" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
          <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 rounded-t-2xl">
            <h3 class="text-2xl font-bold text-white">
              {{ editingSchedule ? '✏️ Edit Jadwal' : '➕ Tambah Jadwal Baru' }}
            </h3>
          </div>

          <form @submit.prevent="saveSchedule" class="p-6">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  🎯 Tujuan *
                </label>
                <input
                  v-model="form.destination"
                  type="text"
                  required
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                  placeholder="Jakarta - Bandung"
                />
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    📅 Tanggal *
                  </label>
                  <input
                    v-model="form.departure_date"
                    type="date"
                    required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    ⏰ Waktu *
                  </label>
                  <input
                    v-model="form.departure_time"
                    type="time"
                    required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                  />
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    👥 Kuota *
                  </label>
                  <input
                    v-model.number="form.quota"
                    type="number"
                    min="1"
                    required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    💰 Harga (Rp) *
                  </label>
                  <input
                    v-model.number="form.price"
                    type="number"
                    min="0"
                    required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                  />
                </div>
              </div>
            </div>

            <div class="flex gap-3 mt-6">
              <button
                type="button"
                @click="closeForm"
                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-lg font-semibold transition-all"
              >
                Batal
              </button>
              <button
                type="submit"
                class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-4 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all"
              >
                💾 Simpan
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
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
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                  👥 Kuota
                </th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                  💰 Harga
                </th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                  ⚙️ Aksi
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
              <tr v-for="schedule in schedules" :key="schedule.id"
                  class="hover:bg-blue-50 transition-colors duration-150">
                <td class="px-6 py-4">
                  <div class="font-semibold text-gray-800">{{ schedule.destination }}</div>
                </td>
                <td class="px-6 py-4 text-gray-700">
                  {{ formatDate(schedule.departure_date) }}
                </td>
                <td class="px-6 py-4 text-gray-700">
                  {{ schedule.departure_time }}
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold"
                        :class="getQuotaClass(schedule.available_quota, schedule.quota)">
                    {{ schedule.available_quota }} / {{ schedule.quota }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <span class="font-semibold text-gray-800">
                    Rp {{ formatPrice(schedule.price) }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex gap-2">
                    <button
                      @click="viewOrders(schedule.id)"
                      class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-sm font-medium transition-all"
                      title="Lihat Penumpang"
                    >
                      👁️ Penumpang
                    </button>
                    <button
                      @click="editSchedule(schedule)"
                      class="px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg text-sm font-medium transition-all"
                      title="Edit"
                    >
                      ✏️
                    </button>
                    <button
                      @click="deleteSchedule(schedule.id)"
                      class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm font-medium transition-all"
                      title="Hapus"
                    >
                      🗑️
                    </button>
                  </div>
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
import travelScheduleService from '@/services/travelSchedule';

export default {
  name: 'TravelScheduleList',
  data() {
    return {
      schedules: [],
      showForm: false,
      editingSchedule: null,
      form: {
        destination: '',
        departure_date: '',
        departure_time: '',
        quota: 20,
        price: 0
      }
    };
  },
  mounted() {
    this.loadSchedules();
  },
  methods: {
    async loadSchedules() {
      try {
        const response = await travelScheduleService.getAllSchedules();
        this.schedules = response.data;
      } catch (error) {
        console.error('Error loading schedules:', error);
      }
    },
    async saveSchedule() {
      try {
        if (this.editingSchedule) {
          await travelScheduleService.updateSchedule(this.editingSchedule.id, this.form);
          alert('✅ Jadwal berhasil diupdate');
        } else {
          await travelScheduleService.createSchedule(this.form);
          alert('✅ Jadwal berhasil ditambahkan');
        }
        this.closeForm();
        this.loadSchedules();
      } catch (error) {
        alert('❌ ' + (error.response?.data?.message || 'Gagal menyimpan jadwal'));
      }
    },
    editSchedule(schedule) {
      this.editingSchedule = schedule;
      this.form = {
        destination: schedule.destination,
        departure_date: schedule.departure_date,
        departure_time: schedule.departure_time,
        quota: schedule.quota,
        price: schedule.price
      };
      this.showForm = true;
    },
    async deleteSchedule(id) {
      if (!confirm('⚠️ Yakin ingin menghapus jadwal ini?')) return;

      try {
        await travelScheduleService.deleteSchedule(id);
        alert('✅ Jadwal berhasil dihapus');
        this.loadSchedules();
      } catch (error) {
        alert('❌ ' + (error.response?.data?.message || 'Gagal menghapus jadwal'));
      }
    },
    viewOrders(scheduleId) {
      this.$router.push({ name: 'ScheduleOrders', params: { scheduleId } });
    },
    closeForm() {
      this.showForm = false;
      this.editingSchedule = null;
      this.form = {
        destination: '',
        departure_date: '',
        departure_time: '',
        quota: 20,
        price: 0
      };
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      });
    },
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price);
    },
    getQuotaClass(available, total) {
      const percentage = (available / total) * 100;
      if (percentage > 50) return 'bg-green-100 text-green-700';
      if (percentage > 20) return 'bg-yellow-100 text-yellow-700';
      return 'bg-red-100 text-red-700';
    }
  }
};
</script>
