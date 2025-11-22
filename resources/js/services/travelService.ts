import api from './api';

export default {
    async getAllSchedules(params = {}) {
        const response = await api.get('/admin/travel-schedules', { params });
        return response.data;
    },

    async createSchedule(data) {
        const response = await api.post('/admin/travel-schedules', data);
        return response.data;
    },

    async updateSchedule(id, data) {
        const response = await api.put(`/admin/travel-schedules/${id}`, data);
        return response.data;
    },

    async deleteSchedule(id) {
        const response = await api.delete(`/admin/travel-schedules/${id}`);
        return response.data;
    },

    async getSchedulePassengers(id) {
        const response = await api.get(`/admin/travel-schedules/${id}/passengers`);
        return response.data;
    },

    async getReports(params = {}) {
        const response = await api.get('/admin/reports', { params });
        return response.data;
    },

    // Passenger endpoints
    async getAvailableSchedules(params = {}) {
        const response = await api.get('/passenger/travel-schedules', { params });
        return response.data;
    },
};
