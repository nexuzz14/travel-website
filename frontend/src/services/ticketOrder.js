import api from './api';

export default {
  async createOrder(orderData) {
    const response = await api.post('/ticket-orders', orderData);
    return response.data;
  },

  async getOrders() {
    const response = await api.get('/ticket-orders');
    return response.data;
  },

  async getOrderDetail(id) {
    const response = await api.get(`/ticket-orders/${id}`);
    return response.data;
  },

  async cancelOrder(id) {
    const response = await api.post(`/ticket-orders/${id}/cancel`);
    return response.data;
  }
};
