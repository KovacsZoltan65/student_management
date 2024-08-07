<script setup>
import Modal from '@/Components/Modal.vue';


/**
 * Határozza meg a kibocsátott eseményeket a DialogModal komponens számára.
 *
 * Eseménynevek tömbje kerül átadásra argumentumként a "defineEmits" függvénynek.
 * Meghatározza az összetevő által kibocsátható eseményeket.
 *
 * Ebben az esetben a komponens „bezárás” eseményt bocsát ki.
 *
 * @return {Array} Eseménynevek tömbje.
 */
const emit = defineEmits(['close']);

/**
 * Props definíciót a DialogModal komponenshez.
 *
 * @typedef {Object} DialogModalProps
 * @property {boolean} show - Logikai érték, amely jelzi, hogy a párbeszédablak megjelenik-e vagy sem.
 * @property {string} maxWidth - A párbeszédablak maximális szélessége. Alapértelmezés szerint '2xl'.
 * @property {boolean} closeable - Logikai érték, amely azt jelzi, hogy a párbeszédablak bezárható-e vagy sem. Alapértelmezés szerint igaz.
 */

/**
 * Props definíciót a DialogModal komponenshez.
 *
 * @type {DialogModalProps}
 */
defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});


/**
 * A 'close' függvény kiváltja a 'close' eseményt.
 * Az eseményt az üzemeltetői függvény (emit) segítségével jelezük ki
 *
 * @return {void}
 */
const close = () => {
    emit('close');
};

</script>

<template>
    <Modal :show="show"
           :max-width="maxWidth"
           :closeable="closeable"
           @close="close">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                <slot name="title" />
            </div>

            <div class="mt-4 text-sm text-gray-600">
                <slot name="content" />
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right">
            <slot name="footer" />
        </div>
    </Modal>
</template>