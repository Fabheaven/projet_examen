/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.html.twig',  // Fichiers Twig
    './assets/js/**/*.js',         // Fichiers JavaScript
    './node_modules/tw-elements/js/**/*.js',  // Nouveau chemin pour tw-elements v2
  ],
  theme: {
    extend: {
      colors: {
        'custom-blue': '#3b71ca',
      },
    },
  },
  plugins: [
    require('tw-elements/plugin'), // Nouveau chemin pour le plugin
  ],
  safelist: [
    'h-screen',
    'h-[80%]',
    'w-screen',
    'w-[30%]',
    'w-[90%]',
    'flex',
    'flex-row',
    'justify-center',
    'items-center',
    'bg-black',
    'bg-white',
    'h-full'
  ]
};