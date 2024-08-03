<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';


/**
 * Kellékek a NavLink összetevőhöz.
 *
 * @typedef {Object} NavLinkProps
 * @property {string} href - Az az URL, amelyre a hivatkozásra kattintva navigálhat.
 * @property {boolean} [active=false] - Azt jelzi, hogy a kapcsolat jelenleg aktív-e.
 */

/**
 * Meghatározza a NavLink összetevő kellékeit.
 *
 * @type {NavLinkProps}
 */
const props = defineProps({
    /**
     * Az az URL, amelyre a hivatkozásra kattintva navigálhat.
     *
     * @type {string}
     * @required
     */
    href: {
        type: String,
        required: true,
    },
    /**
     * Azt jelzi, hogy a kapcsolat jelenleg aktív-e.
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
 * Kiszámítja a NavLink összetevő CSS-osztályait.
 *
 * @returns {string} A NavLink összetevőre alkalmazandó CSS-osztályok.
 */
const classes = computed(() =>
    // Ha a hivatkozás aktív, alkalmazza az aktív CSS-osztályokat.
    // Ellenkező esetben alkalmazza az alapértelmezett CSS-osztályokat.
    props.active
        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out' // Active CSS classes
        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out' // Default CSS classes
);
</script>

<template>
    <!-- 
        Összetevő: NavLink
        Leírás: Egyéni navigációs hivatkozás összetevő, amely különböző CSS-osztályokat alkalmaz attól függően, hogy a hivatkozás aktív-e vagy sem.
        Kellékek:
            - href: Az az URL, amelyre a hivatkozásra kattintva navigálhat. (kívánt)
            - aktív: Azt jelzi, hogy a kapcsolat jelenleg aktív-e. (alapértelmezett: hamis)
    -->
    <!-- 
        Az egyéni navigációs hivatkozás összetevő.
        The URL to navigate to when the link is clicked.
        The CSS classes to apply to the link.
    -->
    <Link
        :href="href" 
        :class="classes" 
    >
        <!-- A link tartalma. -->
        <slot />
    </Link>
</template>
