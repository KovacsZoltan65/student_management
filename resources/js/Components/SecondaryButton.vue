<script setup>
/**
 * Props meghatározást a SecondaryButton komponenshez.
 *
 * @typedef {Object} SecondaryButtonProps
 * @property {string} [type='button'] - A gomb típusa.
 */

/**
 * Defines the props that the SecondaryButton component accepts.
 *
 * @type {SecondaryButtonProps}
 */
defineProps({
    /**
     * A gomb típusa. Alapértelmezésben a 'gomb'.
     *
     * @type {string}
     * @default 'button'
     */
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <!--
        Ez a másodlagos gomb komponense, amely stílusos megjelenést biztosít.
        A gomb szürke háttérrel és fehér szöveggel rendelkezik.
        A gombnak van egy átütő effektusa is, amely a háttérszínt kis mértékben sötétebb szürkére változtatja.
        A gombra kattintás alapértelmezés szerint nem indít semmilyen műveletet.
    -->
    <button
        :type="type"
        class="
            inline-flex 
            items-center 
            px-4 
            py-2 
            bg-white 
            border 
            border-gray-300 
            rounded-md 
            font-semibold 
            text-xs 
            text-gray-700 
            uppercase 
            tracking-widest 
            shadow-sm 
            hover:bg-gray-50 
            focus:outline-none 
            focus:ring-2 
            focus:ring-indigo-500 
            focus:ring-offset-2 
            disabled:opacity-25 
            transition 
            ease-in-out 
            duration-150"
    >
        <!--
            A nyílás a gomb tartalmának megjelenítésére szolgál.
            Ez lehetővé teszi a dinamikus tartalom átadását a gombnak.
        -->
        <slot />
    </button>
</template>
