<template>
  <Head title="Policy Details" />

  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Policy Details</h1>
        <p class="text-gray-600">View and manage policy information</p>
      </div>
      <a href="/insurer/policies" class="text-blue-600 hover:text-blue-700">← Back to Policies</a>
    </div>

    <!-- Success Message -->
    <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-50 border border-green-200 rounded-lg p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-green-800">{{ $page.props.flash.success }}</p>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-start">
          <div>
            <h2 class="text-xl font-semibold text-gray-900">Policy #{{ policy.policy_number }}</h2>
            <p class="text-gray-600">{{ policy.coverage_type }} Coverage</p>
          </div>
          <span :class="[
            'text-sm px-3 py-1 rounded-full',
            policy.status === 'active' ? 'bg-green-100 text-green-800' :
            policy.status === 'expired' ? 'bg-red-100 text-red-800' :
            'bg-gray-100 text-gray-800'
          ]">
            {{ policy.status }}
          </span>
        </div>
      </div>

      <div class="p-6 space-y-6">
        <!-- Policy Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-3">Policy Information</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Policy Number</dt>
                <dd class="text-sm text-gray-900">{{ policy.policy_number }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Coverage Type</dt>
                <dd class="text-sm text-gray-900">{{ policy.coverage_type }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Coverage Amount</dt>
                <dd class="text-sm font-semibold text-gray-900">${{ parseFloat(policy.coverage_amount).toLocaleString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Premium Amount</dt>
                <dd class="text-sm font-semibold text-gray-900">${{ parseFloat(policy.premium_amount).toLocaleString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                <dd class="text-sm text-gray-900">{{ new Date(policy.start_date).toLocaleDateString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">End Date</dt>
                <dd class="text-sm text-gray-900">{{ new Date(policy.end_date).toLocaleDateString() }}</dd>
              </div>
            </dl>
          </div>

          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-3">Farmer Information</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Farmer Name</dt>
                <dd class="text-sm text-gray-900">{{ policy.user.name }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="text-sm text-gray-900">{{ policy.user.email }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Farm Name</dt>
                <dd class="text-sm text-gray-900">{{ policy.farm.name }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Farm Type</dt>
                <dd class="text-sm text-gray-900">{{ policy.farm.type }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Farm Size</dt>
                <dd class="text-sm text-gray-900">{{ policy.farm.size }} hectares</dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Status Update -->
        <div class="border-t border-gray-200 pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Update Policy Status</h3>
          <form @submit.prevent="updateStatus" class="flex items-center space-x-4">
            <select v-model="statusForm.status" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="active">Active</option>
              <option value="expired">Expired</option>
              <option value="cancelled">Cancelled</option>
            </select>
            <button
              type="submit"
              :disabled="loading"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
            >
              {{ loading ? 'Updating...' : 'Update Status' }}
            </button>
          </form>
        </div>

        <!-- Actions -->
        <div class="border-t border-gray-200 pt-6">
          <div class="flex justify-end space-x-3">
            <a href="/insurer/policies" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg">Back to Policies</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import InsurerLayout from '../../Shared/InsurerLayout.vue'

defineOptions({
  layout: InsurerLayout
})

const props = defineProps({
  policy: Object
})

const loading = ref(false)

const statusForm = useForm({
  status: props.policy.status
})

const updateStatus = () => {
  loading.value = true
  statusForm.patch(`/insurer/policies/${props.policy.id}/status`, {
    onFinish: () => {
      loading.value = false
    }
  })
}
</script>
