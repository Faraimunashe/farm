<template>
    <Head title="Login" />
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
        <div class="w-full max-w-6xl mx-auto flex flex-col md:flex-row bg-white rounded-2xl shadow-lg overflow-hidden">

            <div class="w-full md:w-1/2 p-10 space-y-6">
                <div class="flex items-center space-x-2">
                    <img src="../../../assets/MSU.png" alt="Flowbite Logo" class="h-8 w-8" />
                    <span class="text-2xl font-bold text-blue-600">Midlands State University</span>
                </div>

                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900">Welcome to Canteen Admin</h2>
                    <p class="text-gray-500">
                        Don’t have an account?
                        <a href="#" class="text-blue-600 hover:underline">Fill the system access form.</a>
                    </p>
                </div>

                <form @submit.prevent="submitForm" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input
                            type="email"
                            v-model="form.email"
                            placeholder="username@staff.msu.ac.zw"
                            :class="['w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500', form.errors.email ? 'border-red-500' : 'border-gray-300']"
                        />
                        <div v-if="form.errors.email" class="text-sm text-red-500 mt-1">{{ form.errors.email }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Password</label>
                        <input
                            type="password"
                            v-model="form.password"
                            placeholder="••••••••"
                            :class="['w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500', form.errors.password ? 'border-red-500' : 'border-gray-300']"
                        />
                        <div v-if="form.errors.password" class="text-sm text-red-500 mt-1">{{ form.errors.password }}</div>
                    </div>

                    <div class="flex items-center justify-between">
                        <hr class="flex-1 border-t" />
                        <span class="px-2 text-sm text-gray-400">or</span>
                        <hr class="flex-1 border-t" />
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2">
                        <input type="checkbox" v-model="form.remember" class="form-checkbox" />
                        Remember me
                        </label>
                        <a href="#" class="text-blue-600 hover:underline">Forgot password?</a>
                    </div>

                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full bg-blue-600 text-white font-semibold py-2 rounded-md hover:bg-blue-700 transition flex items-center justify-center gap-2"
                    >
                        <span v-if="loading" class="fas fa-spinner fa-spin text-white"></span>
                        <span v-if="!loading">Sign in to your account</span>
                        <span v-else>Signing in...</span>
                    </button>
                </form>
            </div>

            <div class="hidden md:flex w-1/2 items-center justify-center bg-gray-50 p-10">
                <img src="../../../assets/chef1.png" alt="Login Illustration" class="max-w-xs" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const loading = ref(false)

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submitForm = () => {
    loading.value = true
    form.post('/login', {
        onFinish: () => {
        loading.value = false
        },
    })
}
</script>
