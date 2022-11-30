/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './index.html',
        './src/**/*.{vue,js}',
    ],
    theme: {
        extend: {},
    },
    safelist: [
        {
            pattern: /bg-(|red|blue|green|orange|purple|yellow|lime|cyan|amber|emerald|violet|fuchsia|rose)-500/,
        },
        {
            pattern: /border-(|red|blue|green|orange|purple|yellow|lime|cyan|amber|emerald|violet|fuchsia|rose)-500/,
        },
    ],
    plugins: [],
}
