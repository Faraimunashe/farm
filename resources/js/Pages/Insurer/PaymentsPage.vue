<template>
  <div>
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <i class="fa fa-dollar-sign text-3xl text-green-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Total Revenue</p>
            <p class="text-2xl font-semibold text-gray-900">${{ stats.totalRevenue?.toLocaleString() || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <i class="fa fa-clock text-3xl text-yellow-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Pending Payments</p>
            <p class="text-2xl font-semibold text-gray-900">${{ stats.pendingPayments?.toLocaleString() || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <i class="fa fa-times-circle text-3xl text-red-600"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Failed Payments</p>
            <p class="text-2xl font-semibold text-gray-900">${{ stats.failedPayments?.toLocaleString() || 0 }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-lg shadow">
      <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Payment Management</h2>
          <div class="flex space-x-3">
            <select v-model="filters.status" class="border border-gray-300 rounded-md px-3 py-2">
              <option value="">All Statuses</option>
              <option value="pending">Pending</option>
              <option value="completed">Completed</option>
              <option value="failed">Failed</option>
            </select>
            <select v-model="filters.payment_method" class="border border-gray-300 rounded-md px-3 py-2">
              <option value="">All Methods</option>
              <option value="paynow">PayNow</option>
              <option value="ecocash">EcoCash</option>
              <option value="bank">Bank Transfer</option>
            </select>
            <a href="/insurer/payments/export" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
              <i class="fa fa-download mr-2"></i>Export
            </a>
          </div>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Payment Details
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Policy Info
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Amount
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Method
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="payment in payments.data" :key="payment.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ payment.user?.name || 'Unknown User' }}</div>
                <div class="text-sm text-gray-500">{{ payment.transaction_id || 'No Transaction ID' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ payment.insurance?.policy_number || 'N/A' }}</div>
                <div class="text-sm text-gray-500">{{ payment.insurance?.insurancePlan?.name || 'N/A' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${{ payment.amount?.toLocaleString() || 0 }}</div>
                <div v-if="payment.fee_amount > 0" class="text-sm text-gray-500">
                  Fee: ${{ payment.fee_amount?.toLocaleString() || 0 }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                  {{ payment.payment_method }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  getStatusClass(payment.status)
                ]">
                  {{ payment.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ new Date(payment.created_at).toLocaleDateString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <a :href="`/insurer/payments/${payment.id}`" class="text-blue-600 hover:text-blue-900">
                  <i class="fa fa-eye mr-1"></i>View
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="payments && payments.links && payments.links.length > 3" class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Showing {{ payments.from }} to {{ payments.to }} of {{ payments.total }} results
          </div>
          <div class="flex space-x-2">
            <a
              v-for="(link, index) in payments.links"
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
  </div>
</template>

<script setup>
import InsurerLayout from '../../Shared/InsurerLayout.vue'
import { ref } from 'vue'

defineOptions({
  layout: InsurerLayout
})

const props = defineProps({
  payments: Object,
  stats: Object
})

const filters = ref({
  status: '',
  payment_method: ''
})

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'completed': 'bg-green-100 text-green-800',
    'failed': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}
</script>
