<template>
    <AppLayout title="My Reservations">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="border-b border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Your Reservations</h2>
                    </div>

                    <div v-if="reservations.length === 0"
                         class="p-12 text-center text-gray-600 dark:text-gray-300">
                        <div class="mx-auto w-16 h-16 mb-4">
                            <svg class="w-full h-full text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-lg font-medium">You have no reservations</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Your future reservations will appear here</p>
                    </div>

                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div v-for="reservation in reservations"
                             :key="reservation.table.id"
                             class="p-6 transition-colors duration-150"
                        >
                            <div class="flex items-start justify-between">
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900">
                                            <span class="text-lg font-semibold text-blue-700 dark:text-blue-300">
                                                {{ reservation.table.id }}
                                            </span>
                                        </span>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                Table {{ reservation.table.id }}
                                                <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                                                    ({{ reservation.table.places }} places)
                                                </span>
                                            </h3>
                                            <div class="flex items-center mt-1 text-sm text-gray-500 dark:text-gray-400 space-x-4">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ reservation.date }}
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ reservation.time }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="reservation.status == 0"
                                         class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Cancelled
                                    </div>
                                </div>

                                <button v-if="reservation.status != 0 && reservation.date > new Date().toISOString().split('T')[0]"
                                        @click="cancelReservation(reservation.id)"
                                        class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700
                                               hover:bg-red-200 rounded-lg transition-colors duration-200
                                               dark:bg-red-900 dark:text-red-100 dark:hover:bg-red-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { onMounted } from "vue";
import { router } from "@inertiajs/vue3";

interface Reservation {
    id: number,
    table: {
        id: number,
        places: number
    },
    date: string,
    time: string,
    status: number
}

const props = defineProps({
    reservations: {
        type: Array<Reservation>,
        required: true
    }
});

const cancelReservation = (id: number) => {
    const result = confirm('Are you sure you want to cancel this reservation?');

    if (result) {
        router.put(route('booking.cancel', { id: id }), {}, {
            preserveScroll: true,
            preserveState: false
        });
    }
}

onMounted(() => {
    router.reload({ only: ['reservations'] });
});
</script>
