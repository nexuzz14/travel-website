import api from './api';

export default {

  async getAvailableSchedules(filters = {}) {
    const params = new URLSearchParams();
    if (filters.destination) params.append('destination', filters.destination);
    if (filters.date) params.append('date', filters.date);
    if (filters.available) params.append('available', 'true');

    const response = await api.get(`/travel-schedules?${params.toString()}`);
    return response.data;
  },

  async getScheduleDetail(id) {
    const response = await api.get(`/travel-schedules/${id}`);
    return response.data;
  },

  async getAllSchedules() {
    const response = await api.get('/admin/travel-schedules');
    return response.data;
  },

  async createSchedule(scheduleData) {
    const response = await api.post('/admin/travel-schedules', scheduleData);
    return response.data;
  },

  async updateSchedule(id, scheduleData) {
    const response = await api.put(`/admin/travel-schedules/${id}`, scheduleData);
    return response.data;
  },

  async deleteSchedule(id) {
    const response = await api.delete(`/admin/travel-schedules/${id}`);
    return response.data;
  },

  async getScheduleOrders(id) {
    const response = await api.get(`/admin/travel-schedules/${id}/orders`);
    return response.data;
  }
};
