/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.html.twig',  // Recherche dans les fichiers Twig
    './assets/js/**/*.js',
    './node_modules/tw-elements/dist/js/**/*.js',  // Pour tw-elements (si tu l'utilises)
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('tw-elements/dist/plugin.cjs') // Décommente si tu utilises tw-elements
  ],
  safelist: [
    'h-screen',
    'h-[80%]',
    'w-screen',  // Correction de 'w - screen' => 'w-screen'
    'w-[30%]',
    'w-[90%]',
    'flex',
    'flex-row',
    'justify-center',
    'items-center', // Correction de 'items - center' => 'items-center'
    'bg-black',
    'bg-white',
    'h-full'
  ]
}
