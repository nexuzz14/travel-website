<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Manajemen Jadwal Travel</h2>
      <button
        @click="showForm = true"
        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
      >
        + Tambah Jadwal
      </button>
    </div>

    <div v-if="showForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4">
          {{ editingSchedule ? 'Edit Jadwal' : 'Tambah Jadwal Baru' }}
        </h3>

        <form @submit.prevent="saveSchedule">
          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Tujuan *</label>
            <input
              v-model="form.destination"
              type="text"
              required
              class="w-full px-3 py-2 border rounded"
              placeholder="Jakarta - Bandung"
            />
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Tanggal Keberangkatan *</label>
            <input
              v-model="form.departure_date"
              type="date"
              required
              class="w-full px-3 py-2 border rounded"
            />
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Waktu Keberangkatan *</label>
            <input
              v-model="form.departure_time"
              type="time"
              required
              class="w-full px-3 py-2 border rounded"
            />
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Kuota *</label>
            <input
              v-model.number="form.quota"
              type="number"
              min="1"
              required
              class="w-full px-3 py-2 border rounded"
            />
          </div>

          <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Harga (Rp) *</label>
            <input
              v-model.number="form.price"
              type="number"
              min="0"
              required
              class="w-full px-3 py-2 border rounded"
            />
          </div>

          <div class="flex gap-3">
            <button
              type="button"
              @click="closeForm"
              class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded"
            >
              Batal
            </button>
            <button
              type="submit"
              class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
            >
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tujuan</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kuota</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="schedule in schedules" :key="schedule.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ schedule.destination }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(schedule.departure_date) }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ schedule.departure_time }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              {{ schedule.available_quota }} / {{ schedule.quota }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">Rp {{ formatPrice(schedule.price) }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button
                @click="viewOrders(schedule.id)"
                class="text-blue-600 hover:text-blue-800 mr-3"
              >
                Lihat Penumpang
              </button>
              <button
                @click="editSchedule(schedule)"
                class="text-green-600 hover:text-green-800 mr-3"
              >
                Edit
              </button>
              <button
                @click="deleteSchedule(schedule.id)"
                class="text-red-600 hover:text-red-800"
              >
                Hapus
              </button>
            </td>
          </tr>
        </tbody>
      </table>
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
          alert('Jadwal berhasil diupdate');
        } else {
          await travelScheduleService.createSchedule(this.form);
          alert('Jadwal berhasil ditambahkan');
        }
        this.closeForm();
        this.loadSchedules();
      } catch (error) {
        alert(error.response?.data?.message || 'Gagal menyimpan jadwal');
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
      if (!confirm('Yakin ingin menghapus jadwal ini?')) return;

      try {
        await travelScheduleService.deleteSchedule(id);
        alert('Jadwal berhasil dihapus');
        this.loadSchedules();
      } catch (error) {
        alert(error.response?.data?.message || 'Gagal menghapus jadwal');
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
      return new Date(date).toLocaleDateString('id-ID');
    },
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price);
    }
  }
};
</script>
