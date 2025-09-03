<template>
  <Head title="Insurer Dashboard" />

  <div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 text-white">
      <h1 class="text-2xl font-bold mb-2">Welcome back, {{ insurer.name }}!</h1>
      <p class="text-blue-100">Manage your insurance plans and policies effectively.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <i class="fa fa-file-contract text-2xl text-blue-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Plans</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.totalPlans }}</p>
            <p class="text-xs text-gray-500">{{ stats.activePlans }} active</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-lg">
            <i class="fa fa-users text-2xl text-green-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Policies</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.totalPolicies }}</p>
            <p class="text-xs text-gray-500">{{ stats.activePolicies }} active</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-purple-100 rounded-lg">
            <i class="fa fa-dollar-sign text-2xl text-purple-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-900">${{ stats.totalRevenue.toLocaleString() }}</p>
            <p class="text-xs text-gray-500">${{ stats.pendingRevenue.toLocaleString() }} pending</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <i class="fa fa-exclamation-triangle text-2xl text-yellow-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Claims</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.totalClaims }}</p>
            <p class="text-xs text-gray-500">{{ stats.pendingClaims }} pending</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Additional Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-indigo-100 rounded-lg">
            <i class="fa fa-credit-card text-2xl text-indigo-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Payments</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.totalPayments }}</p>
            <p class="text-xs text-gray-500">{{ stats.pendingPayments }} pending</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-orange-100 rounded-lg">
            <i class="fa fa-clock text-2xl text-orange-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Pending Policies</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.pendingPolicies }}</p>
            <p class="text-xs text-gray-500">Awaiting approval</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-red-100 rounded-lg">
            <i class="fa fa-times-circle text-2xl text-red-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Failed Payments</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.failedPayments || 0 }}</p>
            <p class="text-xs text-gray-500">Require attention</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="/insurer/plans/create" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
          <i class="fa fa-plus text-2xl mb-2 text-blue-600"></i>
          <span class="text-sm font-medium text-gray-900">Create Plan</span>
        </a>
        <a href="/insurer/policies" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
          <i class="fa fa-file-contract text-2xl mb-2 text-green-600"></i>
          <span class="text-sm font-medium text-gray-900">View Policies</span>
        </a>
        <a href="/insurer/claims" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
          <i class="fa fa-exclamation-triangle text-2xl mb-2 text-yellow-600"></i>
          <span class="text-sm font-medium text-gray-900">Manage Claims</span>
        </a>
        <a href="/insurer/payments" class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
          <i class="fa fa-credit-card text-2xl mb-2 text-indigo-600"></i>
          <span class="text-sm font-medium text-gray-900">View Payments</span>
        </a>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
      <div class="space-y-3">
        <div v-if="stats.pendingClaims > 0" class="flex items-center p-3 bg-yellow-50 rounded-lg">
          <i class="fa fa-exclamation-triangle text-yellow-600 mr-3"></i>
          <div class="flex-1">
            <p class="text-sm font-medium text-yellow-800">{{ stats.pendingClaims }} claims pending review</p>
            <p class="text-xs text-yellow-600">Require immediate attention</p>
          </div>
          <a href="/insurer/claims" class="text-sm text-yellow-600 hover:text-yellow-800">View →</a>
        </div>

        <div v-if="stats.pendingPolicies > 0" class="flex items-center p-3 bg-blue-50 rounded-lg">
          <i class="fa fa-file-contract text-blue-600 mr-3"></i>
          <div class="flex-1">
            <p class="text-sm font-medium text-blue-800">{{ stats.pendingPolicies }} policies pending approval</p>
            <p class="text-xs text-blue-600">Awaiting verification</p>
          </div>
          <a href="/insurer/policies" class="text-sm text-blue-600 hover:text-blue-800">View →</a>
        </div>

        <div v-if="stats.pendingPayments > 0" class="flex items-center p-3 bg-green-50 rounded-lg">
          <i class="fa fa-credit-card text-green-600 mr-3"></i>
          <div class="flex-1">
            <p class="text-sm font-medium text-green-800">{{ stats.pendingPayments }} payments pending</p>
            <p class="text-xs text-green-600">Processing transactions</p>
          </div>
          <a href="/insurer/payments" class="text-sm text-green-600 hover:text-green-800">View →</a>
        </div>

        <div v-if="!stats.pendingClaims && !stats.pendingPolicies && !stats.pendingPayments" class="text-center py-8 text-gray-500">
          <i class="fa fa-check-circle text-4xl text-green-500 mb-2"></i>
          <p class="text-sm">All systems are up to date!</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import InsurerLayout from '../../Shared/InsurerLayout.vue'

defineOptions({
  layout: InsurerLayout
})

defineProps({
  insurer: Object,
  stats: Object
})
</script>
