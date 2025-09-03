<template>
  <Head title="Dashboard" />

  <div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-lg p-6 text-white">
      <h1 class="text-2xl font-bold mb-2">Welcome back, {{ farms?.[0]?.user?.name || 'Farmer' }}!</h1>
      <p class="text-green-100">Manage your farms, track finances, and grow your agricultural business.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-lg">
            <i class="fa fa-leaf text-2xl text-green-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Farms</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats?.totalFarms || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <i class="fa fa-ruler-combined text-2xl text-blue-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Farm Size</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats?.totalFarmSize || 0 }} ha</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <i class="fa fa-dollar-sign text-2xl text-yellow-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Active Loans</p>
            <p class="text-2xl font-bold text-gray-900">${{ (stats?.totalLoanAmount || 0).toLocaleString() }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-purple-100 rounded-lg">
            <i class="fa fa-shield-alt text-2xl text-purple-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Insurance Coverage</p>
            <p class="text-2xl font-bold text-gray-900">${{ (stats?.totalInsuranceValue || 0).toLocaleString() }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Recent Transactions -->
      <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
        </div>
        <div class="p-6">
          <div v-if="!recentTransactions || recentTransactions.length === 0" class="text-center py-8">
            <span class="text-gray-500">No recent transactions</span>
          </div>
          <div v-else class="space-y-4">
            <div v-for="transaction in recentTransactions" :key="transaction.id" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
              <div>
                <p class="font-medium text-gray-900">{{ transaction.farm.name }}</p>
                <p class="text-sm text-gray-600">{{ transaction.description }}</p>
                <p class="text-xs text-gray-500">{{ new Date(transaction.created_at).toLocaleDateString() }}</p>
              </div>
              <div class="text-right">
                <p class="font-semibold text-gray-900">${{ transaction.amount.toLocaleString() }}</p>
                <span :class="[
                  'text-xs px-2 py-1 rounded-full',
                  transaction.type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                ]">
                  {{ transaction.type }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Active Loans -->
      <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Active Loans</h3>
        </div>
        <div class="p-6">
          <div v-if="!activeLoans || activeLoans.length === 0" class="text-center py-8">
            <span class="text-gray-500">No active loans</span>
          </div>
          <div v-else class="space-y-4">
            <div v-for="loan in activeLoans" :key="loan.id" class="p-4 bg-gray-50 rounded-lg">
              <div class="flex justify-between items-start">
                <div>
                  <p class="font-medium text-gray-900">{{ loan.lender.name }}</p>
                  <p class="text-sm text-gray-600">{{ loan.purpose }}</p>
                  <p class="text-xs text-gray-500">{{ loan.term_months }} months • {{ loan.interest_rate }}%</p>
                </div>
                <div class="text-right">
                  <p class="font-semibold text-gray-900">${{ loan.amount.toLocaleString() }}</p>
                  <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                    {{ loan.status }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="/farmer/farms/create" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
          <i class="fa fa-leaf text-2xl mb-2 text-green-600"></i>
          <span class="text-sm font-medium text-gray-900">Add Farm</span>
        </a>
        <a href="/farmer/loans/create" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
          <i class="fa fa-dollar-sign text-2xl mb-2 text-blue-600"></i>
          <span class="text-sm font-medium text-gray-900">Apply for Loan</span>
        </a>
        <a href="/farmer/insurance/create" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
          <i class="fa fa-shield-alt text-2xl mb-2 text-purple-600"></i>
          <span class="text-sm font-medium text-gray-900">Get Insurance</span>
        </a>
        <a href="/farmer/reports" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
          <i class="fa fa-chart-bar text-2xl mb-2 text-yellow-600"></i>
          <span class="text-sm font-medium text-gray-900">View Reports</span>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import DashboardLayout from '../../Shared/DashboardLayout.vue'

defineOptions({
  layout: DashboardLayout
})

defineProps({
  farms: Array,
  recentTransactions: Array,
  activeLoans: Array,
  activeInsurance: Array,
  stats: Object
})
</script>
