<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-blue-600 text-white shadow-lg">
      <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
          <div class="flex items-center space-x-8">
            <h1 class="text-xl font-bold">🚗 Travel Booking Admin</h1>
            <div class="hidden md:flex space-x-4">
              <router-link
                to="/admin/dashboard/schedules"
                class="px-4 py-2 rounded hover:bg-blue-700 transition"
                active-class="bg-blue-700"
              >
                Jadwal Travel
              </router-link>
              <router-link
                to="/admin/dashboard/reports"
                class="px-4 py-2 rounded hover:bg-blue-700 transition"
                active-class="bg-blue-700"
              >
                Laporan
              </router-link>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <span class="text-sm">{{ user?.name }}</span>
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
  </div>
</template>

<script>
import authService from '@/services/auth';

export default {
  name: 'AdminDashboard',
  data() {
    return {
      user: null
    };
  },
  mounted() {
    this.user = authService.getUser();

    // Redirect jika bukan admin
    if (!authService.isAdmin()) {
      this.$router.push('/login');
    }

    // Default redirect ke schedules
    if (this.$route.path === '/admin/dashboard') {
      this.$router.push('/admin/dashboard/schedules');
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
