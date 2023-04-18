/** @type {import('tailwindcss').Config} */
export const content = [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.vue",
];
export const theme = {
    fontFamily: {
        body: ["Madefor", "sans-serif"],
    },
};
export const plugins = [require("@tailwindcss/forms")];
