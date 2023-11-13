import { defineConfig, loadEnv } from 'vite'
import liveReload from 'vite-plugin-live-reload'

const dest = './theme/dist'
const entries = [
  './theme/assets/main.js',
  './theme/assets/styles/editor-style.css',
]
const watchFiles = [
  './theme/*.php',
  './theme/views/**/*',
  './theme/blocks/**/*.{php,twig}'
]

export default defineConfig(({ mode }) => {
  return {
    base: './',
    resolve: {
      alias: {
        '@': __dirname
      }
    },
    plugins: [liveReload(watchFiles)],
    server: {
      cors: true,
      strictPort: true,
      port: 3000,
      https: false,
      hmr: {
        host: 'localhost',
      }
    },
    build: {
      outDir: dest,
      emptyOutDir: true,
      manifest: true,
      target: 'es2018',
      rollupOptions: {
        input: entries,
      },
      minify: true,
      write: true
    }
  }
})
