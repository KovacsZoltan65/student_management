<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});


/**
 * Nyújtsa be a jelszó-megerősítő űrlapot.
 *
 * Ez a funkció akkor aktiválódik, amikor a felhasználó elküldi a jelszó-megerősítő űrlapot.
 * POST kérést küld a 'password.confirm' útvonalra az űrlap adataival.
 * Ha a kérés sikeres, visszaállítja az űrlapot.
 *
 * @return {void}
 */
const submit = () => {
    // Küldjön POST kérést a 'password.confirm' útvonalra az űrlapadatokkal.
    // Az „onFinish” opció az űrlap visszaállítására szolgál, miután a kérés sikeres volt.
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <!--
        Ez egy Vue-komponens, amely a jelszó megerősítése oldal elrendezését képviseli.
        Ez a GuestLayout összetevő gyermekkomponense, és a jelszó megerősítése oldal megjelenítésére szolgál.
        Tartalmaz egy űrlapot, amely lehetővé teszi a felhasználó számára a jelszó megadását és megerősítését.
    -->
    <GuestLayout>
        <!-- Állítsa az oldal címét "Jelszó megerősítése"-re -->
        <Head title="Confirm Password" />

        <!-- Üzenet megjelenítése a felhasználónak -->
        <div class="mb-4 text-sm text-gray-600">
            This is a secure area of the application. Please confirm your password before continuing.
        </div>

        <!-- Űrlap a jelszó megadásához -->
        <form @submit.prevent="submit">
            <!-- A jelszó beviteli mezője -->
            <div>
                <InputLabel for="password" value="Password" />
                <!-- Szövegbeviteli mező a jelszóhoz -->
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                />
                <!-- A jelszómezőhöz kapcsolódó hibaüzenetek megjelenítése -->
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Gomb a jelszó megerősítéséhez -->
            <div class="flex justify-end mt-4">
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    <!-- Gomb címke -->
                    Confirm
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
