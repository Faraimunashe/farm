<template>
  <Head title="Reports & Analytics" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Reports & Analytics</h1>
        <p class="text-gray-600">Comprehensive insights into your lending portfolio performance</p>
      </div>
      <div class="flex space-x-3">
        <a href="/lender/reports/loan-performance" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
          <i class="fa fa-chart-line mr-2"></i>Loan Performance
        </a>
        <a href="/lender/reports/financial-summary" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
          <i class="fa fa-chart-pie mr-2"></i>Financial Summary
        </a>
      </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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
          <div class="p-2 bg-red-100 rounded-lg">
            <i class="fa fa-exclamation-triangle text-2xl text-red-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Defaulted Loans</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats?.defaultedLoans || 0 }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Loan Amounts by Status -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Loan Amounts by Status</h3>
        <div v-if="!loanAmountsByStatus || loanAmountsByStatus.length === 0" class="text-center py-8">
          <span class="text-gray-500">No data available</span>
        </div>
        <div v-else class="space-y-4">
          <div v-for="item in loanAmountsByStatus" :key="item.status" class="flex items-center justify-between">
            <div class="flex items-center">
              <div :class="[
                'w-3 h-3 rounded-full mr-3',
                getStatusColor(item.status)
              ]"></div>
              <span class="text-sm font-medium text-gray-700">{{ item.status }}</span>
            </div>
            <div class="text-right">
              <p class="text-sm font-semibold text-gray-900">${{ item.total_amount?.toLocaleString() || 0 }}</p>
              <p class="text-xs text-gray-500">{{ item.count }} loans</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Monthly Transactions -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Transactions ({{ new Date().getFullYear() }})</h3>
        <div v-if="!monthlyTransactions || monthlyTransactions.length === 0" class="text-center py-8">
          <span class="text-gray-500">No transactions this year</span>
        </div>
        <div v-else class="space-y-3">
          <div v-for="item in monthlyTransactions" :key="item.month" class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700">{{ getMonthName(item.month) }}</span>
            <div class="flex items-center">
              <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                <div
                  class="bg-blue-600 h-2 rounded-full"
                  :style="{ width: getPercentageWidth(item.total_amount) }"
                ></div>
              </div>
              <span class="text-sm font-semibold text-gray-900">${{ item.total_amount?.toLocaleString() || 0 }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

            <!-- Farm Size Analysis -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Loan Performance by Farm Size</h3>
          <div v-if="!loansByFarmSize || Object.keys(loansByFarmSize).length === 0" class="text-center py-8">
            <span class="text-gray-500">No farm size data available</span>
          </div>
          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farm Size Category</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number of Loans</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average Loan Amount</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Default Rate</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(item, category) in loansByFarmSize" :key="category" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ category }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ item.loan_count }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${{ item.avg_loan_amount?.toLocaleString() || 0 }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="[
                      'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                      getDefaultRateClass(item.defaulted_count, item.loan_count)
                    ]">
                      {{ calculateDefaultRate(item.defaulted_count, item.loan_count) }}%
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

    <!-- Export Reports -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Reports</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="border border-gray-200 rounded-lg p-4">
          <h4 class="font-medium text-gray-900 mb-2">Portfolio Summary</h4>
          <p class="text-sm text-gray-600 mb-3">Complete overview of your lending portfolio</p>
          <button class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            <i class="fa fa-download mr-2"></i>Export CSV
          </button>
        </div>

        <div class="border border-gray-200 rounded-lg p-4">
          <h4 class="font-medium text-gray-900 mb-2">Loan Performance</h4>
          <p class="text-sm text-gray-600 mb-3">Detailed analysis of loan performance metrics</p>
          <button class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
            <i class="fa fa-download mr-2"></i>Export CSV
          </button>
        </div>

        <div class="border border-gray-200 rounded-lg p-4">
          <h4 class="font-medium text-gray-900 mb-2">Financial Summary</h4>
          <p class="text-sm text-gray-600 mb-3">Cash flow and financial performance data</p>
          <button class="w-full bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600">
            <i class="fa fa-download mr-2"></i>Export CSV
          </button>
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
        <a href="/lender/documents" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
          <i class="fa fa-file-alt text-2xl mb-2 text-yellow-600"></i>
          <span class="text-sm font-medium text-gray-900">Documents</span>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import LenderLayout from '../../Shared/LenderLayout.vue'
import { computed } from 'vue'

defineOptions({
  layout: LenderLayout
})

const props = defineProps({
  stats: Object,
  loanAmountsByStatus: Array,
  monthlyTransactions: Array,
  loansByFarmSize: Array
})

const getStatusColor = (status) => {
  const colors = {
    'pending': 'bg-yellow-400',
    'active': 'bg-green-400',
    'completed': 'bg-blue-400',
    'defaulted': 'bg-red-400',
    'rejected': 'bg-gray-400'
  }
  return colors[status] || 'bg-gray-400'
}

const getMonthName = (monthNumber) => {
  const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ]
  return months[monthNumber - 1] || 'Unknown'
}

const getPercentageWidth = (amount) => {
  if (!props.monthlyTransactions || props.monthlyTransactions.length === 0) return '0%'

  const maxAmount = Math.max(...props.monthlyTransactions.map(item => item.total_amount || 0))
  if (maxAmount === 0) return '0%'

  return `${(amount / maxAmount) * 100}%`
}

const getDefaultRateClass = (defaultedCount, totalCount) => {
  const rate = (defaultedCount / totalCount) * 100
  if (rate === 0) return 'bg-green-100 text-green-800'
  if (rate <= 5) return 'bg-yellow-100 text-yellow-800'
  return 'bg-red-100 text-red-800'
}

const calculateDefaultRate = (defaultedCount, totalCount) => {
  if (totalCount === 0) return 0
  return ((defaultedCount / totalCount) * 100).toFixed(1)
}
</script>
