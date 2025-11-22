import api from './api';

export default {
    async createOrder(data) {
        const response = await api.post('/passenger/orders', data);
        return response.data;
    },

    async getOrderHistory() {
        const response = await api.get('/passenger/orders');
        return response.data;
    },

    async confirmPayment(orderId, formData) {
        const response = await api.post(`/passenger/orders/${orderId}/payment`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        return response.data;
    },

    async downloadInvoice(orderId) {
        const response = await api.get(`/passenger/orders/${orderId}/invoice`, {
            responseType: 'blob',
        });
        return response.data;
    },
};
