<script setup>
import { computed } from 'vue';


/**
 * Eseményt vált ki, amikor a bejelölt állapot frissül.
 *
 * Az esemény neve 'update:checked', és a 'checked' prop új értékével kerül kiadásra.
 *
 * @event update:checked - Kiváltva, ha az ellenőrzött állapot frissítve van.
 * @type {boolean | Array} - A 'checked' prop új értéke.
 */
const emit = defineEmits([
    /**
     * Kibocsátva, ha az ellenőrzött állapot frissítve van.
     *
     * @param {boolean | Array} newCheckedValue - A 'checked' prop új értéke.
     */
    'update:checked'
]);


/**
 * Kellékek a Checkbox komponenshez.
 *
 * @typedef {Object} CheckboxProps
 * @property {Array|Boolean} checked - A jelölőnégyzet bejelölt állapota. Lehet logikai érték vagy tömb.
 * @property {*} [value=null] - A jelölőnégyzethez tartozó érték.
 */

/**
 * Props definition for the Checkbox component.
 *
 * @type {CheckboxProps}
 */
const props = defineProps({
    /**
     * A jelölőnégyzet bejelölt állapota. Lehet logikai érték vagy tömb.
     *
     * @type {Array|Boolean}
     */
    checked: {
        type: [Array, Boolean],
        required: true,
    },

    /**
     * A jelölőnégyzethez tartozó érték.
     *
     * @type {*}
     * @default null
     */
    value: {
        default: null,
    },
});


/**
 * Az „ellenőrzött” tulajdonság kétirányú összerendeléséhez használt számított tulajdonság.
 * Ha a számított tulajdonság értéke megváltozik, az 'update:checked' esemény kerül kiadásra.
 *
 * @type {import('vue').ComputedRef<Array|Boolean>}
 */
const proxyChecked = computed({
    /**
     * Getter függvény a számított tulajdonsághoz. Az „ellenőrzött” prop értékét adja vissza.
     *
     * @returns {Array|Boolean} Az „ellenőrzött” tulajdonság értéke.
     */
    get() {
        return props.checked;
    },

    /**
     * Beállító függvény a számított tulajdonsághoz. Meghíváskor az 'update:checked' eseményt bocsátja ki az új értékkel.
     *
     * @param {Array|Boolean} val - Az „ellenőrzött” tulajdonság új értéke.
     */
    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <!--
        A jelölőnégyzet elem.
        A value tulajdonságában megadott értéket tartalmazza.
        A v-model tulajdonságában található számított tulajdonság segítségével kétirányú összerendelést
        végez az „ellenőrzött” tulajdonsággal.
    -->
    <input
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
    >
    <!--
        Az „ellenőrzött” tulajdonsághoz tartozó számított tulajdonság értékét várja.
        Amikor az érték megváltozik, az 'update:checked' eseményt bocsátja ki a módosított értékkel.
    -->
</template>
