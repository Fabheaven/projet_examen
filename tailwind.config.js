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
      keyframes: {
        pageTurnIn: {
          '0%': { transform: 'rotateY(-180deg)', opacity: '0' },
          '100%': { transform: 'rotateY(0)', opacity: '1' },
        },
        pageTurnOut: {
          '0%': { transform: 'rotateY(0)', opacity: '1' },
          '100%': { transform: 'rotateY(180deg)', opacity: '0' },
        },
      },
      animation: {
        'page-turn-in': 'pageTurnIn 0.5s forwards',
        'page-turn-out': 'pageTurnOut 0.5s forwards',
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