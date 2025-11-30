/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './src/**/*.php',
    ],
    theme: {
        extend: {},
    },
    corePlugins: {
        preflight: false, // Disable Tailwind's base reset - Filament handles this
    },
    plugins: [],
}