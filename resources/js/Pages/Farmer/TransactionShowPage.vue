<template>
  <Head title="Transaction Details" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Transaction Details</h1>
        <p class="text-gray-600">View transaction information</p>
      </div>
      <div class="flex space-x-3">
        <a href="/farmer/transactions" class="text-blue-600 hover:text-blue-700 font-medium">
          ← Back to Transactions
        </a>
        <a :href="`/farmer/transactions/${transaction.id}/edit`" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
          Edit Transaction
        </a>
      </div>
    </div>

    <!-- Transaction Details Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h2 class="text-xl font-semibold text-gray-900">Transaction #{{ transaction.id }}</h2>
          <span :class="[
            'text-sm px-3 py-1 rounded-full font-medium',
            transaction.type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
          ]">
            {{ transaction.type }}
          </span>
        </div>
      </div>

      <div class="p-6 space-y-6">
        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Transaction Information</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Amount</dt>
                <dd class="text-2xl font-bold text-gray-900">${{ transaction.amount.toLocaleString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Type</dt>
                <dd class="text-sm text-gray-900">{{ transaction.type }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Description</dt>
                <dd class="text-sm text-gray-900">{{ transaction.description }}</dd>
              </div>
            </dl>
          </div>

          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Details</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Farm</dt>
                <dd class="text-sm text-gray-900">{{ transaction.farm.name }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Transaction Date</dt>
                <dd class="text-sm text-gray-900">{{ new Date(transaction.date).toLocaleDateString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Recorded On</dt>
                <dd class="text-sm text-gray-900">{{ new Date(transaction.created_at).toLocaleDateString() }}</dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Actions -->
        <div class="border-t border-gray-200 pt-6">
          <div class="flex justify-end space-x-3">
            <a href="/farmer/transactions" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
              Back to Transactions
            </a>
            <a :href="`/farmer/transactions/${transaction.id}/edit`" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
              Edit Transaction
            </a>
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
  transaction: Object
})
</script>
