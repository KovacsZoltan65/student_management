<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

/**
 * Határozza meg a ResponsiveNavLink összetevő kellékeit.
 *
 * @typedef {Object} ResponsiveNavLinkProps
 * @property {string} href - Az az URL, amelyre a linknek el kell navigálnia.
 * @property {boolean} [active=false] - Függetlenül attól, hogy a link aktív-e vagy sem.
 */
const props = defineProps({
    /**
     * Az az URL, amelyre a linknek el kell navigálnia.
     *
     * @type {string}
     * @required
     */
    href: {
        type: String,
        required: true,
    },
    /**
     * Függetlenül attól, hogy a link aktív-e vagy sem.
     *
     * @type {boolean}
     * @default false
     */
    active: {
        type: Boolean,
        default: false,
    },
});

/**
 * Számítsa ki a ResponsiveNavLink összetevő CSS-osztályait az aktív prop alapján.
 *
 * @returns {string} A ResponsiveNavLink összetevő CSS-osztályai.
 */
const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 text-start text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out'
);
</script>

<template>
    <!--
        A ResponsiveNavLink összetevőt jelölő link.

        @see https://inertiajs.com/link
    -->
    <Link
        :href="href"
        :class="classes"
    >
        <!--
            A link tartalma, amely a ResponsiveNavLink összetevőben jelenik meg.
        -->
        <slot />
    </Link>
</template>
