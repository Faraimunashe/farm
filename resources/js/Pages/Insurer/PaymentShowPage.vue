<template>
  <Head title="Payment Details" />

  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Payment Details</h1>
        <p class="text-gray-600">Review and manage payment information</p>
      </div>
      <a href="/insurer/payments" class="text-blue-600 hover:text-blue-700">← Back to Payments</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <!-- Payment Header -->
      <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-start">
          <div>
            <h2 class="text-xl font-semibold text-gray-900">Payment #{{ payment.id }}</h2>
            <p class="text-gray-600">{{ payment.description || 'Insurance Premium Payment' }}</p>
            <div class="mt-2 flex items-center space-x-4">
              <span :class="[
                'text-sm px-3 py-1 rounded-full font-medium',
                payment.status === 'completed' ? 'bg-green-100 text-green-800' :
                payment.status === 'failed' ? 'bg-red-100 text-red-800' :
                'bg-yellow-100 text-yellow-800'
              ]">
                {{ payment.status.charAt(0).toUpperCase() + payment.status.slice(1) }}
              </span>
              <span class="text-sm text-gray-500">{{ new Date(payment.payment_date || payment.created_at).toLocaleDateString() }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="p-6 space-y-6">
        <!-- Payment Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-3">Payment Details</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Amount</dt>
                <dd class="text-lg font-semibold text-gray-900">${{ parseFloat(payment.amount || 0).toLocaleString() }}</dd>
              </div>
              <div v-if="payment.fee_amount">
                <dt class="text-sm font-medium text-gray-500">Fee Amount</dt>
                <dd class="text-sm text-gray-900">${{ parseFloat(payment.fee_amount).toLocaleString() }}</dd>
              </div>
              <div v-if="payment.net_amount">
                <dt class="text-sm font-medium text-gray-500">Net Amount</dt>
                <dd class="text-sm text-gray-900">${{ parseFloat(payment.net_amount).toLocaleString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                <dd class="text-sm text-gray-900 capitalize">{{ payment.payment_method }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Currency</dt>
                <dd class="text-sm text-gray-900">{{ payment.currency || 'USD' }}</dd>
              </div>
            </dl>
          </div>

          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-3">Policy Information</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Policy Holder</dt>
                <dd class="text-sm text-gray-900">{{ payment.user?.name || 'N/A' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Farm</dt>
                <dd class="text-sm text-gray-900">{{ payment.insurance?.farm?.name || 'N/A' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Insurance Plan</dt>
                <dd class="text-sm text-gray-900">{{ payment.insurance?.insurance_plan?.name || 'N/A' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Policy Number</dt>
                <dd class="text-sm text-gray-900">{{ payment.insurance?.policy_number || 'N/A' }}</dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Transaction Details -->
        <div v-if="payment.transaction_id">
          <h3 class="text-lg font-medium text-gray-900 mb-3">Transaction Details</h3>
          <div class="bg-gray-50 p-4 rounded-lg">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                <dd class="text-sm font-mono text-gray-900">{{ payment.transaction_id }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Payment Date</dt>
                <dd class="text-sm text-gray-900">{{ new Date(payment.payment_date || payment.created_at).toLocaleString() }}</dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Gateway Response -->
        <div v-if="payment.gateway_response && Object.keys(payment.gateway_response).length > 0">
          <h3 class="text-lg font-medium text-gray-900 mb-3">Gateway Response</h3>
          <div class="bg-gray-50 p-4 rounded-lg">
            <pre class="text-xs text-gray-700 overflow-x-auto">{{ JSON.stringify(payment.gateway_response, null, 2) }}</pre>
          </div>
        </div>

        <!-- Status Update Forms -->
        <div v-if="payment.status === 'pending'" class="space-y-4">
          <h3 class="text-lg font-medium text-gray-900">Update Payment Status</h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <form @submit.prevent="completePayment" class="bg-green-50 p-4 rounded-lg border border-green-200">
              <h4 class="font-medium text-green-800 mb-3">Mark as Completed</h4>
              <div class="space-y-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                  <textarea
                    v-model="completionForm.notes"
                    rows="2"
                    placeholder="Enter completion notes..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{ 'border-red-500': completionForm.errors.notes }"
                  ></textarea>
                  <div v-if="completionForm.errors.notes" class="text-sm text-red-500 mt-1">{{ completionForm.errors.notes }}</div>
                </div>
                <button
                  type="submit"
                  :disabled="completionLoading"
                  class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span v-if="!completionLoading">Mark Completed</span>
                  <span v-else>Updating...</span>
                </button>
              </div>
            </form>

            <form @submit.prevent="failPayment" class="bg-red-50 p-4 rounded-lg border border-red-200">
              <h4 class="font-medium text-red-800 mb-3">Mark as Failed</h4>
              <div class="space-y-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Failure Reason</label>
                  <textarea
                    v-model="failureForm.reason"
                    rows="2"
                    placeholder="Enter failure reason..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    :class="{ 'border-red-500': failureForm.errors.reason }"
                    required
                  ></textarea>
                  <div v-if="failureForm.errors.reason" class="text-sm text-red-500 mt-1">{{ failureForm.errors.reason }}</div>
                </div>
                <button
                  type="submit"
                  :disabled="failureLoading"
                  class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span v-if="!failureLoading">Mark Failed</span>
                  <span v-else>Updating...</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Payment History -->
        <div class="bg-gray-50 p-4 rounded-lg">
          <h3 class="text-lg font-medium text-gray-900 mb-3">Payment History</h3>
          <div class="space-y-2">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Created:</span>
              <span class="text-gray-900">{{ new Date(payment.created_at).toLocaleString() }}</span>
            </div>
            <div v-if="payment.updated_at !== payment.created_at" class="flex justify-between text-sm">
              <span class="text-gray-600">Last Updated:</span>
              <span class="text-gray-900">{{ new Date(payment.updated_at).toLocaleString() }}</span>
            </div>
            <div v-if="payment.payment_date" class="flex justify-between text-sm">
              <span class="text-gray-600">Payment Date:</span>
              <span class="text-gray-900">{{ new Date(payment.payment_date).toLocaleString() }}</span>
            </div>
          </div>
        </div>

        <div class="border-t border-gray-200 pt-6">
          <div class="flex justify-end space-x-3">
            <a href="/insurer/payments" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg">Back to Payments</a>
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
  payment: Object
})

const completionLoading = ref(false)
const failureLoading = ref(false)

const completionForm = useForm({
  notes: ''
})

const failureForm = useForm({
  reason: ''
})

const completePayment = () => {
  completionLoading.value = true
  completionForm.patch(`/insurer/payments/${props.payment.id}/status`, {
    data: { status: 'completed' },
    onFinish: () => {
      completionLoading.value = false
    }
  })
}

const failPayment = () => {
  failureLoading.value = true
  failureForm.patch(`/insurer/payments/${props.payment.id}/status`, {
    data: { status: 'failed' },
    onFinish: () => {
      failureLoading.value = false
    }
  })
}
</script>
