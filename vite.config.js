import { defineConfig, loadEnv } from 'vite'

const dest = './theme/assets/dist'
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
