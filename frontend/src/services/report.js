import api from './api';

export default {
  async getPassengerReport(filters = {}) {
    const params = new URLSearchParams();
    if (filters.date) params.append('date', filters.date);
    if (filters.start_date) params.append('start_date', filters.start_date);
    if (filters.end_date) params.append('end_date', filters.end_date);

    const response = await api.get(`/admin/reports/passengers?${params.toString()}`);
    return response.data;
  },

  async getReportByPeriod(startDate, endDate) {
    const response = await api.get('/admin/reports/by-period', {
      params: { start_date: startDate, end_date: endDate }
    });
    return response.data;
  }
};
