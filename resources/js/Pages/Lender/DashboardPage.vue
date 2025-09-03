<template>
  <Head title="Lender Dashboard" />

  <div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg p-6 text-white">
      <h1 class="text-2xl font-bold mb-2">Welcome back, {{ lender?.name || 'Lender' }}!</h1>
      <p class="text-blue-100">Manage loan applications, track portfolio performance, and grow your lending business.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <i class="fa fa-file-invoice-dollar text-2xl text-blue-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Loans</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats?.totalLoans || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-lg">
            <i class="fa fa-check-circle text-2xl text-green-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Active Loans</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats?.activeLoanCount || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <i class="fa fa-clock text-2xl text-yellow-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Pending Applications</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats?.pendingLoanCount || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-purple-100 rounded-lg">
            <i class="fa fa-dollar-sign text-2xl text-purple-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Outstanding</p>
            <p class="text-2xl font-bold text-gray-900">${{ (stats?.totalOutstandingAmount || 0).toLocaleString() }}</p>
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
                <p class="font-medium text-gray-900">{{ transaction.farm?.user?.name || 'Unknown User' }}</p>
                <p class="text-sm text-gray-600">{{ transaction.description }}</p>
                <p class="text-xs text-gray-500">{{ new Date(transaction.date).toLocaleDateString() }}</p>
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

      <!-- Pending Loan Applications -->
      <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Pending Applications</h3>
        </div>
        <div class="p-6">
          <div v-if="!pendingLoans || pendingLoans.length === 0" class="text-center py-8">
            <span class="text-gray-500">No pending applications</span>
          </div>
          <div v-else class="space-y-4">
            <div v-for="loan in pendingLoans" :key="loan.id" class="p-4 bg-gray-50 rounded-lg">
              <div class="flex justify-between items-start">
                <div>
                  <p class="font-medium text-gray-900">{{ loan.user?.name || 'Unknown User' }}</p>
                  <p class="text-sm text-gray-600">{{ loan.purpose }}</p>
                  <p class="text-xs text-gray-500">{{ loan.user?.farms?.[0]?.name || 'No Farm' }} • {{ loan.user?.farms?.[0]?.size || 0 }} acres</p>
                </div>
                <div class="text-right">
                  <p class="font-semibold text-gray-900">${{ loan.amount.toLocaleString() }}</p>
                  <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">
                    {{ loan.status }}
                  </span>
                </div>
              </div>
              <div class="mt-3 flex space-x-2">
                <a :href="`/lender/loans/${loan.id}`" class="text-xs bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                  Review
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Active Loans Summary -->
    <div class="bg-white rounded-lg shadow">
      <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Active Loans Overview</h3>
      </div>
      <div class="p-6">
        <div v-if="!activeLoans || activeLoans.length === 0" class="text-center py-8">
          <span class="text-gray-500">No active loans</span>
        </div>
        <div v-else class="space-y-4">
          <div v-for="loan in activeLoans" :key="loan.id" class="p-4 bg-gray-50 rounded-lg">
            <div class="flex justify-between items-start">
              <div>
                <p class="font-medium text-gray-900">{{ loan.user?.name || 'Unknown User' }}</p>
                <p class="text-sm text-gray-600">{{ loan.purpose }}</p>
                                  <p class="text-xs text-gray-500">{{ loan.user?.farms?.[0]?.name || 'No Farm' }} • {{ loan.user?.farms?.[0]?.size || 0 }} acres</p>
              </div>
              <div class="text-right">
                <p class="font-semibold text-gray-900">${{ loan.amount.toLocaleString() }}</p>
                <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                  {{ loan.status }}
                </span>
              </div>
            </div>
            <div class="mt-3 flex space-x-2">
                              <a :href="`/lender/loans/${loan.id}`" class="text-xs bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                  View Details
                </a>
                              <a :href="`/lender/loans/${loan.id}/transactions`" class="text-xs bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                  Transactions
                </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="/lender/loans/applications" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
          <i class="fa fa-file-invoice-dollar text-2xl mb-2 text-blue-600"></i>
          <span class="text-sm font-medium text-gray-900">Review Applications</span>
        </a>
        <a href="/lender/loans/active" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
          <i class="fa fa-check-circle text-2xl mb-2 text-green-600"></i>
          <span class="text-sm font-medium text-gray-900">Active Loans</span>
        </a>
        <a href="/lender/transactions" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
          <i class="fa fa-exchange-alt text-2xl mb-2 text-purple-600"></i>
          <span class="text-sm font-medium text-gray-900">Transactions</span>
        </a>
        <a href="/lender/reports" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
          <i class="fa fa-chart-bar text-2xl mb-2 text-yellow-600"></i>
          <span class="text-sm font-medium text-gray-900">Reports</span>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import LenderLayout from '../../Shared/LenderLayout.vue'

defineOptions({
  layout: LenderLayout
})

defineProps({
  lender: Object,
  loanApplications: Array,
  activeLoans: Array,
  pendingLoans: Array,
  recentTransactions: Array,
  stats: Object
})
</script>
