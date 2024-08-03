<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <!--
        Ez a fő elrendezés a vendégfelhasználók számára. Tartalmaz egy egyszerű fejlécet az alkalmazás logójával és egy fő tartalomterülettel.
    -->
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        
        <!-- Header -->
        <div>
            <!-- Link a kezdőlapra -->
            <Link href="/">
                <!-- Alkalmazás logója -->
                <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
            </Link>
        </div>

        <!--
            Fő tartalom terület
            class: Stílus a fő tartalomterülethez
        -->
        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <!-- A helyek a tartalom dinamikus felvételére szolgálnak ebben az elrendezésben -->
            <slot />
        </div>
    </div>
</template>
