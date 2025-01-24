const browserSync = require('browser-sync').create();
const { exec } = require('child_process');
const { cleanDist } = require('./utils');
const { distDir, styles, browserSyncConfig } = require('./config');

// Clean the dist directory
cleanDist(distDir);

// Watch Tailwind CSS files
styles.forEach(({ input, output }) => {
  exec(`npx @tailwindcss/cli -i ${input} -o ${output} --watch`);
});

// Initialize BrowserSync
browserSync.init(browserSyncConfig);
