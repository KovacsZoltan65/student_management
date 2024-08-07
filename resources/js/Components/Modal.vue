<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue';


/**
 * Props definíciót a Modal komponenshez.
 *
 * @typedef {Object} ModalProps
 * @property {boolean} show - Logikai érték, amely jelzi, hogy a modális megjelenik-e vagy sem.
 * @property {string} maxWidth - A modál maximális szélessége. Alapértelmezés szerint '2xl'.
 * @property {boolean} closeable - Logikai érték, amely azt jelzi, hogy a modális bezárható-e vagy sem. Alapértelmezés szerint igaz.
 */
const props = defineProps({
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
 * Eseményt vált ki, amikor a modális bezáródik.
 *
 * Az esemény neve 'close', és nem fogad paramétereket.
 *
 * @event close - Kiváltva, ha a modális bezáródik.
 */
const emit = defineEmits(['close']);


/**
 * Figyelése a 'show' prop változására és a szükséges műveletek végrehajtása.
 *
 * Ha a 'show' prop értéke true, akkor a dokumentum törzsének overflow stílusa 'hidden'-ra állítódik,
 * ellenkező esetben visszaállítódik a régi értékre.
 *
 * @return {void}
 */
watch(
    // A figyelt prop
    () => props.show,
    // Az eseménykezelő függvény
    () => {
        // Ha a 'show' prop értéke true, akkor a dokumentum törzsének overflow stílusa 'hidden'-ra állítódik
        if (props.show) {
            document.body.style.overflow = 'hidden';
        } 
        // Ellenkező esetben visszaállítódik a régi értékre
        else {
            document.body.style.overflow = null;
        }
    }
);

/**
 * A 'close' metódus a modális bezárására szolgál.
 * Ha a 'closeable' prop értéke igaz, akkor a 'close' eseményt jelzi ki.
 *
 * @return {void}
 */
const close = () => {
    if (props.closeable) {
        emit('close');
    }
};


/**
 * A 'closeOnEscape' függvény a modális bezárását végzi, ha az Escape billentyűt lenyomják.
 *
 * @param {KeyboardEvent} e - A billentyűzet eseménye.
 * @return {void} 
 */
const closeOnEscape = (e) => {
    // Az Escape billentyűt lenyomták?
    // És a 'show' prop értéke igaz?
    //Ekkor a 'close' függvényt hívjuk meg.
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

/**
 * Felszereli az összetevőt, és hozzáad egy kulcsleállítási eseményfigyelőt a dokumentumhoz.
 * Ez az eseményfigyelő meghívja a `closeOnEscape` függvényt az Escape billentyű lenyomásakor.
 * 
 * @return {void}
 */
onMounted(() => {
    // Kulcsleállítási eseményfigyelőt ad a dokumentumhoz
    // Ez az eseményfigyelő meghívja a `closeOnEscape` függvényt az Escape billentyű lenyomásakor
    document.addEventListener('keydown', closeOnEscape);
});


/**
 * Eltávolítja az Escape billentyű lenyomásakor történő modális bezárást.
 * Visszaállítja a dokumentum törzsének overflow stílusát.
 * 
 * @return {void}
 */
onUnmounted(() => {
    // Eltávolítja az Escape billentyű lenyomásakor történő modális bezárást
    document.removeEventListener('keydown', closeOnEscape);
    
    // Visszaállítja a dokumentum törzsének overflow stílusát
    document.body.style.overflow = null;
});


/**
 * A megfelelő max-width osztályt adja vissza a "maxWidth" prop alapján.
 * 
 * @return {string|undefined} A max-width osztálykarakterlánc vagy meghatározatlan, 
 *                            ha a „maxWidth” nem érvényes beállítás.
 */
const maxWidthClass = computed(() => {
    // Egy objektum, amely minden érvényes „maxWidth” propértéket leképez a megfelelő max-width osztálykarakterláncra.
    const maxWidthClasses = {
        sm: 'sm:max-w-sm', // Extra kicsi max szélesség osztály
        md: 'sm:max-w-md', // Közepes maximális szélességű osztály
        lg: 'sm:max-w-lg', // Nagy maximális szélesség osztály
        xl: 'sm:max-w-xl', // Extra large max-width class
        '2xl': 'sm:max-w-2xl', // Dupla extra nagy maximális szélesség osztály
    };

    // Adja vissza a max-width osztálykarakterláncot, amely megfelel a "maxWidth" propnak, 
    // vagy nem definiált, ha a "maxWidth" nem érvényes beállítás.
    return maxWidthClasses[props.maxWidth];
});
</script>

<template>
    <!--
        A "Teleport" komponens használatával a modális ablakot a "body" elem alá helyezi.
        Ez biztosítja, hogy a modális ablak mindig a legfelső rétegben jelenjen meg.
    -->
    <Teleport to="body">
        <!--
            A "Transition" komponens használatával be-/kiúsztatási átmenetet alkalmaz a modális ablak
            láthatóságának változásaihoz.
        -->
        <Transition leave-active-class="duration-200">
            <!--
                A modális ablak rejtett, ha a "show" prop hamis, és látható, ha a "show" prop igaz.
            -->
            <div v-show="show" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" scroll-region>
                <!--
                    Az átfedés akkor jelenik meg, ha a modális látható, és rejtett, ha a modális el van rejtve.
                    A fedvény átlátszatlansága 75%-ra van állítva.
                -->
                <Transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <!-- Az átfedés háttér div-je -->
                    <div 
                        v-show="show" 
                        class="fixed inset-0 transform transition-all" 
                        @click="close"
                    >
                        <!-- A fedvény háttere, amely 75%-os átlátszatlanságra van beállítva -->
                        <div class="absolute inset-0 bg-gray-500 opacity-75" />
                    </div>
                </Transition>

                <!--
                    A modális ablak átmenetet használ a bejáratának és távozásának animálásához.
                    Az átmenetet a "show" prop indítja el, amely meghatározza, hogy a modális látható vagy rejtve.
                    Az átmenet attribútumokat használ a CSS-osztályok meghatározásához a belépési és indulási átmenetekhez.
                    Az átmenet tulajdonságait a Tailwind CSS keretrendszer határozza meg.
                -->
                <Transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <!--
                        A modális ablak csak akkor jelenik meg, ha a "show" prop igaz.
                        A "maxWidthClass" számított tulajdonság a modális ablak maximális szélességének beállítására szolgál.
                        A "bg-white" osztály a modális ablak háttérszínét fehérre állítja.
                        A "rounded-lg" osztály a modális ablak sarkait kerekíti.
                        A "overflow-hidden" osztály elrejti a túlcsordult tartalmat.
                        Az "shadow-xl" osztály árnyékot ad a modális ablakhoz.
                        A "transform" osztály transzformációt alkalmaz a modális ablakra.
                        A "transition-all" osztály átmenetet alkalmaz a modális ablak összes tulajdonságára.
                        Az "sm:w-full" osztály 100%-ra állítja a modális ablak szélességét kis képernyőkön.
                        Az "sm:mx-auto" osztály a modális ablakot vízszintesen középre állítja kis képernyőkön.
                    -->
                    <div
                        v-show="show"
                        class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto"
                        :class="maxWidthClass"
                    >
                        <!-- A modális ablak tartalma a "v-if" direktíva használatával kerül megjelenítésre. -->
                        <slot v-if="show" />
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
