<script setup>
import MagnifyingGlass from "@/Components/Icons/MagnifyingGlass.vue";
import Pagination from "@/Components/Pagination.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";

/**
 * Kellékek a Diákindex oldalhoz.
 *
 * @typedef {Object} StudentIndexProps
 * @property {Object} tanulók – A tanulók adatai.
 * @property {Object} osztályok – Az osztályok adatai.
 */
defineProps({
    students: {
        type: Object,
    },
    classes: {
        type: Object,
        /**
         * Az osztályok adatai kötelezőek.
         */
        required: true,
    },
});

/**
 * Reaktív hivatkozás az aktuális oldalszámra.
 *
 * @type {import('vue').Ref<number>}
 */
let pageNumber = ref(1);

/**
 * Reaktív hivatkozás a keresett kifejezésre.
 *
 * @type {import('vue').Ref<string>}
 * @description Ha a keresett kifejezés nincs megadva az oldal kellékei között, akkor alapértelmezés szerint üres karakterlánc lesz.
 */
let searchTerm = ref(usePage().props.search ?? "");

/**
 * Reaktív hivatkozás az osztályazonosítóra.
 *
 * @type {import('vue').Ref<string>}
 * @description Ha az osztályazonosító nincs megadva az oldalreklámokban, 
 * akkor alapértelmezés szerint üres karakterlánc lesz.
 */
let class_id = ref(usePage().props.class_id ?? "");

/**
 * Frissítse az oldalszámot a megadott link alapján.
 *
 * @param {Object} link – Az URL-t tartalmazó linkobjektum.
 * @param {karakterlánc} link.url – Az URL, ahonnan az oldalszámot ki kell bontani.
 */
const pageNumberUpdated = (link) => {
    // Bontsa ki az oldalszámot az URL-ből, és frissítse a pageNumber reaktív hivatkozást.
    // Az URL-nek egy "page" nevű lekérdezési paraméterrel kell rendelkeznie, amelynek értéke az oldalszám.
    // Például: "/students?page=2"
    pageNumber.value = link.url.split("=")[1];
};


/**
 * Számított tulajdonság, amely visszaadja az URL-t a tanulók adatainak lekéréséhez.
 *
 * @return {URL} – A tanulói adatok lekéréséhez szükséges URL-t tartalmazó URL-objektum.
 * @description Az URL-t az útvonal függvény segítségével hozzuk létre, 
 * és lekérdezési paraméterként tartalmazza az oldalszámot, 
 * a keresési kifejezést és az osztályazonosítót.
 */
let studentsUrl = computed(() => {
    // Hozzon létre egy új URL objektumot a route függvény segítségével a tanulók útvonalának indexéhez.
    const url = new URL(route("students.index"));

    // Állítsa be az oldalszámot lekérdezési paraméterként az URL-ben.
    url.searchParams.set("page", pageNumber.value);

    // Ha keresési kifejezést ad meg, állítsa be lekérdezési paraméterként az URL-ben.
    if (searchTerm.value) {
        url.searchParams.set("search", searchTerm.value);
    }

    // Ha meg van adva osztályazonosító, fűzze hozzá lekérdezési paraméterként az URL-hez.
    if (class_id.value) {
        url.searchParams.append("class_id", class_id.value);
    }

    // Visszaadja a felépített URL objektumot.
    return url;
});


/**
 * Figyeli a studentsUrl számított tulajdonság változásait, és ennek megfelelően frissíti az útvonalat.
 *
 * @param {URL} newValue - The new value of the studentsUrl computed property.
 * @description Amikor a studentsUrl számított tulajdonság megváltozik, frissíti az útvonalat, hogy tükrözze az új URL-t.
 *              Ez biztosítja, hogy az oldalon megjelenő adatok az új URL-lel frissüljenek.
 *              A frissítés a router látogatási módszerével történik, amely lehetővé teszi az aktuális állapot és görgetési pozíció megőrzését.
 */
watch(
    // Figyelje a studentsUrl számított tulajdonság változásait.
    () => studentsUrl.value,
    // Amikor a studentsUrl számított tulajdonság megváltozik:
    (newValue) => {
        // Frissítse az útvonalat, hogy az tükrözze az új URL-t.
        router.visit(newValue, {
            // Cserélje ki az aktuális előzménybejegyzést az új URL-címre.
            replace: true,
            // Megőrzi az aktuális állapotot, beleértve az űrlapadatokat és a görgetési pozíciót.
            preserveState: true,
            // Az aktuális görgetési pozíció megőrzése.
            preserveScroll: true,
        });
    }
);

/**
 * Figyeli a keresőkifejezés reaktív hivatkozásában bekövetkezett változásokat, és frissíti az oldalszámot, ha keresési kifejezést ad meg.
 *
 * @param {string} value - A searchTerm reaktív hivatkozás új értéke.
 * @description Amikor a keresőkifejezés reaktív hivatkozás megváltozik, ellenőrzi, hogy van-e megadva keresett kifejezés. Ha megadja a keresett kifejezést,
 *              frissíti a pageNumber reaktív hivatkozást 1-re, gyakorlatilag visszaállítja a lapozást az első oldalra.
 */
watch(
    // Figyelje a searchTerm reaktív hivatkozás változásait.
    () => searchTerm.value,
    // Amikor a keresőkifejezés reaktív hivatkozás megváltozik:
    (value) => {
        // Ha van megadva keresett kifejezés:
        if (value) {
            // Állítsa vissza a pageNumber reaktív hivatkozást 1-re.
            pageNumber.value = 1;
        }
    }
);

/**
 * A tanulók törlésére használt reaktív űrlapobjektum.
 * @type {import('@inertiajs/vue3').Form}
 */
const deleteForm = useForm({
    /**
     * A törlési űrlap adatai.
     * @type {Object}
     * @property {} - Az űrlap adatai üresek, mivel tanuló törlésekor nem kell adatokat küldenünk a szerverre.
     */
});


/**
 * Töröl egy tanulót.
 *
 * @param {number} id - A törölni kívánt tanuló azonosítója.
 * @returns {void}
 */
const deleteStudent = (id) => {
    // Kérje meg a felhasználót, hogy erősítse meg a tanuló törlését.
    if (confirm("Are you sure you want to delete this student?")) {
        // Törölje a tanulót a deleteForm reaktív űrlapobjektum segítségével.
        // A route metódus létrehoz egy URL-t a students.destroy útvonal számára, paraméterként átadva a tanuló azonosítóját.
        // A delete metódus DELETE kérést küld a szervernek a tanuló törlésére.
        // A keepScroll beállítás igazra van állítva, ami azt jelenti, hogy a törlés befejezése után az aktuális görgetési pozíció megmarad.
        deleteForm.delete(route("students.destroy", id), {
            preserveScroll: true,
        });
    }
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
        <div class="bg-gray-100 py-10">
            <div class="mx-auto max-w-7xl">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-xl font-semibold text-gray-900">
                                Students
                            </h1>
                            <p class="mt-2 text-sm text-gray-700">
                                A list of all the Students.
                            </p>
                        </div>

                        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                            <Link
                                :href="route('students.create')"
                                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                            >
                                Add Student
                            </Link>
                        </div>
                    </div>

                    <div class="flex flex-col justify-start sm:flex-row mt-6">
                        <div class="relative text-sm text-gray-800 col-span-3">
                            <div
                                class="absolute pl-2 left-0 top-0 bottom-0 flex items-center pointer-events-none text-gray-500"
                            >
                                <MagnifyingGlass />
                            </div>

                            <input
                                type="text"
                                v-model="searchTerm"
                                placeholder="Search students data..."
                                id="search"
                                class="block rounded-lg border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            />
                        </div>

                        <select
                            v-model="class_id"
                            class="block rounded-lg border-0 py-2 ml-5 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 sm:text-sm sm:leading-6"
                        >
                            <option value="">Filter By Class</option>
                            <option
                                :value="item.id"
                                :key="item.id"
                                v-for="item in classes.data"
                            >
                                {{ item.name }}
                            </option>
                        </select>
                    </div>

                    <div class="mt-8 flex flex-col">
                        <div
                            class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8"
                        >
                            <div
                                class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8"
                            >
                                <div
                                    class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg relative"
                                >
                                    <table
                                        class="min-w-full divide-y divide-gray-300"
                                    >
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                                >
                                                    ID
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                                >
                                                    Name
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                                >
                                                    Email
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Class
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Section
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Created At
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="relative py-3.5 pl-3 pr-4 sm:pr-6"
                                                />
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="divide-y divide-gray-200 bg-white"
                                        >
                                            <tr
                                                v-for="student in students.data"
                                                :key="student.id"
                                            >
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                                >
                                                    {{ student.id }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                                >
                                                    {{ student.name }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{ student.email }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{ student.class.name }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{ student.section.name }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{
                                                        student.created_at_formatted
                                                    }}
                                                </td>

                                                <td
                                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                                >
                                                    <Link
                                                        :href="
                                                            route(
                                                                'students.edit',
                                                                student.id
                                                            )
                                                        "
                                                        class="text-indigo-600 hover:text-indigo-900"
                                                    >
                                                        Edit
                                                    </Link>
                                                    <button
                                                        @click="
                                                            deleteStudent(
                                                                student.id
                                                            )
                                                        "
                                                        class="ml-2 text-indigo-600 hover:text-indigo-900"
                                                    >
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <Pagination
                                    :data="students"
                                    :pageNumberUpdated="pageNumberUpdated"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
