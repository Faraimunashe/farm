<template>
  <Head title="Loan Details" />

  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Loan Details</h1>
        <p class="text-gray-600">Review loan application and manage loan status</p>
      </div>
      <div class="flex space-x-3">
        <a :href="`/lender/loans/${loan.id}/transactions`" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600">
          <i class="fa fa-exchange-alt mr-2"></i>View Transactions
        </a>
        <a href="/lender/loans" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
          <i class="fa fa-arrow-left mr-2"></i>Back to Loans
        </a>
      </div>
    </div>

    <!-- Loan Status Banner -->
    <div :class="[
      'rounded-lg p-4 border-l-4',
      getStatusBannerClass(loan.status)
    ]">
      <div class="flex items-center">
        <i :class="getStatusIcon(loan.status) + ' text-xl mr-3'"></i>
        <div>
          <h3 class="text-lg font-semibold">Loan Status: {{ loan.status?.toUpperCase() }}</h3>
          <p class="text-sm opacity-90">
            {{ getStatusDescription(loan.status) }}
          </p>
        </div>
      </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left Column - Loan Details -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Basic Loan Information -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Loan Information</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Loan Amount</label>
              <p class="text-2xl font-bold text-gray-900">${{ loan.amount?.toLocaleString() || 0 }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Purpose</label>
              <p class="text-lg text-gray-900">{{ loan.purpose }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Interest Rate</label>
              <p class="text-lg text-gray-900">{{ loan.interest_rate || 0 }}%</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Term</label>
              <p class="text-lg text-gray-900">{{ loan.term_months || 0 }} months</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Application Date</label>
              <p class="text-lg text-gray-900">{{ new Date(loan.created_at).toLocaleDateString() }}</p>
            </div>
            <div v-if="loan.approved_at">
              <label class="block text-sm font-medium text-gray-700">Approved Date</label>
              <p class="text-lg text-gray-900">{{ new Date(loan.approved_at).toLocaleDateString() }}</p>
            </div>
          </div>
        </div>

        <!-- Borrower Information -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Borrower Information</h3>
          <div class="flex items-center space-x-4 mb-4">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
              <span class="text-blue-600 font-semibold text-2xl">{{ loan.user?.name?.charAt(0)?.toUpperCase() || 'U' }}</span>
            </div>
            <div>
              <h4 class="text-xl font-semibold text-gray-900">{{ loan.user?.name || 'Unknown User' }}</h4>
              <p class="text-gray-600">{{ loan.user?.email || 'No email' }}</p>
              <p class="text-gray-500">{{ loan.user?.phone || 'No phone' }}</p>
            </div>
          </div>
        </div>

        <!-- Farm Information -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Farm Information</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Farm Name</label>
              <p class="text-lg text-gray-900">{{ loan.user?.farms?.[0]?.name || 'No Farm' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Farm Size</label>
              <p class="text-lg text-gray-900">{{ loan.user?.farms?.[0]?.size || 0 }} acres</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Location</label>
              <p class="text-lg text-gray-900">{{ loan.user?.farms?.[0]?.location || 'No location' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Farm Type</label>
              <p class="text-lg text-gray-900">{{ loan.user?.farms?.[0]?.type || 'No type' }}</p>
            </div>
          </div>
        </div>

        <!-- Action Buttons for Pending Loans -->
        <div v-if="loan.status === 'pending'" class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Loan Decision</h3>
          <div class="flex space-x-4">
            <button @click="showApproveModal = true" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600">
              <i class="fa fa-check mr-2"></i>Approve Loan
            </button>
            <button @click="showRejectModal = true" class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600">
              <i class="fa fa-times mr-1"></i>Reject Loan
            </button>
          </div>
        </div>

        <!-- Status Update for Active Loans -->
        <div v-if="loan.status === 'active'" class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Loan Status</h3>
          <div class="flex space-x-4">
            <select v-model="newStatus" class="border border-gray-300 rounded-lg px-3 py-2">
              <option value="active">Active</option>
              <option value="suspended">Suspended</option>
              <option value="defaulted">Defaulted</option>
              <option value="completed">Completed</option>
            </select>
            <button @click="updateStatus" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
              Update Status
            </button>
          </div>
        </div>
      </div>

      <!-- Right Column - Actions and Summary -->
      <div class="space-y-6">
        <!-- Loan Summary Card -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Summary</h3>
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-gray-600">Principal Amount:</span>
              <span class="font-semibold">${{ loan.amount?.toLocaleString() || 0 }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Interest Rate:</span>
              <span class="font-semibold">{{ loan.interest_rate || 0 }}%</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Monthly Payment:</span>
              <span class="font-semibold">${{ calculateMonthlyPayment() }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Total Interest:</span>
              <span class="font-semibold">${{ calculateTotalInterest() }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Total Repayment:</span>
              <span class="font-semibold">${{ calculateTotalRepayment() }}</span>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
          <div class="text-center py-4">
            <span class="text-gray-500">No recent activity</span>
          </div>
          <div class="mt-4">
            <a :href="`/lender/farms/${loan.user?.farms?.[0]?.id}/transactions`" class="text-blue-600 hover:text-blue-800 text-sm">
              View farm transactions →
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Approve Loan Modal -->
    <div v-if="showApproveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Approve Loan</h3>
          <p class="text-sm text-gray-500 mb-4">Are you sure you want to approve this loan application?</p>
          <div class="flex justify-center space-x-4">
            <button @click="approveLoan" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
              Yes, Approve
            </button>
            <button @click="showApproveModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Reject Loan Modal -->
    <div v-if="showRejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Loan</h3>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
            <textarea v-model="rejectionReason" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Please provide a reason for rejection..."></textarea>
          </div>
          <div class="flex justify-center space-x-4">
            <button @click="rejectLoan" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
              Reject Loan
            </button>
            <button @click="showRejectModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import LenderLayout from '../../Shared/LenderLayout.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

defineOptions({
  layout: LenderLayout
})

const props = defineProps({
  loan: Object
})

const showApproveModal = ref(false)
const showRejectModal = ref(false)
const rejectionReason = ref('')
const newStatus = ref('active')

// Inertia forms
const approveForm = useForm({})
const rejectForm = useForm({
  rejection_reason: ''
})
const statusForm = useForm({
  status: ''
})

const getStatusBannerClass = (status) => {
  const classes = {
    'pending': 'bg-yellow-50 border-yellow-400 text-yellow-800',
    'active': 'bg-green-50 border-green-400 text-green-800',
    'completed': 'bg-blue-50 border-blue-400 text-blue-800',
    'defaulted': 'bg-red-50 border-red-400 text-red-800',
    'rejected': 'bg-gray-50 border-gray-400 text-gray-800'
  }
  return classes[status] || 'bg-gray-50 border-gray-400 text-gray-800'
}

const getStatusIcon = (status) => {
  const icons = {
    'pending': 'fa fa-clock text-yellow-600',
    'active': 'fa fa-check-circle text-green-600',
    'completed': 'fa fa-check-double text-blue-600',
    'defaulted': 'fa fa-exclamation-triangle text-red-600',
    'rejected': 'fa fa-times-circle text-gray-600'
  }
  return icons[status] || 'fa fa-question-circle text-gray-600'
}

const getStatusDescription = (status) => {
  const descriptions = {
    'pending': 'This loan application is awaiting your review and decision.',
    'active': 'This loan is currently active and being serviced.',
    'completed': 'This loan has been fully repaid and completed.',
    'defaulted': 'This loan is in default and requires attention.',
    'rejected': 'This loan application was rejected.'
  }
  return descriptions[status] || 'Status description not available.'
}

const calculateMonthlyPayment = () => {
  if (!props.loan.amount || !props.loan.interest_rate || !props.loan.term_months) return 0
  const principal = props.loan.amount
  const monthlyRate = props.loan.interest_rate / 100 / 12
  const months = props.loan.term_months

  if (monthlyRate === 0) return principal / months

  const payment = principal * (monthlyRate * Math.pow(1 + monthlyRate, months)) / (Math.pow(1 + monthlyRate, months) - 1)
  return Math.round(payment)
}

const calculateTotalInterest = () => {
  const monthlyPayment = calculateMonthlyPayment()
  const totalRepayment = monthlyPayment * props.loan.term_months
  return Math.round(totalRepayment - props.loan.amount)
}

const calculateTotalRepayment = () => {
  const monthlyPayment = calculateMonthlyPayment()
  return Math.round(monthlyPayment * props.loan.term_months)
}

const approveLoan = () => {
  approveForm.put(`/lender/loans/${props.loan.id}/approve`, {
    onSuccess: () => {
      showApproveModal.value = false
    }
  })
}

const rejectLoan = () => {
  if (!rejectionReason.value.trim()) {
    alert('Please provide a rejection reason')
    return
  }

  rejectForm.rejection_reason = rejectionReason.value
  rejectForm.put(`/lender/loans/${props.loan.id}/reject`, {
    onSuccess: () => {
      showRejectModal.value = false
      rejectionReason.value = ''
    }
  })
}

const updateStatus = () => {
  statusForm.status = newStatus.value
  statusForm.put(`/lender/loans/${props.loan.id}/status`, {
    onSuccess: () => {
      // Status updated successfully
    }
  })
}
</script>
