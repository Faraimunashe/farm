<template>
    <Head title="Register" />
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-blue-50 px-4 py-8">
        <div class="w-full max-w-4xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Join Our Farm Management Platform</h1>
                <p class="text-gray-600">Create your account and start managing your agricultural business</p>
            </div>

            <!-- Error Message -->
            <div v-if="$page.props.flash && $page.props.flash.error" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ $page.props.flash.error }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                            <input
                                type="text"
                                v-model="form.name"
                                placeholder="Enter your full name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                :class="{ 'border-red-500': form.errors.name }"
                            />
                            <div v-if="form.errors.name" class="text-sm text-red-500 mt-1">{{ form.errors.name }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                            <input
                                type="email"
                                v-model="form.email"
                                placeholder="Enter your email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                :class="{ 'border-red-500': form.errors.email }"
                            />
                            <div v-if="form.errors.email" class="text-sm text-red-500 mt-1">{{ form.errors.email }}</div>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">I am a...</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div @click="selectRole('farmer')" class="relative cursor-pointer">
                                <input type="radio" v-model="form.role" value="farmer" class="sr-only" />
                                <div class="p-4 border-2 rounded-lg text-center transition-all duration-200"
                                     :class="form.role === 'farmer' ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-200 hover:border-gray-300'">
                                    <i class="fa fa-leaf text-2xl mb-2"></i>
                                    <div class="font-semibold">Farmer</div>
                                    <div class="text-sm opacity-75">Manage your farm operations</div>
                                </div>
                            </div>

                            <div @click="selectRole('lender')" class="relative cursor-pointer">
                                <input type="radio" v-model="form.role" value="lender" class="sr-only" />
                                <div class="p-4 border-2 rounded-lg text-center transition-all duration-200"
                                     :class="form.role === 'lender' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 hover:border-gray-300'">
                                    <i class="fa fa-dollar-sign text-2xl mb-2"></i>
                                    <div class="font-semibold">Lender</div>
                                    <div class="text-sm opacity-75">Provide financial services</div>
                                </div>
                            </div>

                            <div @click="selectRole('insurer')" class="relative cursor-pointer">
                                <input type="radio" v-model="form.role" value="insurer" class="sr-only" />
                                <div class="p-4 border-2 rounded-lg text-center transition-all duration-200"
                                     :class="form.role === 'insurer' ? 'border-purple-500 bg-purple-50 text-purple-700' : 'border-gray-200 hover:border-gray-300'">
                                    <i class="fa fa-shield-alt text-2xl mb-2"></i>
                                    <div class="font-semibold">Insurer</div>
                                    <div class="text-sm opacity-75">Offer insurance solutions</div>
                                </div>
                            </div>
                        </div>
                        <div v-if="form.errors.role" class="text-sm text-red-500 mt-1">{{ form.errors.role }}</div>
                    </div>

                    <!-- Password Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <input
                                type="password"
                                v-model="form.password"
                                placeholder="Create a strong password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                :class="{ 'border-red-500': form.errors.password }"
                            />
                            <div v-if="form.errors.password" class="text-sm text-red-500 mt-1">{{ form.errors.password }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                            <input
                                type="password"
                                v-model="form.password_confirmation"
                                placeholder="Confirm your password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            />
                        </div>
                    </div>

                    <!-- Farmer Fields -->
                    <div v-if="form.role === 'farmer'" class="space-y-6 p-6 bg-green-50 rounded-lg border border-green-200">
                        <h3 class="text-lg font-semibold text-green-800 mb-4">Farm Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Farm Name</label>
                                <input
                                    type="text"
                                    v-model="form.farm_name"
                                    placeholder="Enter farm name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.farm_name }"
                                />
                                <div v-if="form.errors.farm_name" class="text-sm text-red-500 mt-1">{{ form.errors.farm_name }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Farm Size (Hectares)</label>
                                <input
                                    type="number"
                                    v-model="form.farm_size"
                                    placeholder="Enter farm size"
                                    step="0.01"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.farm_size }"
                                />
                                <div v-if="form.errors.farm_size" class="text-sm text-red-500 mt-1">{{ form.errors.farm_size }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Farm Type</label>
                                <select
                                    v-model="form.farm_type"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.farm_type }"
                                >
                                    <option value="">Select farm type</option>
                                    <option value="crops">Crops</option>
                                    <option value="livestock">Livestock</option>
                                    <option value="mixed">Mixed</option>
                                </select>
                                <div v-if="form.errors.farm_type" class="text-sm text-red-500 mt-1">{{ form.errors.farm_type }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number</label>
                                <input
                                    type="text"
                                    v-model="form.farm_contact"
                                    placeholder="Enter contact number"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.farm_contact }"
                                />
                                <div v-if="form.errors.farm_contact" class="text-sm text-red-500 mt-1">{{ form.errors.farm_contact }}</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Farm Address</label>
                            <textarea
                                v-model="form.farm_address"
                                placeholder="Enter farm address"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                :class="{ 'border-red-500': form.errors.farm_address }"
                            ></textarea>
                            <div v-if="form.errors.farm_address" class="text-sm text-red-500 mt-1">{{ form.errors.farm_address }}</div>
                        </div>
                    </div>

                    <!-- Lender Fields -->
                    <div v-if="form.role === 'lender'" class="space-y-6 p-6 bg-blue-50 rounded-lg border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-800 mb-4">Lender Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Institution Name</label>
                                <input
                                    type="text"
                                    v-model="form.lender_name"
                                    placeholder="Enter institution name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.lender_name }"
                                />
                                <div v-if="form.errors.lender_name" class="text-sm text-red-500 mt-1">{{ form.errors.lender_name }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number</label>
                                <input
                                    type="text"
                                    v-model="form.lender_contact"
                                    placeholder="Enter contact number"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.lender_contact }"
                                />
                                <div v-if="form.errors.lender_contact" class="text-sm text-red-500 mt-1">{{ form.errors.lender_contact }}</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Institution Address</label>
                            <textarea
                                v-model="form.lender_address"
                                placeholder="Enter institution address"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :class="{ 'border-red-500': form.errors.lender_address }"
                            ></textarea>
                            <div v-if="form.errors.lender_address" class="text-sm text-red-500 mt-1">{{ form.errors.lender_address }}</div>
                        </div>
                    </div>

                    <!-- Insurer Fields -->
                    <div v-if="form.role === 'insurer'" class="space-y-6 p-6 bg-purple-50 rounded-lg border border-purple-200">
                        <h3 class="text-lg font-semibold text-purple-800 mb-4">Insurance Company Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Company Name</label>
                                <input
                                    type="text"
                                    v-model="form.insurer_name"
                                    placeholder="Enter company name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.insurer_name }"
                                />
                                <div v-if="form.errors.insurer_name" class="text-sm text-red-500 mt-1">{{ form.errors.insurer_name }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number</label>
                                <input
                                    type="text"
                                    v-model="form.insurer_contact"
                                    placeholder="Enter contact number"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.insurer_contact }"
                                />
                                <div v-if="form.errors.insurer_contact" class="text-sm text-red-500 mt-1">{{ form.errors.insurer_contact }}</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Company Address</label>
                            <textarea
                                v-model="form.insurer_address"
                                placeholder="Enter company address"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                :class="{ 'border-red-500': form.errors.insurer_address }"
                            ></textarea>
                            <div v-if="form.errors.insurer_address" class="text-sm text-red-500 mt-1">{{ form.errors.insurer_address }}</div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6">
                        <button
                            type="submit"
                            :disabled="loading"
                            class="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white font-semibold py-4 rounded-lg hover:from-green-700 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2"
                        >
                            <div v-if="loading" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                            <span v-if="!loading">Create Account</span>
                            <span v-else>Creating Account...</span>
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-gray-600">
                            Already have an account?
                            <a href="/login" class="text-green-600 hover:text-green-700 font-semibold">Sign in here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const loading = ref(false)

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
    // Farmer fields
    farm_name: '',
    farm_address: '',
    farm_contact: '',
    farm_size: '',
    farm_type: '',
    // Lender fields
    lender_name: '',
    lender_address: '',
    lender_contact: '',
    // Insurer fields
    insurer_name: '',
    insurer_address: '',
    insurer_contact: '',
})

const selectRole = (role) => {
    form.role = role
}

const submitForm = () => {
    loading.value = true
    form.post('/register', {
        onFinish: () => {
            loading.value = false
        },
    })
}
</script>
