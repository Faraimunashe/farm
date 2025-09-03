<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg">
      <!-- Logo -->
      <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-blue-600 rounded-lg flex items-center justify-center">
            <i class="fa fa-leaf text-white text-lg"></i>
          </div>
          <span class="text-xl font-bold text-gray-900">FarmHub</span>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="mt-6 px-3">
        <div class="space-y-1">
          <a
            v-for="item in navigation"
            :key="item.name"
            :href="item.href"
            class="group flex items-center px-3 py-2 text-sm font-medium border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900"
          >
            <i :class="item.icon + ' mr-3 text-gray-400 group-hover:text-gray-500'"></i>
            {{ item.name }}
          </a>
        </div>
      </nav>

      <!-- User Info -->
      <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
        <div class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
            <span class="text-white font-semibold text-sm">{{ username ? username.charAt(0).toUpperCase() : 'G' }}</span>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">{{ username || 'Guest' }}</p>
            <p class="text-xs text-gray-500 truncate">{{ role?.display_name || 'Farmer' }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="pl-64">
      <!-- Top Header -->
      <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="flex items-center justify-between h-16 px-6">
          <h1 class="text-xl font-semibold text-gray-900">{{ pageTitle }}</h1>
          <div class="flex items-center space-x-4">
            <a href="/farmer/profile" class="text-gray-600 hover:text-gray-900">
              <i class="fa fa-user mr-1"></i>Profile
            </a>
            <form @submit.prevent="logout" class="inline">
              <button type="submit" class="text-gray-600 hover:text-gray-900">
                <i class="fa fa-sign-out-alt mr-1"></i>Logout
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Page Content -->
      <main class="p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    username() {
      return this.$page.props.auth?.user?.name || null
    },
    role() {
      return this.$page.props.auth?.user?.roles?.[0] || null
    },
    pageTitle() {
      const path = this.$page.url
      if (path.startsWith('/farmer/dashboard')) return 'Dashboard'
      if (path.startsWith('/farmer/farms')) return 'My Farms'
      if (path.startsWith('/farmer/loans')) return 'Loans'
      if (path.startsWith('/farmer/insurance')) return 'Insurance'
      if (path.startsWith('/farmer/transactions')) return 'Transactions'
      if (path.startsWith('/farmer/plans')) return 'Plans'
      if (path.startsWith('/farmer/reports')) return 'Reports'
      if (path.startsWith('/farmer/profile')) return 'Profile & Settings'
      return 'Dashboard'
    },
    navigation() {
      return [
        { name: 'Dashboard', href: '/farmer/dashboard', icon: 'fa fa-tachometer-alt' },
        { name: 'My Farms', href: '/farmer/farms', icon: 'fa fa-leaf' },
        { name: 'Loans', href: '/farmer/loans', icon: 'fa fa-file-invoice-dollar' },
        { name: 'Insurance', href: '/farmer/insurance', icon: 'fa fa-shield-alt' },
        { name: 'Transactions', href: '/farmer/transactions', icon: 'fa fa-exchange-alt' },
        { name: 'Plans', href: '/farmer/plans', icon: 'fa fa-calendar-alt' },
        { name: 'Reports', href: '/farmer/reports', icon: 'fa fa-chart-bar' },
        { name: 'Profile', href: '/farmer/profile', icon: 'fa fa-user-cog' }
      ]
    }
  },
  methods: {
    logout() {
      // Call the logout route that will call the destroy function in LoginController
      window.location.href = '/logout'
    }
  }
}
</script>
