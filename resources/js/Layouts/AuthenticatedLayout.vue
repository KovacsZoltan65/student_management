<script setup>
import { onMounted, ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/vue3";
import { loadLanguageAsync } from "laravel-vue-i18n";

const showingNavigationDropdown = ref(false);

const setLocale = (locale) => {
    loadLanguageAsync(locale);
};

onMounted(() => {
    setLocale('hu');
});

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <!--
                Navigációs menü panel, amely a felső részben található.
                Alapvetően a navigációs menü.
            -->
            <nav class="bg-white border-b border-gray-100">

                <!-- Elsődleges navigációs menü -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">

                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800"
                                    />
                                </Link>
                            </div>

                            <!-- Navigációs hivatkozások -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <!--
                                    Navigációs hivatkozás a dashboard oldalára.

                                    Az "aktív" prop ellenőrzi, hogy az aktuális útvonal megfelel-e a megadott útvonalak valamelyikének.
                                    Ez az aktív navigációs hivatkozás kiemelésére szolgál.
                                -->
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    <!--
                                        A navigációs hivatkozás tartalma.
                                    -->
                                    {{ $t('dashboard')}}
                                </NavLink>
                                <!--
                                    Navigációs hivatkozás a diákok indexoldalára.

                                    Az "aktív" prop ellenőrzi, hogy az aktuális útvonal megfelel-e a megadott útvonalak valamelyikének.
                                    Ez az aktív navigációs hivatkozás kiemelésére szolgál.
                                -->
                                <NavLink
                                    :href="route('students.index')"
                                    :active="
                                        // Ellenőrizze, hogy az aktuális útvonal megegyezik-e a tanulók tárgymutatójával
                                        route().current('students.index') ||
                                        // Ellenőrizze, hogy az aktuális útvonal megegyezik-e a tanulók által létrehozott oldallal
                                        route().current('students.create') ||
                                        // Ellenőrizze, hogy az aktuális útvonal megegyezik-e a tanulók szerkesztőoldalával
                                        route().current('students.edit')
                                    "
                                >
                                    <!-- A tanulók megjelenítendő szövege -->
                                    {{ $t('students') }}
                                </NavLink>

                                <NavLink :href="route('classes.index')" 
                                         :active="route().current('classes.index') || 
                                            route().current('classes.create') || 
                                            route().current('classes.edit')">
                                    {{ $t('classes') }}
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <!--
                                    Beállítások legördülő menüjét.

                                    Az `align` prop határozza meg, hogy a menü hol jelenik meg a képernyőn.
                                    Alapértelmezetten a bal oldali igazítás van beállítva.

                                    A `width` prop határozza meg a menü szélességét. Az alapértelmezett érték 48,
                                    amely megfelel a logó és a név megjelenítésének helyes helyzetének eléréséhez.

                                    A `Dropdown` komponens a `DropdownMenu` komponenst használja, hogy
                                    megjelenítse a legördülő menüt.
                                -->
                                <Dropdown align="right" width="48" >
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <!--
                                                A felhasználó nevé mellett megjelenítünk egy gombot, amely
                                                megnyitja a felhasználó profiljának szerkesztésére szolgáló menüt.
                                                A gombon a felhasználó neve és egy down-arrow ikont látható.
                                            -->
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                <!-- A felhasználó neve -->
                                                {{ $page.props.auth.user.name }}

                                                <!-- A down-arrow ikont tartalmazó SVG -->
                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <!-- A down-arrow ikont meghatározó path -->
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!--
                                            DropdownLink összetevő, amely megjeleníti a felhasználó profiljának
                                            szerkesztésére szolgáló linket a felhasználói felületen.
                                         -->
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            <!--
                                                A link, amely a felhasználó profiljának szerkesztésére
                                                navigál.
                                             -->
                                            {{ $t('profile') }}
                                        </DropdownLink>
                                        <!--
                                            DropdownLink összetevő, amely logout gombot
                                            jelenít meg a felhasználói felületen.
                                         -->
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            <!--
                                                A gombon megjelenő szöveg, amely
                                                a felhasználó kijelentkezését
                                                jelzi.
                                             -->
                                             {{ $t('logout') }}
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <!--
                                A Hamburger menü gombot jeleníti meg, amely a navigációs menüt
                                nyitja vagy zárja.
                             -->
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <!-- A Hamburgert menü gomb ikona -->
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <!-- A menü nyitott állapotban megjelenő ikona -->
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <!-- A menü zárva állapotban megjelenő ikona -->
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    >
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Érzékeny navigációs menü -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <!--
                        A ResponsiveNavigationMenu része, amely tartalmazza a navigációs menüpontokat.
                        Jelenleg csak a Dashboard menüpont található itt.
                    -->
                    <div class="pt-2 pb-3 space-y-1">
                        <!--
                            A ResponsiveNavLink komponens a navigációs menüpontokat jeleníti meg.
                            :href tulajdonsága a menüponthoz tartozó URL-t tartalmazza.
                            :active tulajdonsága ellenőrzi, hogy a jelenlegi URL-cím megegyezik-e a menüpont URL-címével.
                            Ezzel a tulajdonsággal a menüponton belül a kiválasztott menüpont fel lesz tüntetve.
                        -->
                        <ResponsiveNavLink
                            :href="route('dashboard')" 
                            :active="route().current('dashboard')" 
                        >
                            Dashboard  // A menüpont címképe
                        </ResponsiveNavLink>
                    </div>

                    <!--
                        Ez a rész a hitelesített felhasználó profilinformációit jeleníti meg.
                        Tartalmazza a felhasználó nevét és e-mail címét.
                    -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <!-- Jelenítse meg a felhasználó nevét -->
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <!-- Jelenítse meg a felhasználó e-mail-címét -->
                            <div class="font-medium text-sm text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <!-- Link a felhasználó profiloldalára -->
                            <ResponsiveNavLink :href="route('profile.edit')">
                                {{ $t('profile') }}
                            </ResponsiveNavLink>
                            <!-- Link a kijelentkezési oldalra -->
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                {{ $t('logout') }}
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!--
                Az oldal fejléc szakasza.
                Jelenleg egy egyszerű fejlécet jelenít meg, amely a gyermekkomponensek által átadott tartalmat jeleníti meg.
            -->
            <header class="bg-white shadow" v-if="$slots.header">
                <!--
                    A `<div>` komponens a fejlécre vonatkozó stílusokat tartalmazza.
                    A maximum szélesség 7xl, a belső margó 4, a külső margó 6, a külső margó esetén az X és a L típusú külső margó 8.
                -->
                <div
                    class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"
                >
                    <!--
                        A `<slot>` komponens a gyermekkomponensek által átadott tartalmat jeleníti meg.
                        A "header" nevet kapta, hogy a gyermekkomponensek ehhez a komponenshez tudjanak csatlakozni.
                    -->
                    <slot name="header" />
                </div>
            </header>

            <!--
                Az oldal fő tartalma.
                Itt jelennek meg a gyermekkomponensek.
            -->
            <main>
                <!--
                    A `<slot />` komponens a gyermekkomponensek megjelenítésére szolgál.
                    Az AuthenticatedLayout összetevőnek átadott tartalom helyőrzője.
                    A gyermekkomponensek ott jelennek meg, ahol a `<slot />` komponens található.
                -->
                <slot />
            </main>
        </div>
    </div>
</template>
