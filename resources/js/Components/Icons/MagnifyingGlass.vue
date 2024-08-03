<template>
    <!-- 
        Ez egy SVG-vel készült nagyító ikon.
        A keresési funkciók megjelenítésére szolgál.
    -->
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
        fill="currentColor"
        class="w-5 h-5"
    >
        <!--
            Ez az az út, amely a nagyítót formálja.
            A „fill-rule” attribútum határozza meg az alakzat kitöltésének módját.
            A „d” attribútum határozza meg a nagyító tényleges alakját.
            A „clip-rule” attribútum határozza meg, hogy az alakzaton kívüli tartalom hogyan legyen vágva.
        -->
        <path
            fill-rule="evenodd"
            d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
            clip-rule="evenodd"
        />
    </svg>
</template>
