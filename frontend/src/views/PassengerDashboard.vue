<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-blue-600 text-white shadow-lg">
      <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
          <div class="flex items-center space-x-8">
            <h1 class="text-xl font-bold">🚗 Travel Booking</h1>
            <div class="hidden md:flex space-x-4">
              <router-link
                to="/passenger/dashboard/travels"
                class="px-4 py-2 rounded hover:bg-blue-700 transition"
                active-class="bg-blue-700"
              >
                Jadwal Travel
              </router-link>
              <router-link
                to="/passenger/dashboard/orders"
                class="px-4 py-2 rounded hover:bg-blue-700 transition"
                active-class="bg-blue-700"
              >
                Riwayat Pemesanan
              </router-link>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <span class="text-sm">👤 {{ user?.name }}</span>
            <button
              @click="handleLogout"
              class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded transition"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="py-8">
      <router-view />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-12">
      <div class="container mx-auto px-4 text-center">
        <p class="text-sm">© 2024 Travel Booking System. All rights reserved.</p>
        <p class="text-xs text-gray-400 mt-2">Hubungi kami: (021) 1234-5678 | info@travelbooking.com</p>
      </div>
    </footer>
  </div>
</template>

<script>
import authService from '@/services/auth';

export default {
  name: 'PassengerDashboard',
  data() {
    return {
      user: null
    };
  },
  mounted() {
    this.user = authService.getUser();

    // Redirect jika bukan passenger
    if (!authService.isPassenger()) {
      this.$router.push('/login');
    }

    // Default redirect ke travels
    if (this.$route.path === '/passenger/dashboard') {
      this.$router.push('/passenger/dashboard/travels');
    }
  },
  methods: {
    async handleLogout() {
      try {
        await authService.logout();
        this.$router.push('/login');
      } catch (error) {
        console.error('Logout error:', error);
      }
    }
  }
};
</script>
