<template>
  <Head title="Reports" />

  <div class="space-y-6">
    <!-- Header -->
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Reports & Analytics</h1>
      <p class="text-gray-600">Comprehensive insights into your farm operations</p>
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
            <i class="fa fa-dollar-sign text-2xl text-blue-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-900">${{ (stats?.totalRevenue || 0).toLocaleString() }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-purple-100 rounded-lg">
            <i class="fa fa-shield-alt text-2xl text-purple-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Insurance Value</p>
            <p class="text-2xl font-bold text-gray-900">${{ (stats?.totalInsuranceValue || 0).toLocaleString() }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <i class="fa fa-chart-line text-2xl text-yellow-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Growth Rate</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats?.growthRate || 0 }}%</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Farm Type Distribution -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Farm Type Distribution</h3>
        <div v-if="Object.keys(farmTypeDistribution).length === 0" class="text-center py-8">
          <span class="text-gray-500">No farm data available</span>
        </div>
        <div v-else class="space-y-3">
          <div v-for="(count, type) in farmTypeDistribution" :key="type" class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="w-4 h-4 rounded-full mr-3" :class="{
                'bg-green-500': type === 'crops',
                'bg-blue-500': type === 'livestock',
                'bg-purple-500': type === 'mixed'
              }"></div>
              <span class="text-sm font-medium text-gray-900 capitalize">{{ type }}</span>
            </div>
            <span class="text-sm text-gray-600">{{ count }} farms</span>
          </div>
        </div>
      </div>

      <!-- Loan Status Distribution -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Loan Status Distribution</h3>
        <div v-if="Object.keys(loanStatusDistribution).length === 0" class="text-center py-8">
          <span class="text-gray-500">No loan data available</span>
        </div>
        <div v-else class="space-y-3">
          <div v-for="(count, status) in loanStatusDistribution" :key="status" class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="w-4 h-4 rounded-full mr-3" :class="{
                'bg-green-500': status === 'approved',
                'bg-yellow-500': status === 'pending',
                'bg-red-500': status === 'rejected',
                'bg-gray-500': status === 'active'
              }"></div>
              <span class="text-sm font-medium text-gray-900 capitalize">{{ status }}</span>
            </div>
            <span class="text-sm text-gray-600">{{ count }} loans</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Monthly Transactions Chart -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Transaction Trends</h3>
      <div v-if="monthlyTransactions.length === 0" class="text-center py-8">
        <span class="text-gray-500">No transaction data available</span>
      </div>
      <div v-else class="space-y-4">
        <div v-for="transaction in monthlyTransactions" :key="`${transaction.year}-${transaction.month}`" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
          <div>
            <p class="text-sm font-medium text-gray-900">
              {{ new Date(transaction.year, transaction.month - 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' }) }}
            </p>
          </div>
          <div class="text-right">
            <p class="text-sm font-semibold text-gray-900">${{ transaction.total.toLocaleString() }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Export Section -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Reports</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <button class="flex items-center justify-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
          <i class="fa fa-chart-bar mr-2"></i>
          <span class="text-sm font-medium text-gray-900">Export Farm Report</span>
        </button>
        <button class="flex items-center justify-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
          <i class="fa fa-dollar-sign mr-2"></i>
          <span class="text-sm font-medium text-gray-900">Export Financial Report</span>
        </button>
        <button class="flex items-center justify-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
          <i class="fa fa-chart-line mr-2"></i>
          <span class="text-sm font-medium text-gray-900">Export Analytics</span>
        </button>
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
  stats: Object,
  monthlyTransactions: Array,
  farmTypeDistribution: Object,
  loanStatusDistribution: Object
})
</script>
