<template>
  <Head title="Transactions" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Transactions</h1>
        <p class="text-gray-600">Monitor all loan-related financial activities</p>
      </div>
      <div class="flex space-x-3">
        <a href="/lender/loans" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
          <i class="fa fa-file-invoice-dollar mr-2"></i>View Loans
        </a>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
          <select v-model="filters.type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            <option value="">All Types</option>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
          <select v-model="filters.dateRange" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            <option value="">All Time</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
            <option value="quarter">This Quarter</option>
            <option value="year">This Year</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Amount Range</label>
          <select v-model="filters.amountRange" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            <option value="">All Amounts</option>
            <option value="0-1000">$0 - $1,000</option>
            <option value="1000-5000">$1,000 - $5,000</option>
            <option value="5000-10000">$5,000 - $10,000</option>
            <option value="10000+">$10,000+</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Search by description..."
            class="w-full border border-gray-300 rounded-lg px-3 py-2"
          >
        </div>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-lg">
            <i class="fa fa-arrow-down text-2xl text-green-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Income</p>
            <p class="text-2xl font-bold text-gray-900">${{ summary.totalIncome?.toLocaleString() || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <i class="fa fa-arrow-up text-2xl text-blue-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Expenses</p>
            <p class="text-2xl font-bold text-gray-900">${{ summary.totalExpenses?.toLocaleString() || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <i class="fa fa-dollar-sign text-2xl text-yellow-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Income</p>
            <p class="text-2xl font-bold text-gray-900">${{ summary.totalIncome?.toLocaleString() || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-red-100 rounded-lg">
            <i class="fa fa-exclamation-triangle text-2xl text-red-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Expenses</p>
            <p class="text-2xl font-bold text-gray-900">${{ summary.totalExpenses?.toLocaleString() || 0 }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Transaction History</h3>
      </div>

      <div v-if="!transactions || transactions.data.length === 0" class="text-center py-12">
        <i class="fa fa-exchange-alt text-4xl text-gray-400 mb-4"></i>
        <p class="text-gray-500 text-lg">No transactions found</p>
        <p class="text-gray-400">Transactions will appear here as loans are processed.</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrower</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="transaction in transactions.data" :key="transaction.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ new Date(transaction.transaction_date).toLocaleDateString() }}</div>
                <div class="text-sm text-gray-500">{{ new Date(transaction.transaction_date).toLocaleTimeString() }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 font-semibold text-sm">{{ transaction.user?.name?.charAt(0)?.toUpperCase() || 'U' }}</span>
                  </div>
                  <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900">{{ transaction.user?.name || 'Unknown User' }}</div>
                    <div class="text-sm text-gray-500">{{ transaction.loan?.purpose || 'No purpose' }}</div>
                  </div>
                </div>
              </td>
                             <td class="px-6 py-4 whitespace-nowrap">
                 <div class="text-sm text-gray-900">Farm: {{ transaction.farm?.name || 'No farm' }}</div>
                 <div class="text-sm text-gray-500">{{ transaction.farm?.size || 0 }} acres</div>
               </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  getTransactionTypeClass(transaction.type)
                ]">
                  {{ transaction.type }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900">{{ transaction.description }}</div>
                <div class="text-sm text-gray-500">{{ transaction.created_at ? new Date(transaction.created_at).toLocaleDateString() : '' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${{ transaction.amount?.toLocaleString() || 0 }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                 <div class="flex space-x-2">
                   <a :href="`/lender/transactions/${transaction.id}`" class="text-blue-600 hover:text-blue-900">
                     <i class="fa fa-eye mr-1"></i>View
                   </a>
                   <a :href="`/lender/farms/${transaction.farm?.id}`" class="text-green-600 hover:text-green-900">
                     <i class="fa fa-leaf mr-1"></i>Farm
                   </a>
                 </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="transactions && transactions.links && transactions.links.length > 3" class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Showing {{ transactions.from }} to {{ transactions.to }} of {{ transactions.total }} results
          </div>
          <div class="flex space-x-2">
            <a
              v-for="(link, index) in transactions.links"
              :key="index"
              :href="link.url"
              :class="[
                'px-3 py-2 text-sm font-medium rounded-md',
                link.active
                  ? 'bg-blue-500 text-white'
                  : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'
              ]"
              v-html="link.label"
            ></a>
          </div>
        </div>
      </div>
    </div>

    <!-- Export Section -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Transactions</h3>
      <div class="flex space-x-4">
        <button class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
          <i class="fa fa-download mr-2"></i>Export to CSV
        </button>
        <button class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
          <i class="fa fa-file-pdf mr-2"></i>Export to PDF
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import LenderLayout from '../../Shared/LenderLayout.vue'
import { ref, computed } from 'vue'

defineOptions({
  layout: LenderLayout
})

const props = defineProps({
  transactions: Object
})

const filters = ref({
  type: '',
  dateRange: '',
  amountRange: '',
  search: ''
})

const summary = computed(() => {
  if (!props.transactions?.data) return {}

  return props.transactions.data.reduce((acc, transaction) => {
    const amount = transaction.amount || 0
    switch (transaction.type) {
      case 'income':
        acc.totalIncome = (acc.totalIncome || 0) + amount
        break
      case 'expense':
        acc.totalExpenses = (acc.totalExpenses || 0) + amount
        break
    }
    return acc
  }, {})
})

const getTransactionTypeClass = (type) => {
  const classes = {
    'income': 'bg-green-100 text-green-800',
    'expense': 'bg-red-100 text-red-800'
  }
  return classes[type] || 'bg-gray-100 text-gray-800'
}
</script>
