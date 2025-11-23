<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 py-12 px-4">
    <div class="max-w-md w-full">
      <!-- Logo/Brand -->
      <div class="text-center mb-8">
        <div class="inline-block bg-blue-600 text-white rounded-full p-4 mb-4">
          <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
            />
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Travel Booking</h1>
        <p class="text-gray-600 mt-2">Pesan tiket travel dengan mudah</p>
      </div>

      <div class="bg-white rounded-lg shadow-xl p-8">
        <div class="flex mb-6 bg-gray-100 rounded-lg p-1">
          <button
            @click="activeTab = 'login'"
            :class="activeTab === 'login' ? 'bg-white shadow text-gray-900' : 'text-gray-600'"
            class="flex-1 py-2 px-4 rounded-md font-medium transition duration-200"
          >
            Login
          </button>
          <button
            @click="activeTab = 'register'"
            :class="activeTab === 'register' ? 'bg-white shadow text-gray-900' : 'text-gray-600'"
            class="flex-1 py-2 px-4 rounded-md font-medium transition duration-200"
          >
            Daftar
          </button>
        </div>

        <div
          v-if="error"
          class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm"
        >
          {{ error }}
        </div>
        <div
          v-if="success"
          class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm"
        >
          {{ success }}
        </div>

        <form v-if="activeTab === 'login'" @submit.prevent="handleLogin">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input
              v-model="loginForm.email"
              type="email"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="email@example.com"
            />
          </div>

          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input
              v-model="loginForm.password"
              type="password"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="••••••••"
            />
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ loading ? 'Memproses...' : 'Login' }}
          </button>
        </form>

        <form v-else @submit.prevent="handleRegister">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
            <input
              v-model="registerForm.name"
              type="text"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Muhammad Nabil Cahya Firdaus"
            />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input
              v-model="registerForm.email"
              type="email"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="email@example.com"
            />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Telepon</label>
            <input
              v-model="registerForm.phone"
              type="tel"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="081234567890"
            />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input
              v-model="registerForm.password"
              type="password"
              required
              minlength="8"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Minimal 8 karakter"
            />
          </div>

          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password</label>
            <input
              v-model="registerForm.password_confirmation"
              type="password"
              required
              minlength="8"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Masukkan password lagi"
            />
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ loading ? 'Memproses...' : 'Daftar Sekarang' }}
          </button>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-200">
          <p class="text-xs text-gray-500 text-center mb-2">Demo Credentials untuk Testing:</p>
          <div class="text-xs text-gray-600 space-y-1">
            <div class="flex justify-between px-4">
              <span class="font-medium">Admin:</span>
              <span>admin@travel.com / password</span>
            </div>
            <div class="flex justify-between px-4">
              <span class="font-medium">Penumpang:</span>
              <span>passenger@travel.com / password</span>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-6 text-sm text-gray-600">
        <p>© 2025 Muhammad Nabil Cahya Firdaus</p>
      </div>
    </div>
  </div>
</template>

<script>
import authService from '@/services/auth';

export default {
  name: 'LoginForm',
  data() {
    return {
      activeTab: 'login',
      loginForm: {
        email: '',
        password: '',
      },
      registerForm: {
        name: '',
        email: '',
        phone: '',
        password: '',
        password_confirmation: '',
      },
      loading: false,
      error: null,
      success: null,
    };
  },
  methods: {
    async handleLogin() {
      this.loading = true;
      this.error = null;
      this.success = null;

      try {
        const res = await authService.login({
          email: this.loginForm.email,
          password: this.loginForm.password,
        });

        const user = res.data?.user || res.user || null;

        if (user?.role === 'admin') {
          this.$router.push('/admin/dashboard/schedules');
        } else {
          this.$router.push('/passenger/dashboard/travels');
        }
      } catch (err) {
        this.error =
          err.response?.data?.message || 'Login gagal. Silakan coba lagi.';
      } finally {
        this.loading = false;
      }
    },

    async handleRegister() {
      this.loading = true;
      this.error = null;
      this.success = null;

      try {
        const res = await authService.register({
          name: this.registerForm.name,
          email: this.registerForm.email,
          phone: this.registerForm.phone,
          password: this.registerForm.password,
          password_confirmation: this.registerForm.password_confirmation,
        });

        this.success =
          res.data?.message || 'Registrasi berhasil. Silakan login.';
        this.activeTab = 'login';
      } catch (err) {
        this.error =
          err.response?.data?.message || 'Registrasi gagal. Silakan coba lagi.';
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>
