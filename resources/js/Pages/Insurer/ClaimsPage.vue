<template>
  <div>
    <div class="bg-white rounded-lg shadow">
      <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900">Claims Management</h2>
          <div class="flex space-x-3">
            <select v-model="filters.status" class="border border-gray-300 rounded-md px-3 py-2">
              <option value="">All Statuses</option>
              <option value="pending">Pending</option>
              <option value="investigating">Investigating</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
            <input
              v-model="filters.search"
              type="text"
              placeholder="Search claims..."
              class="border border-gray-300 rounded-md px-3 py-2"
            />
          </div>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Claim Details
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Policy Info
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Amount
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
            <tr v-for="claim in claims.data" :key="claim.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ claim.user?.name || 'Unknown User' }}</div>
                <div class="text-sm text-gray-500">{{ claim.farm?.name || 'Unknown Farm' }}</div>
                <div class="text-sm text-gray-500">{{ claim.description?.substring(0, 50) }}...</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ claim.insurance?.policy_number || 'N/A' }}</div>
                <div class="text-sm text-gray-500">{{ claim.insurance?.insurancePlan?.name || 'N/A' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${{ claim.claim_amount?.toLocaleString() || 0 }}</div>
                <div v-if="claim.approved_amount" class="text-sm text-gray-500">
                  Approved: ${{ claim.approved_amount?.toLocaleString() || 0 }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                  getStatusClass(claim.status)
                ]">
                  {{ claim.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ new Date(claim.incident_date).toLocaleDateString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <a :href="`/insurer/claims/${claim.id}`" class="text-blue-600 hover:text-blue-900">
                  <i class="fa fa-eye mr-1"></i>View
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="claims && claims.links && claims.links.length > 3" class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Showing {{ claims.from }} to {{ claims.to }} of {{ claims.total }} results
          </div>
          <div class="flex space-x-2">
            <a
              v-for="(link, index) in claims.links"
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
  claims: Object
})

const filters = ref({
  status: '',
  search: ''
})

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'investigating': 'bg-blue-100 text-blue-800',
    'approved': 'bg-green-100 text-green-800',
    'rejected': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}
</script>
