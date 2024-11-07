<template>
    <AppLayout title="Menu">
        <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <!-- Category sections -->
                <div class="space-y-8">
                    <div v-for="(category, name) in categories" :key="category.id"
                         class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl">
                        <!-- Category header -->
                        <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ name }}</h2>
                        </div>

                        <!-- Food items list -->
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            <li v-for="food in category" :key="food.id"
                                class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <div class="flex items-start justify-between">
                                    <div class="flex-grow">
                                        <div class="flex justify-between items-start mb-1">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ food.name }}
                                            </h3>
                                            <span class="ml-4 text-lg font-bold text-green-600 dark:text-green-400">
                                                {{ food.price / 100 }} zł
                                            </span>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                                            {{ food.ingredients.join(', ') }}
                                        </p>
                                    </div>

                                    <!-- Add to cart button -->
                                    <button @click="addToOrder(food.id)"
                                            class="ml-4 inline-flex items-center justify-center p-2
                                                   bg-blue-600 hover:bg-blue-700 text-white rounded-lg
                                                   transition-colors duration-200 focus:outline-none focus:ring-2
                                                   focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a1 1 0 01-1-1v-6H3a1 1 0 110-2h6V3a1 1 0 112 0v6h6a1 1 0 110 2h-6v6a1 1 0 01-1 1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from "@inertiajs/vue3";

interface Food {
    id: number;
    name: string;
    description: string;
    price: number;
    ingredients: string[];
}

const props = defineProps({
    categories: {
        type: Object,
        required: true
    }
});

const addToOrder = (id: number) => {
    router.post(route('cart.store'), { id: id }, {
        preserveScroll: true,
        preserveState: false
    });
};
</script>
