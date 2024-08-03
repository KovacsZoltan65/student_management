<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';


/**
 * Kellékek a ForgotPassword összetevőhöz.
 *
 * @typedef {Object} ForgotPasswordProps
 * @property {string} status - Állapotüzenet a szervertől.
 */

/**
 * Kellékek a ForgotPassword összetevőhöz.
 *
 * @type {ForgotPasswordProps}
 */
const props = defineProps({
    /**
     * Állapotüzenet a szervertől.
     *
     * @type {string}
     */
    status: {
        type: String,
    },
});

/**
 * Űrlapobjektum a ForgotPassword összetevőhöz.
 *
 * @type {import('@inertiajs/vue3').UseFormReturn<{ email: string }>}
 */
const form = useForm({
    /**
     * A felhasználó e-mail címe.
     *
     * @type {string}
     */
    email: '',
});

/**
 * Elküldi az űrlapot a jelszó e-mail útvonalára.
 *
 * @return {void}
 */
const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <!-- A ForgotPassword összetevő, amely lehetővé teszi a felhasználók számára, hogy visszaállítsák a jelszavukat. -->
    <GuestLayout>
        <!-- A fejléc, amely tartalmazza a ForgotPassword oldalának a címet. -->
        <Head title="Forgot Password" />

        <!-- A felhasználóknak megjelenített üzenet, amely a jelszó visszaállításának folyamatáról tájékoztat. -->
        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset
            link that will allow you to choose a new one.
        </div>

        <!-- A szerver által visszaküldött állapotüzenet megjelenítése. -->
        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <!-- A jelszó visszaállítása űrlap. -->
        <form @submit.prevent="submit">
            <div>
                <!-- Az e-mail cím mező. -->
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <!-- A jelszó visszaállítása gomb. -->
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
