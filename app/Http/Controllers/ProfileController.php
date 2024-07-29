<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * A felhasználói profil űrlap megjelenítése.
     *
     * @param \Illuminate\Http\Request $request A HTTP kérés objektuma.
     * @return \Inertia\Response A Inertia válaszobjektum.
     */
    public function edit(Request $request): Response
    {
        // Határozza meg, hogy a felhasználónak igazolnia kell-e az e-mail címét.
        $mustVerifyEmail = $request->user() instanceof MustVerifyEmail;

        // Kérje le az állapotüzenetet a munkamenetről, ha van ilyen.
        $status = session('status');

        // Renderelje le a 'Profil/Edit' Inertia komponenst a szükséges adatokkal.
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $mustVerifyEmail,
            'status' => $status,
        ]);
    }

    /**
     * Frissítse a felhasználó profiladatait.
     *
     * @param  ProfileUpdateRequest  $request  Az érvényesített bemeneti adatokat tartalmazó HTTP kérés objektum.
     * @return RedirectResponse A HTTP-válasz objektum, amely a felhasználó profilszerkesztő oldalára irányít át.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Töltse ki a felhasználói modellt az érvényesített bemeneti adatokkal.
        $request->user()->fill($request->validated());

        // Ellenőrizze, hogy az e-mail cím megváltozott-e.
        if ($request->user()->isDirty('email')) {
            // Ha igen, jelölje meg a felhasználó e-mail-címét ellenőrizetlenként.
            $request->user()->email_verified_at = null;
        }

        // Mentse el a felhasználói modell módosításait.
        $request->user()->save();

        // A felhasználó átirányítása a profilszerkesztő oldalra.
        return Redirect::route('profile.edit');
    }

    /**
     * Törölje a felhasználói fiókot.
     *
     * Ez a módszer a megadott jelszó érvényesítése után megsemmisíti a felhasználó fiókját.
     * Kijelentkezteti a felhasználót, törli a felhasználó rekordját az adatbázisból, érvényteleníti
     * a felhasználó munkamenetét, és újra létrehoz egy új CSRF tokent a munkamenethez. Végül,
     * átirányítja a felhasználót a kezdőlapra.
     *
     * @param  Request  $request  A jelszó érvényesítését tartalmazó HTTP-kérelem objektum.
     * @return RedirectResponse  A HTTP-válasz objektum, amely a felhasználót a kezdőlapra irányítja át.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Ellenőrizze, hogy a megadott jelszó megegyezik-e a felhasználó jelenlegi jelszavával.
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        // Szerezze meg az aktuális felhasználót.
        $user = $request->user();

        // Jelentkezzen ki.
        Auth::logout();

        // Törölje a felhasználó rekordját az adatbázisból.
        $user->delete();

        // Érvénytelenítse a felhasználó munkamenetét.
        $request->session()->invalidate();

        // Hozzon létre újra egy új CSRF tokent a munkamenethez.
        $request->session()->regenerateToken();

        // A felhasználó átirányítása a kezdőlapra.
        return Redirect::to('/');
    }
}
