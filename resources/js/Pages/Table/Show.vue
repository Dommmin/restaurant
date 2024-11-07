<template>
    <AppLayout title="Table">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Table no. {{ table.id }} - {{ table.places }} places
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div class="mb-6">
                            <label for="date" class="text-gray-700 dark:text-gray-300 font-semibold mb-2">Choose Date</label>
                            <br>
                            <input
                                v-model="form.date"
                                :min="new Date().toISOString().split('T')[0]"
                                :max="new Date(new Date().setDate(new Date().getDate() + 7)).toISOString().split('T')[0]"
                                type="date"
                                name="date"
                                id="date"
                                class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            >
                        </div>

                        <div>
                            <p class="text-gray-700 dark:text-gray-300 font-semibold mb-4">Available Slots</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div v-for="(slot, index) in availableSlots" :key="index" class="relative">
                                    <label
                                        class="block w-full cursor-pointer"
                                        :class="{
                                            'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700': form.time == slot,
                                            'bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600': form.time !== slot
                                        }"
                                    >
                                        <input
                                            v-model="form.time"
                                            type="radio"
                                            :value="slot"
                                            name="slot"
                                            class="hidden"
                                        />
                                        <div class="px-4 py-3 text-center rounded-lg border transition-all duration-200">
                                            {{ slot }}
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <InputError :message="form.errors.time" class="mt-2" />
                            <button
                                type="submit"
                                class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200 disabled:opacity-50"
                                :disabled="form.processing"
                            >
                                {{ form.processing ? 'Submitting...' : 'Submit' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import {onMounted, watch} from "vue";
import { router, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

interface TableProps {
    table: {
        id: number;
        places: number;
    };
    availableSlots: string[];
    date: string;
}

const props = defineProps<TableProps>();

const form = useForm({
    date: props.date,
    time: ''
});

watch(() => form.date, (newDate) => {
    if (newDate) {
        router.reload({
            data: {
                date: newDate
            },
        });
    }
});

const submit = () => {
    form.post(route('booking.store', { table: props.table.id }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset('time');
        }
    });
};

onMounted(() => {
    router.reload({ only: ['availableSlots'] });
})
</script>
