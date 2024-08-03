<script setup>

/**
 * Kellékek az InputLabel komponenshez.
 *
 * @typedef {Object} InputLabelProps
 * @property {string} [value] - A címke értéke.
 */

/**
 * Az InputLabel komponens kellékei.
 *
 * @type {InputLabelProps}
 */
defineProps({
    /**
     * A címke értéke.
     *
     * @type {string|undefined}
     */
    value: {
        type: String,
    },
});
</script>

<template>
    <!--
     * A címke címke.
     *
     * Ez a komponens egy felhasználható címke, amely a forrásoldalás
     * komponenseket tartalmazza.
     *
     * @see {@link Input}
     * @see {@link TextInput}
     * @see {@link Checkbox}
     *
     * @slot {default} A címke szövege.
     *
     * @props {
     *     @prop {value} A címke értéke.
     * }
     -->
    <label
        class="block font-medium text-sm text-gray-700"
    >
        <!--
         * A címke szövege.
         *
         * Ha van értéke akkor ezt a kell használni.
         * Ha nincs értéke akkor a forrásoldalás komponens szövege
         * használni.
         -->
        <span v-if="value">
            {{ value }}
        </span>
        <span v-else>
            <!-- forrásoldalás komponens szövege -->
            <slot />
        </span>
    </label>
</template>
