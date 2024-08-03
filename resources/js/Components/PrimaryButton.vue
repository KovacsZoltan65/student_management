<template>
    <!-- 
        Ez a gomb elsődleges összetevője. Egy gomb stílusos megjelenésű megjelenítésére szolgál.
        A gomb szürke háttérszínű és fehér szövegszínű.
        A gombnak van egy lebegő effektusa is, amely a háttérszínt kissé sötétebb szürke árnyalatra változtatja.
        A gombra kattintás alapértelmezés szerint nem indít semmilyen műveletet.
    -->
    <button
        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
    >
        <!--
            A gomb tartalma itt jelenik meg.
            Ez lehet bármilyen szöveg vagy más HTML-elem.
        -->
        <slot />
    </button>
</template>
