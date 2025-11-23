<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">

    <nav class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white shadow-2xl sticky top-0 z-50 backdrop-blur-lg bg-opacity-95">
      <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
          <div class="flex items-center space-x-8">
            <div class="flex items-center space-x-3">
              <div class="p-2 rounded-xl backdrop-blur-sm shadow-xl">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <div>
                <h1 class="text-xl font-bold">Travel Booking</h1>
                <p class="text-xs text-blue-100">Admin Dashboard</p>
              </div>
            </div>

            <div class="hidden md:flex space-x-2">
              <router-link
                to="/admin/dashboard/schedules"
                class="group px-5 py-2.5 rounded-xl hover:bg-white hover:bg-opacity-20 hover:text-black transition-all duration-200 flex items-center gap-2 font-medium"
                active-class="shadow-lg"
              >
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Jadwal Travel
              </router-link>
              <router-link
                to="/admin/dashboard/reports"
                class="group px-5 py-2.5 rounded-xl hover:bg-white hover:bg-opacity-20 hover:text-black transition-all duration-200 flex items-center gap-2 font-medium"
                active-class="shadow-lg"
              >
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan
              </router-link>
            </div>
          </div>

          <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center gap-3 shadow-lg backdrop-blur-sm px-4 py-2 rounded-xl">
              <div class="shadow-lg p-2 rounded-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
              <div class="text-left">
                <p class="text-sm font-semibold">{{ user?.name || 'Admin' }}</p>
                <p class="text-xs text-blue-100">Administrator</p>
              </div>
            </div>

            <button
              @click="handleLogout"
              class="group bg-red-500 hover:bg-red-600 px-5 py-2.5 rounded-xl transition-all duration-200 font-semibold shadow-lg hover:shadow-xl flex items-center gap-2"
            >
              <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
              </svg>
              <span class="hidden sm:inline">Logout</span>
            </button>

            <!-- Mobile Menu Toggle -->
            <button
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="md:hidden p-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>
          </div>
        </div>

        <div
          v-if="mobileMenuOpen"
          class="md:hidden pb-4 space-y-2 border-t border-white border-opacity-20 pt-4"
        >
          <router-link
            to="/admin/dashboard/schedules"
            @click="mobileMenuOpen = false"
            class="block px-4 py-3 rounded-xl hover:bg-white hover:bg-opacity-20 transition-all flex items-center gap-3"
            active-class="bg-white bg-opacity-20"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Jadwal Travel
          </router-link>
          <router-link
            to="/admin/dashboard/reports"
            @click="mobileMenuOpen = false"
            class="block px-4 py-3 rounded-xl hover:bg-white hover:bg-opacity-20 transition-all flex items-center gap-3"
            active-class="bg-white bg-opacity-20"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Laporan
          </router-link>
        </div>
      </div>
    </nav>

    <main class="py-8 transition-all duration-300">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <footer class="bg-white bg-opacity-80 backdrop-blur-sm border-t border-gray-200 mt-12">
      <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
          <div class="text-gray-600 text-sm">
            <p class="font-semibold">© 2025 Muhammad Nabil Cahya Firdaus</p>
            <p class="text-xs text-gray-500">Travel Booking System</p>
          </div>
          <div class="flex items-center gap-4 text-sm text-gray-600">
            <a href="#" class="hover:text-blue-600 transition-colors flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Help
            </a>
            <a href="#" class="hover:text-blue-600 transition-colors flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              Settings
            </a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
import authService from '@/services/auth';

export default {
  name: 'AdminDashboard',
  data() {
    return {
      user: null,
      mobileMenuOpen: false
    };
  },
  mounted() {
    this.user = authService.getUser();

    if (!authService.isAdmin()) {
      this.$router.push('/login');
    }

    if (this.$route.path === '/admin/dashboard') {
      this.$router.push('/admin/dashboard/schedules');
    }
  },
  methods: {
    async handleLogout() {
      if (!confirm('🚪 Yakin ingin keluar dari dashboard?')) return;

      try {
        await authService.logout();
        this.$router.push('/login');
      } catch (error) {
        console.error('Logout error:', error);
        alert('❌ Gagal logout. Silakan coba lagi.');
      }
    }
  }
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from {
  opacity: 0;
  transform: translateY(10px);
}

.fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

html {
  scroll-behavior: smooth;
}
</style>
