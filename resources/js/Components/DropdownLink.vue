<script setup>
import { Link } from '@inertiajs/vue3';

/**
 * Meghatározza a DropdownLink összetevő kellékeit.
 *
 * @typedef {Object} DropdownLinkProps
 * @property {string} href - Az az URL, amelyre a linknek el kell navigálnia.
 *                           Ez egy kötelező kellék.
 */

/**
 * Meghatározza a DropdownLink összetevő kellékeit.
 *
 * @type {DropdownLinkProps}
 */
defineProps({
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
});
</script>

<template>
    <!-- A link, amely a dropdown menüben található. -->
    <Link
        :href="href"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
    >
        <!-- A link tartalma, amely a dropdown menüben jelenik meg. -->
        <slot />
    </Link>
</template>
