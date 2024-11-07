<template>
    <AppLayout title="My Orders">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="border-b border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Your Orders</h2>
                    </div>

                    <div v-if="orders.length === 0" class="p-6 text-center">
                        <p class="text-gray-500 dark:text-gray-400">You don't have any orders yet.</p>
                    </div>

                    <div v-else v-for="order in orders" :key="order.id" class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-800 dark:text-white">Order #{{ order.id }}</h3>
                            <p class="text-gray-800 dark:text-white font-medium">Total: {{ formatPrice(total(order)) }}</p>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <div v-for="item in order.items" :key="item.id" class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-800 dark:text-white font-medium">{{ item.food.name }}</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Quantity: {{ item.quantity }}</p>
                                </div>
                                <p class="text-gray-800 dark:text-white font-medium">{{ formatPrice(item.price * item.quantity) }}</p>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Ordered on: {{ order.created_at }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

interface Order {
    id: number;
    items: {
        id: number;
        food: {
            name: string;
            price: number;
        };
        quantity: number;
        price: number;
    }[];
    created_at: string;
}

const props = defineProps({
    orders: {
        type: Array<Order>,
        required: true
    }
});

const formatPrice = (price: number) => `${(price / 100).toFixed(2)} zł`;

const total = (order: Order) => {
    return order.items.reduce((sum, item) => {
        return sum + (item.price * item.quantity);
    }, 0);
}

onMounted(() => {
    router.reload({ only: ['orders'] });
});
</script>
