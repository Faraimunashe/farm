<template>
  <Head title="All Loans" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">All Loans</h1>
        <p class="text-gray-600">Manage your loan portfolio and review applications</p>
      </div>
      <div class="flex space-x-3">
        <a href="/lender/loans/applications" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
          <i class="fa fa-file-invoice-dollar mr-2"></i>Review Applications
        </a>
        <a href="/lender/loans/active" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
          <i class="fa fa-check-circle mr-2"></i>Active Loans
        </a>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
          <select v-model="filters.status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="active">Active</option>
            <option value="completed">Completed</option>
            <option value="defaulted">Defaulted</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Purpose</label>
          <select v-model="filters.purpose" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            <option value="">All Purposes</option>
            <option value="equipment">Equipment</option>
            <option value="seeds">Seeds & Fertilizers</option>
            <option value="expansion">Farm Expansion</option>
            <option value="operational">Operational Costs</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Amount Range</label>
          <select v-model="filters.amountRange" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            <option value="">All Amounts</option>
            <option value="0-10000">$0 - $10,000</option>
            <option value="10000-50000">$10,000 - $50,000</option>
            <option value="50000+">$50,000+</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Search by borrower name..."
            class="w-full border border-gray-300 rounded-lg px-3 py-2"
          >
        </div>
      </div>
    </div>

    <!-- Loans Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Loan Portfolio</h3>
      </div>

      <div v-if="!loans || loans.data.length === 0" class="text-center py-12">
        <i class="fa fa-file-invoice-dollar text-4xl text-gray-400 mb-4"></i>
        <p class="text-gray-500 text-lg">No loans found</p>
        <p class="text-gray-400">Start by reviewing loan applications from farmers.</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrower</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farm</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="loan in loans.data" :key="loan.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 font-semibold text-sm">{{ loan.user?.name?.charAt(0)?.toUpperCase() || 'U' }}</span>
                  </div>
                  <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900">{{ loan.user?.name || 'Unknown User' }}</div>
                    <div class="text-sm text-gray-500">{{ loan.user?.email || 'No email' }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ loan.user?.farms?.[0]?.name || 'No Farm' }}</div>
                <div class="text-sm text-gray-500">{{ loan.user?.farms?.[0]?.size || 0 }} acres</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ loan.purpose }}</div>
                <div class="text-sm text-gray-500">{{ loan.term_months || 0 }} months</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${{ loan.amount?.toLocaleString() || 0 }}</div>
                <div class="text-sm text-gray-500">{{ loan.interest_rate || 0 }}% interest</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  getStatusClass(loan.status)
                ]">
                  {{ loan.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ new Date(loan.created_at).toLocaleDateString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <a :href="`/lender/loans/${loan.id}`" class="text-blue-600 hover:text-blue-900">
                    <i class="fa fa-eye mr-1"></i>View
                  </a>
                  <a v-if="loan.status === 'pending'" :href="`/lender/loans/${loan.id}`" class="text-green-600 hover:text-green-900">
                    <i class="fa fa-check mr-1"></i>Review
                  </a>
                  <a v-if="loan.status === 'active'" :href="`/lender/loans/${loan.id}/transactions`" class="text-purple-600 hover:text-purple-900">
                    <i class="fa fa-exchange-alt mr-1"></i>Transactions
                  </a>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="loans && loans.links && loans.links.length > 3" class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Showing {{ loans.from }} to {{ loans.to }} of {{ loans.total }} results
          </div>
          <div class="flex space-x-2">
            <a
              v-for="(link, index) in loans.links"
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
import LenderLayout from '../../Shared/LenderLayout.vue'
import { ref, computed } from 'vue'

defineOptions({
  layout: LenderLayout
})

const props = defineProps({
  loans: Object
})

const filters = ref({
  status: '',
  purpose: '',
  amountRange: '',
  search: ''
})

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'active': 'bg-green-100 text-green-800',
    'completed': 'bg-blue-100 text-blue-800',
    'defaulted': 'bg-red-100 text-red-800',
    'rejected': 'bg-gray-100 text-gray-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}
</script>
