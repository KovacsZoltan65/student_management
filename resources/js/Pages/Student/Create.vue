<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { watch, ref } from "vue";
import axios from "axios";
import InputError from "@/Components/InputError.vue";

defineProps({
    classes: {
        type: Object,
    },
});

let sections = ref({});


/**
 * Reaktív hivatkozás az űrlap adataira.
 *
 * @type {import('@inertiajs/vue3').UseFormReturn<{
 *     name: string,
 *     email: string,
 *     class_id: string,
 *     section_id: string,
 * }>}
 * @description Ez a reaktív hivatkozás a tanuló űrlapadatainak tárolására szolgál.
 * A `useForm` függvény egy reaktív űrlapobjektum létrehozására szolgál.
 * A "ref" függvény a tanulói adatok reaktív hivatkozássá alakítására szolgál,
 * Lehetővé teszi számunkra az űrlapadatok egyszerű frissítését és a Vue komponens reaktív frissítéseinek elindítását.
 */
const form = useForm({
    /**
     * A tanuló neve.
     *
     * @type {string}
     */
    name: "",

    /**
     * A hallgató e-mail címe.
     *
     * @type {string}
     */
    email: "",

    /**
     * Annak az osztálynak az azonosítója, amelyhez a tanuló tartozik.
     *
     * @type {string}
     */
    class_id: "",

    /**
     * Annak a szakasznak az azonosítója, amelyhez a tanuló tartozik.
     *
     * @type {string}
     */
    section_id: "",
});


/**
 * Figyeli az űrlap class_id tulajdonságának változásait, és lekéri az új class_id szakaszokat.
 *
 * @param {string} newValue - A class_id tulajdonság új értéke.
 * @return {void}
 */
watch(
    // A figyelő funkció úgy van beállítva, hogy figyelje az űrlap class_id tulajdonságának változásait.
    () => form.class_id,
    /**
     * Ez a funkció akkor kerül végrehajtásra, amikor a figyelt tulajdonság megváltozik.
     * Ha az új érték falsy, törli a szakaszok reaktív hivatkozását.
     * Ellenkező esetben lekéri az új class_id szakaszokat.
     *
     * @param {string} newValue - A class_id tulajdonság új értéke.
     * @return {void}
     */
    (newValue) => {
        // Ha az új érték hamis, törölje a szakaszok reaktív hivatkozását.
        if (!newValue) {
            sections.value = {};
        }
        // Ellenkező esetben kérje le az új class_id szakaszokat.
        else {
            getSections(newValue);
        }
    }
);

/**
 * Lekéri a szakaszokat a megadott osztályazonosító alapján.
 *
 * @param {string} class_id - Az osztály azonosítója.
 * @return {void}
 */
const getSections = (class_id) => {
    // Állítsa össze az API-végpont URL-jét az osztályazonosító lekérdezési paraméterrel.
    const url = `/api/sections?class_id=${class_id}`;

    // Készítsen HTTP GET-kérést az API-végponthoz.
    axios.get(url)
        // Kezelje az API-ból érkező választ.
        .then((response) => {
            // Frissítse a szakaszok reaktív hivatkozását a válaszadatokkal.
            sections.value = response.data;
        });
};


/**
 * A studens létrehozásához használatos függvény.
 * Az `form` objektumot a `useForm` függvény segítségével jelenítjük meg.
 * A `route` függvény segítségével előkészítjük az URL-t a `students.store` útvonalra.
 * A `form.post` metódus segítségével elküldjük az adatokat a kiszolgálóhoz.
 * A `preserveScroll: true` opcióval a jelenlegi oldal pozíciója megmarad a frissítés után.
 *
 * @return {void}
 */
const submit = () => {
    // Elküldjük a form adatait a kiszolgálóhoz a students.store útvonalra.
    // A preserveScroll opcióval a jelenlegi oldal pozíciója megmarad a frissítés után.
    form.post(route("students.store"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Students" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Students
            </h2>
        </template>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-12">
                    <form @submit.prevent="submit">
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                                <div>
                                    <h3
                                        class="text-lg leading-6 font-medium text-gray-900"
                                    >
                                        Student Information
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Use this form to create a new student.
                                    </p>
                                </div>

                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <label
                                            for="name"
                                            class="block text-sm font-medium text-gray-700"
                                            >Name</label
                                        >
                                        <input
                                            v-model="form.name"
                                            type="text"
                                            id="name"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            :class="{
                                                'text-red-900 focus:ring-red-500 focus:border-red-500 border-red-300':
                                                    form.errors.name,
                                            }"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.name"
                                        />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label
                                            for="email"
                                            class="block text-sm font-medium text-gray-700"
                                            >Email Address</label
                                        >
                                        <input
                                            v-model="form.email"
                                            type="email"
                                            id="email"
                                            autocomplete="email"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            :class="{
                                                'text-red-900 focus:ring-red-500 focus:border-red-500 border-red-300':
                                                    form.errors.email,
                                            }"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.email"
                                        />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label
                                            for="class_id"
                                            class="block text-sm font-medium text-gray-700"
                                            >Class</label
                                        >
                                        <select
                                            v-model="form.class_id"
                                            id="class_id"
                                            class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            :class="{
                                                'text-red-900 focus:ring-red-500 focus:border-red-500 border-red-300':
                                                    form.errors.class_id,
                                            }"
                                        >
                                            <option value="">
                                                Select a Class
                                            </option>
                                            <option
                                                v-for="item in classes.data"
                                                :key="item.id"
                                                :value="item.id"
                                            >
                                                {{ item.name }}
                                            </option>
                                        </select>
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.class_id"
                                        />
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label
                                            for="section_id"
                                            class="block text-sm font-medium text-gray-700"
                                            >Section</label
                                        >
                                        <select
                                            v-model="form.section_id"
                                            id="section_id"
                                            class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            :class="{
                                                'text-red-900 focus:ring-red-500 focus:border-red-500 border-red-300':
                                                    form.errors.section_id,
                                            }"
                                        >
                                            <option value="">
                                                Select a Section
                                            </option>
                                            <option
                                                v-for="section in sections.data"
                                                :value="section.id"
                                            >
                                                {{ section.name }}
                                            </option>
                                        </select>
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.section_id"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div
                                class="px-4 py-3 bg-gray-50 text-right sm:px-6"
                            >
                                <Link
                                    :href="route('students.index')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
