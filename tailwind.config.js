/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './templates/**/*.twig', // Symfony Twig templates
        './assets/**/*.vue',    // Vue files
        './assets/**/*.js',     // JavaScript files
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};
