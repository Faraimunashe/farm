<template>
  <Head title="Insurance Details" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Insurance Policy Details</h1>
        <p class="text-gray-600">View your insurance policy information and manage claims</p>
      </div>
      <div class="flex space-x-3">
        <a href="/farmer/insurance" class="text-blue-600 hover:text-blue-700 font-medium">
          ← Back to Insurance
        </a>
        <a v-if="insurance.status === 'pending'" :href="`/farmer/insurance/${insurance.id}/edit`" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
          Edit Policy
        </a>
      </div>
    </div>

    <!-- Success/Error Messages -->
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

    <div v-if="$page.props.flash && $page.props.flash.error" class="bg-red-50 border border-red-200 rounded-lg p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-red-800">{{ $page.props.flash.error }}</p>
        </div>
      </div>
    </div>

    <!-- Insurance Details Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-xl font-semibold text-gray-900">Policy #{{ insurance.policy_number }}</h2>
            <p class="text-sm text-gray-600">{{ insurance.insurance_plan?.name || 'Standard Plan' }}</p>
          </div>
          <div class="flex flex-col items-end space-y-2">
            <span :class="[
              'text-sm px-3 py-1 rounded-full font-medium',
              insurance.status === 'active' ? 'bg-green-100 text-green-800' :
              insurance.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
              insurance.status === 'expired' ? 'bg-red-100 text-red-800' :
              insurance.status === 'cancelled' ? 'bg-gray-100 text-gray-800' :
              'bg-blue-100 text-blue-800'
            ]">
              {{ insurance.status.charAt(0).toUpperCase() + insurance.status.slice(1) }}
            </span>
            <span :class="[
              'text-xs px-2 py-1 rounded-full',
              insurance.payment_status === 'paid' ? 'bg-green-100 text-green-800' :
              insurance.payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
              'bg-red-100 text-red-800'
            ]">
              {{ insurance.payment_status }}
            </span>
          </div>
        </div>
      </div>

      <div class="p-6 space-y-6">
        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Policy Information</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Insurer</dt>
                <dd class="text-sm text-gray-900">{{ insurance.insurer?.name || 'Unknown' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Farm</dt>
                <dd class="text-sm text-gray-900">{{ insurance.farm?.name || 'Unknown' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Coverage Type</dt>
                <dd class="text-sm text-gray-900 capitalize">{{ insurance.insurance_plan?.coverage_type || 'General' }} Insurance</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Coverage Amount</dt>
                <dd class="text-lg font-semibold text-gray-900">${{ parseFloat(insurance.coverage_amount || 0).toLocaleString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Premium Amount</dt>
                <dd class="text-sm text-gray-900">${{ parseFloat(insurance.premium_amount || 0).toLocaleString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                <dd class="text-sm text-gray-900 capitalize">{{ insurance.payment_method || 'N/A' }}</dd>
              </div>
            </dl>
          </div>

          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Policy Dates & Status</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                <dd class="text-sm text-gray-900">{{ new Date(insurance.start_date).toLocaleDateString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">End Date</dt>
                <dd class="text-sm text-gray-900">{{ new Date(insurance.end_date).toLocaleDateString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Created On</dt>
                <dd class="text-sm text-gray-900">{{ new Date(insurance.created_at).toLocaleDateString() }}</dd>
              </div>
              <div v-if="insurance.renewal_date">
                <dt class="text-sm font-medium text-gray-500">Renewal Date</dt>
                <dd class="text-sm text-gray-900">{{ new Date(insurance.renewal_date).toLocaleDateString() }}</dd>
              </div>
              <div v-if="insurance.claims_count > 0">
                <dt class="text-sm font-medium text-gray-500">Claims Filed</dt>
                <dd class="text-sm text-gray-900">{{ insurance.claims_count }}</dd>
              </div>
              <div v-if="insurance.last_claim_date">
                <dt class="text-sm font-medium text-gray-500">Last Claim</dt>
                <dd class="text-sm text-gray-900">{{ new Date(insurance.last_claim_date).toLocaleDateString() }}</dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Plan Details -->
        <div v-if="insurance.insurance_plan" class="bg-gray-50 p-4 rounded-lg">
          <h3 class="text-lg font-medium text-gray-900 mb-3">Plan Details</h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
              <span class="text-gray-600">Coverage %:</span>
              <div class="font-medium">{{ insurance.insurance_plan.coverage_percentage }}%</div>
            </div>
            <div>
              <span class="text-gray-600">Premium Rate:</span>
              <div class="font-medium">{{ insurance.insurance_plan.premium_rate }}%</div>
            </div>
            <div>
              <span class="text-gray-600">Duration:</span>
              <div class="font-medium">{{ insurance.insurance_plan.duration_months }} months</div>
            </div>
            <div>
              <span class="text-gray-600">Deductible:</span>
              <div class="font-medium">${{ parseFloat(insurance.insurance_plan.deductible_amount || 0).toLocaleString() }}</div>
            </div>
          </div>
        </div>

        <!-- Claims Section -->
        <div class="border-t border-gray-200 pt-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Claims History</h3>
            <button
              v-if="insurance.status === 'active'"
              @click="showClaimForm = true"
              class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
            >
              Submit New Claim
            </button>
          </div>

          <!-- Claims List -->
          <div v-if="insurance.claims && insurance.claims.length > 0" class="space-y-3">
            <div v-for="claim in insurance.claims" :key="claim.id" class="border border-gray-200 rounded-lg p-4">
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <div class="flex items-center space-x-3 mb-2">
                    <span class="text-sm font-medium text-gray-900">Claim #{{ claim.id }}</span>
                    <span :class="[
                      'text-xs px-2 py-1 rounded-full',
                      claim.status === 'approved' ? 'bg-green-100 text-green-800' :
                      claim.status === 'rejected' ? 'bg-red-100 text-red-800' :
                      claim.status === 'investigating' ? 'bg-yellow-100 text-yellow-800' :
                      'bg-gray-100 text-gray-800'
                    ]">
                      {{ claim.status.charAt(0).toUpperCase() + claim.status.slice(1) }}
                    </span>
                  </div>
                  <p class="text-sm text-gray-600 mb-2">{{ claim.description }}</p>
                  <div class="grid grid-cols-3 gap-4 text-xs text-gray-500">
                    <div>Amount: ${{ parseFloat(claim.claim_amount || 0).toLocaleString() }}</div>
                    <div>Date: {{ new Date(claim.incident_date).toLocaleDateString() }}</div>
                    <div v-if="claim.approved_amount">Approved: ${{ parseFloat(claim.approved_amount).toLocaleString() }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- No Claims Message -->
          <div v-else class="text-center py-8 text-gray-500">
            <div class="text-4xl mb-2">📋</div>
            <p class="text-sm">No claims have been filed for this policy yet.</p>
          </div>
        </div>

        <!-- Claim Form Modal -->
        <div v-if="showClaimForm" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
          <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Submit New Claim</h3>

              <form @submit.prevent="submitClaim" class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Claim Amount *</label>
                  <input
                    type="number"
                    v-model="claimForm.claim_amount"
                    step="0.01"
                    min="0"
                    :max="insurance.coverage_amount"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    :class="{ 'border-red-500': claimForm.errors.claim_amount }"
                    required
                  />
                  <div v-if="claimForm.errors.claim_amount" class="text-sm text-red-500 mt-1">{{ claimForm.errors.claim_amount }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Incident Date *</label>
                  <input
                    type="date"
                    v-model="claimForm.incident_date"
                    :max="new Date().toISOString().split('T')[0]"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    :class="{ 'border-red-500': claimForm.errors.incident_date }"
                    required
                  />
                  <div v-if="claimForm.errors.incident_date" class="text-sm text-red-500 mt-1">{{ claimForm.errors.incident_date }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                  <textarea
                    v-model="claimForm.description"
                    rows="3"
                    placeholder="Describe what happened and why you're filing a claim..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    :class="{ 'border-red-500': claimForm.errors.description }"
                    required
                  ></textarea>
                  <div v-if="claimForm.errors.description" class="text-sm text-red-500 mt-1">{{ claimForm.errors.description }}</div>
                </div>

                <div class="flex justify-end space-x-3">
                  <button
                    type="button"
                    @click="showClaimForm = false"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    :disabled="submittingClaim"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                  >
                    <span v-if="!submittingClaim">Submit Claim</span>
                    <span v-else>Submitting...</span>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="border-t border-gray-200 pt-6">
          <div class="flex justify-between">
            <a href="/farmer/insurance" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
              Back to Insurance
            </a>

            <div class="flex space-x-3">
              <a v-if="insurance.status === 'pending'" :href="`/farmer/insurance/${insurance.id}/edit`" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Edit Policy
              </a>

              <button
                v-if="insurance.status === 'active'"
                @click="cancelPolicy"
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
              >
                Cancel Policy
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import DashboardLayout from '../../Shared/DashboardLayout.vue'

defineOptions({
  layout: DashboardLayout
})

const props = defineProps({
  insurance: Object
})

const showClaimForm = ref(false)
const submittingClaim = ref(false)

const claimForm = useForm({
  claim_amount: '',
  incident_date: '',
  description: ''
})

const submitClaim = () => {
  submittingClaim.value = true
  claimForm.post(`/farmer/insurance/${props.insurance.id}/claim`, {
    onSuccess: () => {
      showClaimForm.value = false
      claimForm.reset()
    },
    onFinish: () => {
      submittingClaim.value = false
    }
  })
}

const cancelPolicy = () => {
  if (confirm('Are you sure you want to cancel this insurance policy? This action cannot be undone.')) {
    const cancelForm = useForm({})
    cancelForm.patch(`/farmer/insurance/${props.insurance.id}/cancel`)
  }
}
</script>
