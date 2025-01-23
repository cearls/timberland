const browserSync = require('browser-sync').create();
const { exec } = require('child_process');
const fs = require('fs');
const path = require('path');
const distDir = './theme/assets/dist';

const cleanDist = (directory) => {
  if (fs.existsSync(directory)) {
    fs.readdirSync(directory).forEach((file) => {
      const filePath = path.join(directory, file);
      if (fs.statSync(filePath).isFile()) {
        fs.unlinkSync(filePath);
      }
    });
    console.log(`Cleaned up files in ${directory}`);
  } else {
    console.log(`Directory ${directory} does not exist. Skipping cleanup.`);
  }
};

cleanDist(distDir);

exec('npx @tailwindcss/cli -i ./theme/assets/styles/main.css -o ./theme/assets/dist/main.css --watch');
exec('npx @tailwindcss/cli -i ./theme/assets/styles/editor-style.css -o ./theme/assets/dist/editor-style.css --watch');

browserSync.init({
  proxy: 'http://playground.test', // Replace with your local development URL
  files: [
    './theme/**/*.{twig,php,css,js}',
  ],
  injectChanges: true,
  notify: false,
  open: false,
});
