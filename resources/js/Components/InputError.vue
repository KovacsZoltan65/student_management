<script setup>

/**
 * Props az InputError összetevőhöz.
 *
 * @typedef {Object} InputErrorProps
 * @property {string} message - A megjelenítendő hibaüzenet.
 */

/**
 * Props az InputError összetevőhöz.
 *
 * @type {InputErrorProps}
 */
defineProps({
    /**
     * A megjelenítendő hibaüzenet.
     *
     * @type {string}
     */
    message: {
        type: String,
    },
});
</script>

<template>
    <!-- Az InputError összetevő hibaüzenetét tartalmazó <div> eleme. -->
    <div v-show="message">
        <!-- A hibaüzenetet tartalmazó <p> elem. -->
        <p class="text-sm text-red-600">
            <!-- A hibaüzenetet jeleníti meg. -->
            <!-- A `message` prop értéke lesz a hibaüzenet szövege. -->
            {{ message }}
        </p>
    </div>
</template>
