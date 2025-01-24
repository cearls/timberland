require('dotenv').config();
const path = require('path');

module.exports = {
  distDir: path.resolve(__dirname, '../theme/assets/dist'),
  styles: [
    {
      input: path.resolve(__dirname, '../theme/assets/styles/main.css'),
      output: path.resolve(__dirname, '../theme/assets/dist/main.css'),
    },
    {
      input: path.resolve(__dirname, '../theme/assets/styles/editor-style.css'),
      output: path.resolve(__dirname, '../theme/assets/dist/editor-style.css'),
    },
  ],
  browserSyncConfig: {
    proxy: process.env.BROWSER_SYNC_PROXY || 'http://default.local',
    files: [
      path.resolve(__dirname, '../theme/**/*.{twig,php,css,js}'),
    ],
    injectChanges: true,
    notify: false,
    open: false,
  },
};
