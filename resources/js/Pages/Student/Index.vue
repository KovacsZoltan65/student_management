<script setup>
import MagnifyingGlass from "@/Components/Icons/MagnifyingGlass.vue";
import Pagination from "@/Components/Pagination.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";

/**
 * Props for the Student Index page.
 *
 * @typedef {Object} StudentIndexProps
 * @property {Object} students - The students data.
 * @property {Object} classes - The classes data.
 */
defineProps({
    students: {
        type: Object,
    },
    classes: {
        type: Object,
        /**
         * The classes data is required.
         */
        required: true,
    },
});

/**
 * Reactive reference to the current page number.
 *
 * @type {import('vue').Ref<number>}
 */
let pageNumber = ref(1);

/**
 * Reactive reference to the search term.
 *
 * @type {import('vue').Ref<string>}
 * @description If the search term is not provided in the page props, it will default to an empty string.
 */
let searchTerm = ref(usePage().props.search ?? "");

/**
 * Reactive reference to the class ID.
 *
 * @type {import('vue').Ref<string>}
 * @description If the class ID is not provided in the page props, it will default to an empty string.
 */
let class_id = ref(usePage().props.class_id ?? "");

/**
 * Update the page number based on the given link.
 *
 * @param {Object} link - The link object containing the URL.
 * @param {string} link.url - The URL from which to extract the page number.
 */
const pageNumberUpdated = (link) => {
    // Extract the page number from the URL and update the pageNumber reactive reference.
    // The URL is expected to have a query parameter named "page" with the page number as its value.
    // For example: "/students?page=2"
    pageNumber.value = link.url.split("=")[1];
};


/**
 * Computed property that returns the URL for fetching students data.
 *
 * @return {URL} - The URL object containing the URL for fetching students data.
 * @description The URL is constructed using the route function and includes the page number, search term, and class ID as query parameters.
 */
let studentsUrl = computed(() => {
    // Create a new URL object using the route function for the students index route.
    const url = new URL(route("students.index"));

    // Set the page number as a query parameter in the URL.
    url.searchParams.set("page", pageNumber.value);

    // If a search term is provided, set it as a query parameter in the URL.
    if (searchTerm.value) {
        url.searchParams.set("search", searchTerm.value);
    }

    // If a class ID is provided, append it as a query parameter in the URL.
    if (class_id.value) {
        url.searchParams.append("class_id", class_id.value);
    }

    // Return the constructed URL object.
    return url;
});


/**
 * Watches for changes in the studentsUrl computed property and updates the route accordingly.
 *
 * @param {URL} newValue - The new value of the studentsUrl computed property.
 * @description When the studentsUrl computed property changes, it updates the route to reflect the new URL.
 *              This ensures that the data displayed in the page is refreshed with the new URL.
 *              The update is done using the router's visit method, which allows for preserving the current state and scroll position.
 */
watch(
    // Watch for changes in the studentsUrl computed property.
    () => studentsUrl.value,
    // When the studentsUrl computed property changes:
    (newValue) => {
        // Update the route to reflect the new URL.
        router.visit(newValue, {
            // Replace the current history entry with the new URL.
            replace: true,
            // Preserve the current state, including form data and scroll position.
            preserveState: true,
            // Preserve the current scroll position.
            preserveScroll: true,
        });
    }
);

/**
 * Watches for changes in the searchTerm reactive reference and updates the pageNumber if a search term is provided.
 *
 * @param {string} value - The new value of the searchTerm reactive reference.
 * @description When the searchTerm reactive reference changes, it checks if a search term is provided. If a search term is provided,
 *              it updates the pageNumber reactive reference to 1, effectively resetting the pagination to the first page.
 */
watch(
    // Watch for changes in the searchTerm reactive reference.
    () => searchTerm.value,
    // When the searchTerm reactive reference changes:
    (value) => {
        // If a search term is provided:
        if (value) {
            // Reset the pageNumber reactive reference to 1.
            pageNumber.value = 1;
        }
    }
);

/**
 * A reactive form object used for deleting students.
 * @type {import('@inertiajs/vue3').Form}
 */
const deleteForm = useForm({
    /**
     * The form data for the delete form.
     * @type {Object}
     * @property {} - The form data is empty, as we don't need to send any data to the server when deleting a student.
     */
});


/**
 * Deletes a student.
 *
 * @param {number} id - The ID of the student to delete.
 * @returns {void}
 */
const deleteStudent = (id) => {
    // Prompt the user to confirm the deletion of the student.
    if (confirm("Are you sure you want to delete this student?")) {
        // Delete the student using the deleteForm reactive form object.
        // The route method generates a URL for the students.destroy route, passing the student's ID as a parameter.
        // The delete method sends a DELETE request to the server to delete the student.
        // The preserveScroll option is set to true, which means the current scroll position will be preserved after the deletion is completed.
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
