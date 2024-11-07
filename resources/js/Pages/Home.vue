<template>
    <AppLayout title="Home">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Table List</h2>
                    <div class="my-4">
                        <select v-model="place" class="select select-primary w-full max-w-xs">
                            <option value="">Filter by places</option>
                            <option v-for="p in places" :value="p">{{ p }}</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Link
                            v-for="table in tables"
                            :key="table.id"
                            :href="route('table', table.id)"
                            class="block"
                        >
                            <div
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 shadow-md hover:shadow-lg transition-all duration-300 hover:scale-102 hover:bg-gray-100 dark:hover:bg-gray-600"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- Ikona stolika -->
                                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                                            <svg
                                                class="w-6 h-6 text-blue-600 dark:text-blue-300"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"
                                                />
                                            </svg>
                                        </div>

                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                                Table {{ table.id }}
                                            </h3>
                                            <p class="text-gray-600 dark:text-gray-300">
                                                Places: {{ table.places }}
                                            </p>
                                        </div>
                                    </div>

<!--                                    &lt;!&ndash; Status/Akcje &ndash;&gt;-->
<!--                                    <div class="flex items-center">-->
<!--                                        <span-->
<!--                                            class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300"-->
<!--                                        >-->
<!--                                            Dostępny-->
<!--                                        </span>-->
<!--                                    </div>-->
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, router} from '@inertiajs/vue3';
import {ref, watch} from "vue";

interface Table {
    id: number;
    places: number;
}

const props = defineProps({
    tables: Array<Table>,
    places: Array,
    filters: Object
});

const place = ref(props.filters.place ?? '');

watch(() => place.value, (newValue) => {
    router.get(route('home'), newValue ? { place: newValue } : {}, {
        replace: true,
        preserveState: true,
    });
});
</script>
