<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';


/**
 * Határozza meg a legördülő komponens kellékeit.
 *
 * @returns {Object} - A meghatározott kellékeket tartalmazó objektum.
 */
const props = defineProps({
    /**
     * A legördülő menü igazítása.
     *
     * @type {string}
     * @default 'right'
     */
    align: {
        type: String,
        default: 'right', // Az alapértelmezett igazítás „jobbra” van állítva.
    },
    /**
     * A legördülő menü szélessége.
     *
     * @type {string}
     * @default '48'
     */
    width: {
        type: String,
        default: '48', // Az alapértelmezett szélesség '48'.
    },
    /**
     * A legördülő tartalom további osztályai.
     *
     * @type {string}
     * @default 'py-1 bg-white'
     */
    contentClasses: {
        type: String,
        default: 'py-1 bg-white', // Az alapértelmezett tartalomosztályok beállítása „py-1 bg-white”.
    },
});


/**
 * A legördülő menü bezárása, ha az Escape billentyűt lenyomták.
 *
 * @param {KeyboardEvent} e - A billentyűzet eseménye.
 * @return {void} 
 */
const closeOnEscape = (e) => {
    // A legördülő menü nyílt, és az Escape billentyűt lenyomták?
    if (open.value && e.key === 'Escape') {
        // Akkor zárjuk be a legördülő menüt.
        open.value = false;
    }
};

/**
 * Beállítja az Escape billentyű lenyomásakor történő legördülő menü bezárását.
 *
 * @return {void}
 */
onMounted(() => {
    // Figyelni kezdi a billentyűzet eseményeit, és ha az Escape billentyűt lenyomják, akkor
    // a `closeOnEscape` függvényt hívja meg.
    document.addEventListener('keydown', closeOnEscape);
});

/**
 * Eltávolítja az Escape billentyű lenyomásakor történő legördülő menü bezárását.
 *
 * @return {void}
 */
onUnmounted(() => {
    // Eltávolítja a billentyűzet eseményfigyelőt, amikor a komponens eltávolítódik.
    document.removeEventListener('keydown', closeOnEscape);
});


/**
 * A legördüló menü szélességének beállítása.
 *
 * @return {string|undefined} A megfelelő szélességosztály, vagy `undefined`, ha nem található.
 */
const widthClass = computed(() => {
    /**
     * A kiválasztott szélességes osztályok.
     *
     * @type {Object.<string, string>}
     */
    const widthClasses = {
        // Az alapértelmezett szélesség 48.
        '48': 'w-48',
    };

    // Keressük a megfelelő szélességosztályt.
    return widthClasses[props.width.toString()];
});


/**
 * A legördülő menü igazítási osztályainak beállítása.
 *
 * @return {string} A megfelelő igazítási osztályok.
 */
const alignmentClasses = computed(() => {
    // A legördülő menü igazítása alapján a megfelelő osztályokat adja vissza.

    // Ha a balra igazítva van, akkor a `origin-top-left` osztályt használja,
    // ha jobbra igazítva van, akkor a `origin-top-right` osztályt használja,
    // egyéb esetben az alapértelmezett `origin-top` osztályt használja.

    if (props.align === 'left') {
        // A balra igazított komponenshez használatos osztály.
        return 'ltr:origin-top-left rtl:origin-top-right start-0';
    } else if (props.align === 'right') {
        // A jobbra igazított komponenshez használatos osztály.
        return 'ltr:origin-top-right rtl:origin-top-left end-0';
    } else {
        // Az alapértelmezett igazítás esetén használatos osztály.
        return 'origin-top';
    }
});


/**
 * Reaktív hivatkozás a legördülő menü megnyitott állapotára.
 *
 * @type {import('vue').Ref<boolean>}
 * @description Ez a reaktív hivatkozás azt jelzi, hogy a legördülő menü megnyitott-e vagy sem.
 *              Az alapértelmezett érték `false`, ami azt jelenti, hogy a legördülő menü lezárva.
 *              Amennyiben a `open` értéke `true`, akkor a legördülő menü megnyitott,
 *              ha pedig `false`, akkor a legördülő menü lezárva.
 */
const open = ref(false);
</script>

<template>
    <div class="relative">
        <div @click="open = !open">
            <slot name="trigger" />
        </div>

        <!-- Full Screen Dropdown Overlay -->
        <div v-show="open" class="fixed inset-0 z-40" @click="open = false"></div>

        <!--
            A legördülő menü átmeneti komponense, amely a megnyitott állapotát kezeli.
            Ez a komponens egy `Transition` komponenst használ, amely a `open`
            állapotváltást kezeli, és a megfelelő osztályokat alkalmazza a
            átmenetkor.
        -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <!--
                A legördülő menü tartalmát tartalmazó komponens.
                Ez a komponens csak akkor jelenik meg, ha a `open` állapot `true`.
                A `z-50` osztály meghatározza, hogy a legördülő menü felette legyen.
                A `rounded-md` osztály meghatározza a kerekítést, és a `shadow-lg`
                osztály meghatározza a árkózót.
                A `widthClass` és `alignmentClasses` a `props`-ból származó adatokat használja.
            -->
            <div
                v-show="open"
                class="absolute z-50 mt-2 rounded-md shadow-lg"
                :class="[widthClass, alignmentClasses]"
                style="display: none"
                @click="open = false"
            >
                <!--
                    A legördülő menü tartalmát tartalmazó komponens.
                    Ez a komponens a `contentClasses` osztályokat használja a stílusok
                    meghatározásához.
                -->
                <div class="rounded-md ring-1 ring-black ring-opacity-5" :class="contentClasses">
                    <slot name="content" />
                </div>
            </div>
        </Transition>
    </div>
</template>
