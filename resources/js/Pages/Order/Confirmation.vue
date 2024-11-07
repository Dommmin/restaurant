<template>
    <AppLayout title="Cart">
        <div class="flex items-center justify-center min-h-screen py-12">
            <div class="max-w-4xl mx-auto px-6 sm:px-8 lg:px-10">
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6 space-y-8">
                    <div class="flex justify-center items-center space-x-4">
                        <div class="h-12 w-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Twoje zamówienie zostało złożone</h2>
                            <p class="text-gray-500 dark:text-gray-400">Dziękujemy za Twoje zamówienie!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
</script>
