import api from './api';

export default {
  async confirmPayment(orderId, paymentData) {
    const response = await api.post(`/payments/${orderId}/confirm`, paymentData);
    return response.data;
  },

  async downloadInvoice(orderId) {
    const response = await api.get(`/payments/${orderId}/invoice`, {
      responseType: 'blob'
    });

    // Create download link
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', `invoice-${orderId}.pdf`);
    document.body.appendChild(link);
    link.click();
    link.remove();
    window.URL.revokeObjectURL(url);

    return true;
  }
};
