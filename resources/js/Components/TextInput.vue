<script setup>
import { onMounted, ref } from 'vue';


/**
 * Meghatározza a beviteli mező modelljét.
 * A modell karakterlánc típusú, és kötelező.
 * @type {Object}
 */
const model = defineModel({
    type: String,
    required: true,
});

/**
 * Hivatkozás a bemeneti elemre.
 * @type {Object}
 */
const input = ref(null);

/**
 * Akkor fut le, amikor az összetevő fel van szerelve.
 * Ha a bemeneti elem rendelkezik „autofókusz” attribútummal, akkor az élesre lesz állítva.
 */
onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

/**
 * Megjeleníti a bemeneti elem fókuszmódszerét.
 * Ez lehetővé teszi a bemenet fókuszálását az összetevőn kívülről.
 * @type {Object}
 */
defineExpose({
    focus: () => input.value.focus(),
});
</script>

<template>
    <!--
        A beviteli elem.
        A v-model kijelöli, hogy az adatmodellt a bemeneti elem szinkronizálja.
        A ref jelöli, hogy a bemeneti elemet a `input` névvel hivatkozhatjuk.
    -->
    <!--
        class: Állítsa be a szegély, a fókuszálási és a kör formáját.
    -->
    <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
           v-model="model" ref="input">
</template>
