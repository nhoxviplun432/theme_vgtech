// vite.config.mjs
import { defineConfig } from 'vite'
import { resolve } from 'path'

export default defineConfig({
  root: '.',
  build: {
    outDir: 'assets',
    emptyOutDir: false,
    rollupOptions: {
      input: {
        'turbo/js/app': resolve(process.cwd(), 'src/js/turbo.js'),
        'turbo/css/app': resolve(process.cwd(), 'src/css/turbo.css'),
      },
      output: {
        entryFileNames: (chunk) => {
          if (chunk.name === 'turbo/js/app') return 'turbo/js/app.bundle.js';
          return 'turbo/[name].js';
        },
        assetFileNames: (asset) => {
          if (asset.name && asset.name.endsWith('turbo/css/app.css')) {
            return 'turbo/css/app.bundle.css';
          }
          return 'turbo/[name][extname]';
        },
      },
    },
  },
});
