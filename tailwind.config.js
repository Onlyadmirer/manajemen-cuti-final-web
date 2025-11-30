import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    // TAMBAHKAN BARIS INI:
    darkMode: "class",
    // Artinya: Mode gelap hanya aktif jika ada class="dark" di HTML.
    // Karena kita tidak akan menambahkannya, maka website akan selalu Light Mode.

    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                inter: ["Inter", ...defaultTheme.fontFamily.sans],
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
