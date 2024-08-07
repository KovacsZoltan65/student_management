<script setup>
import { router } from "@inertiajs/vue3";

/**
 * Alkatrész kellékek.
 *
 * @typedef {Object} Props
 * @property {Object} data - Az adatobjektum.
 * @property {Function} pageNumberUpdated - Az oldalszám frissítésének funkciója.
 */

/**
 * Határozza meg az összetevő kellékeit.
 *
 * @type {Props}
 */
defineProps({
    data: {
        type: Object,
    },
    pageNumberUpdated: {
        type: Function,
        required: true,
    },
});

/**
 * Frissítse az oldalszámot, és navigáljon az új oldalra.
 *
 * @param {Object} link - Az URL-t tartalmazó linkobjektum.
 */
const updatePageNumber = (link) => {
    // Vegye ki az oldalszámot az URL-ből.
    let pageNumber = link.url.split("=")[1];

    // Navigáljon az új oldalra a görgetés pozíciójának megőrzése mellett.
    router.visit(`/students?&page=${pageNumber}`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="max-w-7xl mx-auto py-6">
        <div class="max-w-none mx-auto">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    
                    <div class="flex-1 flex justify-between sm:hidden" />

                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        
                        <div>
                            <p class="text-sm text-gray-700">
                                {{ $t(
                                    'paginate', 
                                    {
                                        a: data.meta.from, 
                                        b: data.meta.to, 
                                        c: data.meta.total
                                    }
                                ) 
                                }}
                            </p>
                        </div>

                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                
                                <button v-for="(link, index) in data.meta.links"
                                        :key="index"
                                        @click.prevent="pageNumberUpdated(link)"
                                        :disabled="link.active || !link.url"
                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                        :class="{
                                            'z-10 bg-indigo-50 border-indigo-500 text-indigo-600':
                                                link.active,
                                            'bg-white border-gray-300 text-gray-500 hover:bg-gray-50':
                                                !link.active,
                                        }">
                                    <span v-html="link.label"></span>
                                </button>

                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
