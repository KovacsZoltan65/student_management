<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

/**
 * Kellékek a Bejelentkezés komponenshez.
 *
 * @typedef {Object} Props
 * @property {boolean} canResetPassword - A felhasználó visszaállíthatja-e jelszavát.
 * @property {string} status - A bejelentkezési folyamat állapotüzenete.
 */

/**
 * Határozza meg a Login összetevő kellékeit.
 *
 * @type {Props}
 */
defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

/**
 * A bejelentkezési űrlap adatai.
 *
 * @type {import('@inertiajs/vue3').Form}
 */
const form = useForm({
    email: '', // A felhasználó e-mail címe.
    password: '', // A felhasználó jelszava.
    remember: false, // Megjegyzi-e a felhasználó bejelentkezési adatait.
});

/**
 * Nyújtsa be a bejelentkezési űrlapot.
 *
 * @return {void}
 */
const submit = () => {
    // Nyújtsa be az űrlapot a „bejelentkezési” útvonalra a megadott adatokkal.
    // Ha a beküldés sikeres, állítsa vissza a jelszómezőt.
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <!--
        A Bejelentkezés összetevő egy űrlapot jelenít meg a felhasználók számára az alkalmazásba való bejelentkezéshez.
        A GuestLayout összetevőt használja szülő elrendezésként.
    -->
    <GuestLayout>
        <!-- Állítsd az oldal címét "Bejelentkezés"-re -->
        <Head title="Log in" />

        <!-- Állapotüzenet megjelenítése, ha van ilyen -->
        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            <!-- Jelenítse meg az állapotüzenetet -->
            {{ status }}
        </div>

        <!-- A bejelentkezési űrlap -->
        <form @submit.prevent="submit">
            <!-- Az email mező -->
            <div>
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

                <!-- Hibaüzenet megjelenítése, ha az e-mail mező érvénytelen -->
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- A jelszó mező -->
            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <!-- Hibaüzenet megjelenítése, ha a jelszó mező érvénytelen -->
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Az „Emlékezz rám” jelölőnégyzet -->
            <div class="block mt-4">
                <label class="flex items-center">
                    <!-- A jelölőnégyzet bevitele -->
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <!-- A jelölőnégyzet címkeszövege -->
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <!-- Az "Elfelejtette a jelszavát?" linket és a "Bejelentkezés" gombot -->
            <div class="flex items-center justify-end mt-4">
                <!-- Jelenítsen meg egy hivatkozást a jelszó visszaállításához, ha a „canResetPassword” javaslat igaz -->
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    <!-- A link szövege -->
                    Forgot your password?
                </Link>

                <!-- A "Bejelentkezés" gomb -->
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    <!-- A gomb szövege -->
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
