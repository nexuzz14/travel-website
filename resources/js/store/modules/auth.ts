import authService from '@/services/authService';

export default {
    namespaced: true,

    state: {
        user: authService.getUser(),
        token: authService.getToken(),
        isAuthenticated: !!authService.getToken(),
    },

    getters: {
        user: (state) => state.user,
        isAuthenticated: (state) => state.isAuthenticated,
        isAdmin: (state) => state.user?.role === 'admin',
        isPassenger: (state) => state.user?.role === 'passenger',
    },

    mutations: {
        SET_USER(state, user) {
            state.user = user;
            state.isAuthenticated = !!user;
            if (user) {
                authService.setUser(user);
            } else {
                authService.removeUser();
            }
        },

        SET_TOKEN(state, token) {
            state.token = token;
            if (token) {
                authService.setToken(token);
            } else {
                authService.removeToken();
            }
        },

        LOGOUT(state) {
            state.user = null;
            state.token = null;
            state.isAuthenticated = false;
            authService.removeToken();
            authService.removeUser();
        },
    },

    actions: {
        async login({ commit }, credentials) {
            try {
                const response = await authService.login(credentials);
                commit('SET_USER', response.data.user);
                commit('SET_TOKEN', response.data.token);
                return response;
            } catch (error) {
                commit('LOGOUT');
                throw error;
            }
        },

        async logout({ commit }) {
            try {
                await authService.logout();
            } finally {
                commit('LOGOUT');
            }
        },

        async checkAuth({ commit }) {
            try {
                const response = await authService.getCurrentUser();
                commit('SET_USER', response.data);
                return response.data;
            } catch (error) {
                commit('LOGOUT');
                throw error;
            }
        },
    },
};
