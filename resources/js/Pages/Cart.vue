<template>
    <AppLayout title="Cart">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <!-- Empty cart message -->
                    <div v-if="!cartItems?.length" class="flex flex-col gap-4 items-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-xl">Your cart is empty</p>
                    </div>

                    <!-- Cart items -->
                    <div v-else class="space-y-6">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            <li v-for="item in cartItems" :key="item.id"
                                class="flex flex-col sm:flex-row sm:items-center justify-between py-4 space-y-2 sm:space-y-0">
                                <!-- Item details -->
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <span class="text-lg font-medium">{{ item.food.name }}</span>
                                        <span class="text-gray-600 dark:text-gray-400 ml-2">
                                            ({{ formatPrice(item.food.price) }} zł)
                                        </span>
                                    </div>
                                </div>

                                <!-- Quantity controls and price -->
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center space-x-2">
                                        <button
                                            :disabled="item.quantity <= 1"
                                            @click="decrementQuantity(item.id)"
                                            class="w-8 h-8 flex items-center justify-center rounded-full
                                                   bg-gray-100 hover:bg-gray-200 dark:bg-gray-700
                                                   dark:hover:bg-gray-600 transition-colors duration-200"
                                            :class="{ 'opacity-50 cursor-not-allowed': item.quantity <= 1 }"
                                        >
                                            <span class="text-lg font-medium">-</span>
                                        </button>
                                        <span class="w-8 text-center">{{ item.quantity }}</span>
                                        <button
                                            @click="incrementQuantity(item.id)"
                                            class="w-8 h-8 flex items-center justify-center rounded-full
                                                   bg-gray-100 hover:bg-gray-200 dark:bg-gray-700
                                                   dark:hover:bg-gray-600 transition-colors duration-200"
                                        >
                                            <span class="text-lg font-medium">+</span>
                                        </button>
                                    </div>

                                    <!-- Item total price -->
                                    <div class="w-24 text-right">
                                        <span class="font-medium">
                                            {{ formatPrice(item.food.price * item.quantity) }} zł
                                        </span>
                                    </div>

                                    <!-- Remove button -->
                                    <button @click="removeFromCart(item.id)"
                                            class="text-red-500 hover:text-red-700 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </li>
                        </ul>

                        <!-- Table number input -->
                        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <div class="max-w-xs ml-auto">
                                <input
                                    v-model="form.table_id"
                                    type="number"
                                    min="1"
                                    max="99"
                                    required
                                    placeholder="Podaj numer stolika"
                                    class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border
                                           border-gray-300 dark:border-gray-600 rounded-md shadow-sm
                                           focus:outline-none focus:ring-2 focus:ring-blue-500
                                           focus:border-blue-500 sm:text-sm"
                                />
                            </div>
                            <InputError :message="form.errors.table_id" class="max-w-xs mt-2 ml-auto" />
                        </div>

                        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <div class="max-w-xs ml-auto">
                                <input
                                    v-model="form.email"
                                    type="email"
                                    required
                                    placeholder="Podaj e-mail"
                                    class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border
                                           border-gray-300 dark:border-gray-600 rounded-md shadow-sm
                                           focus:outline-none focus:ring-2 focus:ring-blue-500
                                           focus:border-blue-500 sm:text-sm"
                                />
                            </div>
                            <InputError :message="form.errors.email" class="max-w-xs mt-2 ml-auto" />
                        </div>

                        <!-- Order summary -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-6">
                            <div class="flex justify-between items-center py-2 text-lg font-bold">
                                <span>Razem:</span>
                                <span>{{ formatPrice(total) }} zł</span>
                            </div>
                        </div>

                        <!-- Place order button -->
                        <div class="mt-6">
                            <button
                                @click="placeOrder"
                                class="w-full bg-green-600 text-white font-medium
                                       py-3 px-4 rounded-lg transition-colors duration-200
                                       focus:outline-none focus:ring-2 focus:ring-offset-2
                                       focus:ring-green-500"
                            >
                               Złóż zamówienie
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import {router, useForm, usePage} from "@inertiajs/vue3";
import {computed, onMounted} from 'vue';
import InputError from "@/Components/InputError.vue";

interface Food {
    name: string;
    price: number;
}

interface CartItem {
    food: Food;
    id: number;
    quantity: number;
}

const props = defineProps({
    cartItems: {
        type: Array as () => CartItem[],
        required: true,
        default: () => []
    },
    tableId: {
        type: [Number, null],
        required: true
    },
});

const form = useForm({
    table_id: props.tableId,
    email: usePage().props.auth?.user?.email || '',
});

const total = computed(() => {
    return props.cartItems.reduce((sum, item) => {
        return sum + (item.food.price * item.quantity);
    }, 0);
});

const formatPrice = (price: number): string => {
    return (price / 100).toFixed(2);
};

const removeFromCart = (id: number) => {
    router.delete(route('cart.destroy', { cartItem: id }), {
        preserveScroll: true,
        preserveState: false
    });
};

const incrementQuantity = (id: number) => {
    router.put(route('cart.increment', { cartItem: id }), {
        preserveScroll: true,
        preserveState: false
    });
};

const decrementQuantity = (id: number) => {
    router.put(route('cart.decrement', { cartItem: id }), {
        preserveScroll: true,
        preserveState: false
    });
};

const placeOrder = () => {
    form.post(route('order.store'), {
        preserveScroll: true,
        preserveState: false,
        preserveUrl: true,
        onSuccess: (response) => {
            window.location.href = response.props.flash.info;
        }
    });
};

onMounted(() => {
    router.reload({ only: ['cartItems'] });
})
</script>
