/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './templates/**/*.twig', // Symfony Twig templates
        './assets/**/*.vue',    // Vue files
        './assets/**/*.js',     // JavaScript files
    ],
    theme: {
        extend: {
            colors: {
                darkNavy: '#1A1A2E',
                charcoal: '#2C2C34',
                slateBlue: '#4a596f',
                softYellow: '#FFFFE0',
                teal: '#008080',
                cyan: '#00FFFF',
                lightGray: '#F5F5F5',
            },
            fontFamily: {
                header: ['Inter', 'sans-serif'],
                paragraph: ['Poppins', 'sans-serif'],
            },
        },
    },
    plugins: [],
};
