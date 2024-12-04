const browserSync = require('browser-sync').create();
const { exec } = require('child_process');

// Run Tailwind CLI in watch mode
exec('npx tailwindcss -i ./theme/assets/styles/main.css -o ./theme/assets/dist/main.css --watch');
exec('npx tailwindcss -i ./theme/assets/styles/editor-style.css -o ./theme/assets/dist/editor-style.css --watch');

// Initialize BrowserSync
browserSync.init({
  proxy: 'http://playground.test', // Replace with your local development URL
  files: [
    './theme/**/*.{twig,php,css,js}',
  ],
  injectChanges: true,
  notify: false,
  open: false,
});
