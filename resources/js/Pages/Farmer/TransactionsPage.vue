<template>
  <Head title="Transactions" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Transactions</h1>
        <p class="text-gray-600">View all your farm-related financial transactions</p>
      </div>
      <a href="/farmer/transactions/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
        Add Transaction
      </a>
    </div>

    <!-- Transactions Table -->
    <div v-if="!transactions || transactions.length === 0" class="text-center py-12">
      <div class="text-6xl mb-4">
        <i class="fa fa-chart-bar text-gray-300"></i>
      </div>
      <h3 class="text-lg font-medium text-gray-900 mb-2">No transactions yet</h3>
      <p class="text-gray-500 mb-6">Your financial transactions will appear here</p>
    </div>

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farm</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="transaction in transactions.data" :key="transaction.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ transaction.farm.name }}</div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900">{{ transaction.description }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold" :class="transaction.type === 'income' ? 'text-green-600' : 'text-red-600'">
                  {{ transaction.type === 'income' ? '+' : '-' }}${{ transaction.amount.toLocaleString() }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'text-xs px-2 py-1 rounded-full',
                  transaction.type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                ]">
                  {{ transaction.type }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ new Date(transaction.created_at).toLocaleDateString() }}</div>
                <div class="text-sm text-gray-500">{{ new Date(transaction.created_at).toLocaleTimeString() }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <a :href="`/farmer/transactions/${transaction.id}`" class="text-blue-600 hover:text-blue-900">View</a>
                  <a :href="`/farmer/transactions/${transaction.id}/edit`" class="text-green-600 hover:text-green-900">Edit</a>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="transactions.links && transactions.links.length > 3" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
          <div class="flex-1 flex justify-between sm:hidden">
            <a v-if="transactions.prev_page_url" :href="transactions.prev_page_url" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
              Previous
            </a>
            <a v-if="transactions.next_page_url" :href="transactions.next_page_url" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
              Next
            </a>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ transactions.from }}</span>
                to
                <span class="font-medium">{{ transactions.to }}</span>
                of
                <span class="font-medium">{{ transactions.total }}</span>
                results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                <a v-for="(link, index) in transactions.links" :key="index" :href="link.url" :class="[
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                  link.active ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                  index === 0 ? 'rounded-l-md' : '',
                  index === transactions.links.length - 1 ? 'rounded-r-md' : ''
                ]" v-html="link.label"></a>
              </nav>
            </div>
          </div>
        </div>
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
  transactions: Object
})
</script>
