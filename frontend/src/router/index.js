import { createRouter, createWebHistory } from 'vue-router';
import authService from '@/services/auth';

import Login from '@/views/Login.vue';
import AdminDashboard from '@/views/AdminDashboard.vue';
import PassengerDashboard from '@/views/PassengerDashboard.vue';

import TravelScheduleList from '@/components/Admin/TravelScheduleList.vue';
import ReportList from '@/components/Admin/ReportList.vue';
import ScheduleOrders from '@/components/Admin/ScheduleOrders.vue';

import TravelList from '@/components/Passenger/TravelList.vue';
import BookingForm from '@/components/Passenger/BookingForm.vue';
import PaymentForm from '@/components/Passenger/PaymentForm.vue';
import OrderHistory from '@/components/Passenger/OrderHistory.vue';

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { guest: true }
  },
  {
    path: '/register',
    redirect: '/login'
  },
  {
    path: '/admin',
    redirect: '/admin/dashboard'
  },
  {
    path: '/admin/dashboard',
    name: 'AdminDashboard',
    component: AdminDashboard,
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      {
        path: 'schedules',
        name: 'AdminSchedules',
        component: TravelScheduleList
      },
      {
        path: 'reports',
        name: 'AdminReports',
        component: ReportList
      },
      {
        path: 'orders/:scheduleId',
        name: 'ScheduleOrders',
        component: ScheduleOrders
      }
    ]
  },
  {
    path: '/passenger',
    redirect: '/passenger/dashboard'
  },
  {
    path: '/passenger/dashboard',
    name: 'PassengerDashboard',
    component: PassengerDashboard,
    meta: { requiresAuth: true, role: 'passenger' },
    children: [
      {
        path: 'travels',
        name: 'TravelList',
        component: TravelList
      },
      {
        path: 'booking/:scheduleId',
        name: 'Booking',
        component: BookingForm
      },
      {
        path: 'payment/:orderId',
        name: 'Payment',
        component: PaymentForm
      },
      {
        path: 'orders',
        name: 'OrderHistory',
        component: OrderHistory
      }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = authService.isAuthenticated();
  const userRole = authService.getUser()?.role;

  if (to.meta.guest && isAuthenticated) {
    if (userRole === 'admin') {
      return next('/admin/dashboard/schedules');
    } else {
      return next('/passenger/dashboard/travels');
    }
  }

  if (to.meta.requiresAuth && !isAuthenticated) {
    return next('/login');
  }

  if (to.meta.role && userRole !== to.meta.role) {
    if (userRole === 'admin') {
      return next('/admin/dashboard/schedules');
    } else {
      return next('/passenger/dashboard/travels');
    }
  }

  next();
});

export default router;
