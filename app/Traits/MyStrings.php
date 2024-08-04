<?php

namespace App\Traits;

use Faker\Factory as FakerFactory;

trait MyStrings
{
    /**
     * A kisbetűs karakterlánc.
     *
     * @var string
     */
    protected static string $lowercase = 'abcdefghijklmnopqrstuvwxyz';

    /**
     * A nagybetűs karakterlánc.
     *
     * @var string 
     */
    protected static string $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    /**
     * A számsorok.
     *
     * @var string
     */
    protected static string $numbers = '0123456789';
    /**
     * A speciális karakterláncok.
     *
     * Ez a karakterlánc speciális karaktereket tartalmazó véletlenszerű karakterláncok generálására szolgál.
     * 
     * @var string
     */
    protected static string $specialChars = '!@#$%^&*()-_=+[]{}|;:,.<>?';
    
    /**
     * Adott hosszúságú véletlenszerű karakterlánc létrehozása opcionális kis- és nagybetűérzékenységgel, 
     * számokkal és speciális karakterekkel.
     *
     * @param int $length A véletlenszerű karakterlánc hossza.
     * @param string $case A karakterlánc kis- és nagybetűk érzékenysége. A lehetséges értékek a következők: 
     *  'lower' (kisbetűs), 'upper' (nagybetűs), vagy üres a kettő keveréke esetén.
     * @param bool $includeNumbers Be kell-e venni számokat a karakterláncba.
     * @param bool $includeSpecialChars Speciális karakterek szerepeltetése a karakterláncban.
     * @return string A generált véletlenszerű karakterlánc.
     */
    public static function generateRandomString(int $length, string $case = '',bool $includeNumbers = false, bool $includeSpecialChars = false  ) : string
    {
        // Hozzon létre egy Faker-példányt
        $faker = FakerFactory::create();
        
        // Határozza meg a karaktereket a kis- és nagybetűk érzékenysége alapján
        $characters = match ($case) {
            'lower' => self::$lowercase, // Kisbetűs karakterek
            'upper' => self::$uppercase, // Nagybetűs karakterek
            default => self::$lowercase . self::$uppercase, // Mindkettő keveréke
        };

        // Kérésre adjon hozzá számokat
        if ($includeNumbers) {
            $characters .= self::$numbers;
        }

        // Adjon hozzá speciális karaktereket, ha kéri
        if ($includeSpecialChars) {
            $characters .= self::$specialChars;
        }
        
        // A véletlenszerű karakterlánc létrehozása
        $randomString = '';
        for( $i = 0; $i < $length; $i++ ) {
            // Válasszon ki egy véletlenszerű karaktert a rendelkezésre álló karakterek közül, 
            // és fűzze hozzá a véletlenszerű karakterlánchoz
            $randomString .= $faker->randomElement(str_split($characters));
        }
        
        return $randomString;
    }
    
    /*
    public static function generateRandomString(int $length, string $case = '',bool $includeNumbers = false, bool $includeSpecialChars = false  ) : string
    {
        // Hozzon létre egy Faker-példányt
        $faker = FakerFactory::create();
        
        // Határozza meg a karaktereket a kis- és nagybetűk érzékenysége alapján
        $characters = match ($case) {
            'lower' => self::$lowercase, // Kisbetűs karakterek
            'upper' => self::$uppercase, // Nagybetűs karakterek
            default => self::$lowercase . self::$uppercase, // Mindkettő keveréke
        };

        // Kérésre adjon hozzá számokat
        if ($includeNumbers) {
            $characters .= self::$numbers;
        }

        // Adjon hozzá speciális karaktereket, ha kéri
        if ($includeSpecialChars) {
            $characters .= self::$specialChars;
        }
        
        // A véletlenszerű karakterlánc létrehozása
        $randomString = '';
        for( $i = 0; $i < $length; $i++ ) {
            // Válasszon ki egy véletlenszerű karaktert a rendelkezésre álló karakterek közül, 
            // és fűzze hozzá a véletlenszerű karakterlánchoz
            $randomString .= $faker->randomElement(str_split($characters));
        }
        
        return $randomString;
    }
    */
}