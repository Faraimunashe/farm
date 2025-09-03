<template>
  <Head title="Claim Details" />

  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Claim Details</h1>
        <p class="text-gray-600">Review and manage insurance claim</p>
      </div>
      <a href="/insurer/claims" class="text-blue-600 hover:text-blue-700">← Back to Claims</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <!-- Claim Header -->
      <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-start">
          <div>
            <h2 class="text-xl font-semibold text-gray-900">Claim #{{ claim.id }}</h2>
            <p class="text-gray-600">{{ claim.description }}</p>
            <div class="mt-2 flex items-center space-x-4">
              <span :class="[
                'text-sm px-3 py-1 rounded-full font-medium',
                claim.status === 'approved' ? 'bg-green-100 text-green-800' :
                claim.status === 'rejected' ? 'bg-red-100 text-red-800' :
                claim.status === 'investigating' ? 'bg-yellow-100 text-yellow-800' :
                'bg-gray-100 text-gray-800'
              ]">
                {{ claim.status.charAt(0).toUpperCase() + claim.status.slice(1) }}
              </span>
              <span class="text-sm text-gray-500">Filed on {{ new Date(claim.created_at).toLocaleDateString() }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="p-6 space-y-6">
        <!-- Claim Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-3">Claim Information</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Claim Amount</dt>
                <dd class="text-lg font-semibold text-gray-900">${{ parseFloat(claim.claim_amount || 0).toLocaleString() }}</dd>
              </div>
              <div v-if="claim.approved_amount">
                <dt class="text-sm font-medium text-gray-500">Approved Amount</dt>
                <dd class="text-lg font-semibold text-green-600">${{ parseFloat(claim.approved_amount).toLocaleString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Incident Date</dt>
                <dd class="text-sm text-gray-900">{{ new Date(claim.incident_date).toLocaleDateString() }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Claim Status</dt>
                <dd class="text-sm text-gray-900 capitalize">{{ claim.status }}</dd>
              </div>
            </dl>
          </div>

          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-3">Policy Information</h3>
            <dl class="space-y-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Policy Holder</dt>
                <dd class="text-sm text-gray-900">{{ claim.user?.name || 'N/A' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Farm</dt>
                <dd class="text-sm text-gray-900">{{ claim.farm?.name || 'N/A' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Insurance Plan</dt>
                <dd class="text-sm text-gray-900">{{ claim.insurance?.insurance_plan?.name || 'N/A' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Policy Number</dt>
                <dd class="text-sm text-gray-900">{{ claim.insurance?.policy_number || 'N/A' }}</dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Claim Description -->
        <div>
          <h3 class="text-lg font-medium text-gray-900 mb-3">Claim Description</h3>
          <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-700 whitespace-pre-line">{{ claim.description }}</p>
          </div>
        </div>

        <!-- Evidence Files -->
        <div v-if="claim.evidence_files && claim.evidence_files.length > 0">
          <h3 class="text-lg font-medium text-gray-900 mb-3">Evidence Files</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="(file, index) in claim.evidence_files" :key="index" class="bg-gray-50 p-3 rounded-lg">
              <div class="flex items-center space-x-2">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm text-gray-700">{{ file.name || `File ${index + 1}` }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Notes -->
        <div v-if="claim.notes">
          <h3 class="text-lg font-medium text-gray-900 mb-3">Claim Notes</h3>
          <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-700 whitespace-pre-line">{{ claim.notes }}</p>
          </div>
        </div>

        <!-- Investigation Notes -->
        <div v-if="claim.investigation_notes">
          <h3 class="text-lg font-medium text-gray-900 mb-3">Investigation Notes</h3>
          <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <p class="text-sm text-gray-700 whitespace-pre-line">{{ claim.investigation_notes }}</p>
          </div>
        </div>

        <!-- Rejection Reason -->
        <div v-if="claim.rejection_reason">
          <h3 class="text-lg font-medium text-gray-900 mb-3">Rejection Reason</h3>
          <div class="bg-red-50 p-4 rounded-lg border border-red-200">
            <p class="text-sm text-red-700 whitespace-pre-line">{{ claim.rejection_reason }}</p>
          </div>
        </div>

        <!-- Status Update Forms -->
        <div v-if="claim.status === 'pending'" class="space-y-4">
          <h3 class="text-lg font-medium text-gray-900">Update Claim Status</h3>

          <!-- Start Investigation -->
          <form @submit.prevent="startInvestigation" class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <h4 class="font-medium text-yellow-800 mb-3">Start Investigation</h4>
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Investigation Notes</label>
                <textarea
                  v-model="investigationForm.notes"
                  rows="3"
                  placeholder="Enter investigation notes..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                  :class="{ 'border-red-500': investigationForm.errors.notes }"
                ></textarea>
                <div v-if="investigationForm.errors.notes" class="text-sm text-red-500 mt-1">{{ investigationForm.errors.notes }}</div>
              </div>
              <button
                type="submit"
                :disabled="investigationLoading"
                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="!investigationLoading">Start Investigation</span>
                <span v-else>Starting...</span>
              </button>
            </div>
          </form>

          <!-- Approve/Reject -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <form @submit.prevent="approveClaim" class="bg-green-50 p-4 rounded-lg border border-green-200">
              <h4 class="font-medium text-green-800 mb-3">Approve Claim</h4>
              <div class="space-y-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Approved Amount</label>
                  <input
                    type="number"
                    v-model="approvalForm.approved_amount"
                    step="0.01"
                    min="0"
                    max="claim.claim_amount"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{ 'border-red-500': approvalForm.errors.approved_amount }"
                  />
                  <div v-if="approvalForm.errors.approved_amount" class="text-sm text-red-500 mt-1">{{ approvalForm.errors.approved_amount }}</div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                  <textarea
                    v-model="approvalForm.notes"
                    rows="2"
                    placeholder="Enter approval notes..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{ 'border-red-500': approvalForm.errors.notes }"
                  ></textarea>
                  <div v-if="approvalForm.errors.notes" class="text-sm text-red-500 mt-1">{{ approvalForm.errors.notes }}</div>
                </div>
                <button
                  type="submit"
                  :disabled="approvalLoading"
                  class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span v-if="!approvalLoading">Approve Claim</span>
                  <span v-else>Approving...</span>
                </button>
              </div>
            </form>

            <form @submit.prevent="rejectClaim" class="bg-red-50 p-4 rounded-lg border border-red-200">
              <h4 class="font-medium text-red-800 mb-3">Reject Claim</h4>
              <div class="space-y-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                  <textarea
                    v-model="rejectionForm.rejection_reason"
                    rows="2"
                    placeholder="Enter rejection reason..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    :class="{ 'border-red-500': rejectionForm.errors.rejection_reason }"
                    required
                  ></textarea>
                  <div v-if="rejectionForm.errors.rejection_reason" class="text-sm text-red-500 mt-1">{{ rejectionForm.errors.rejection_reason }}</div>
                </div>
                <button
                  type="submit"
                  :disabled="rejectionLoading"
                  class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span v-if="!rejectionLoading">Reject Claim</span>
                  <span v-else>Rejecting...</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Status Update for Investigating Claims -->
        <div v-if="claim.status === 'investigating'" class="space-y-4">
          <h3 class="text-lg font-medium text-gray-900">Complete Investigation</h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <form @submit.prevent="approveClaim" class="bg-green-50 p-4 rounded-lg border border-green-200">
              <h4 class="font-medium text-green-800 mb-3">Approve Claim</h4>
              <div class="space-y-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Approved Amount</label>
                  <input
                    type="number"
                    v-model="approvalForm.approved_amount"
                    step="0.01"
                    min="0"
                    max="claim.claim_amount"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{ 'border-red-500': approvalForm.errors.approved_amount }"
                  />
                  <div v-if="approvalForm.errors.approved_amount" class="text-sm text-red-500 mt-1">{{ approvalForm.errors.approved_amount }}</div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                  <textarea
                    v-model="approvalForm.notes"
                    rows="2"
                    placeholder="Enter approval notes..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{ 'border-red-500': approvalForm.errors.notes }"
                  ></textarea>
                  <div v-if="approvalForm.errors.notes" class="text-sm text-red-500 mt-1">{{ approvalForm.errors.notes }}</div>
                </div>
                <button
                  type="submit"
                  :disabled="approvalLoading"
                  class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span v-if="!approvalLoading">Approve Claim</span>
                  <span v-else>Approving...</span>
                </button>
              </div>
            </form>

            <form @submit.prevent="rejectClaim" class="bg-red-50 p-4 rounded-lg border border-red-200">
              <h4 class="font-medium text-red-800 mb-3">Reject Claim</h4>
              <div class="space-y-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                  <textarea
                    v-model="rejectionForm.rejection_reason"
                    rows="2"
                    placeholder="Enter rejection reason..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    :class="{ 'border-red-500': rejectionForm.errors.rejection_reason }"
                    required
                  ></textarea>
                  <div v-if="rejectionForm.errors.rejection_reason" class="text-sm text-red-500 mt-1">{{ rejectionForm.errors.rejection_reason }}</div>
                </div>
                <button
                  type="submit"
                  :disabled="rejectionLoading"
                  class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span v-if="!rejectionLoading">Reject Claim</span>
                  <span v-else>Rejecting...</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <div class="border-t border-gray-200 pt-6">
          <div class="flex justify-end space-x-3">
            <a href="/insurer/claims" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg">Back to Claims</a>
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
  claim: Object
})

const investigationLoading = ref(false)
const approvalLoading = ref(false)
const rejectionLoading = ref(false)

const investigationForm = useForm({
  notes: ''
})

const approvalForm = useForm({
  approved_amount: props.claim.claim_amount || 0,
  notes: ''
})

const rejectionForm = useForm({
  rejection_reason: ''
})

const startInvestigation = () => {
  investigationLoading.value = true
  investigationForm.post(`/insurer/claims/${props.claim.id}/investigation-notes`, {
    onFinish: () => {
      investigationLoading.value = false
    }
  })
}

const approveClaim = () => {
  approvalLoading.value = true
  approvalForm.patch(`/insurer/claims/${props.claim.id}/status`, {
    data: { status: 'approved' },
    onFinish: () => {
      approvalLoading.value = false
    }
  })
}

const rejectClaim = () => {
  rejectionLoading.value = true
  rejectionForm.patch(`/insurer/claims/${props.claim.id}/status`, {
    data: { status: 'rejected' },
    onFinish: () => {
      rejectionLoading.value = false
    }
  })
}
</script>
