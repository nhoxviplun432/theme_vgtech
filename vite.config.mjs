// vite.config.mjs
import { defineConfig } from 'vite'
import { resolve } from 'path'

export default defineConfig({
  root: '.',
  // Xuất ra thư mục gốc assets (bên trong có public/ và swup/)
  build: {
    outDir: 'assets',
    emptyOutDir: false,
    rollupOptions: {
      input: {
        // Build Swup riêng vào assets/swup/...
        'swup/js/app':  resolve(process.cwd(), 'src/js/app.js'),
        'swup/css/app': resolve(process.cwd(), 'src/css/app.css'),
        'swup/js/loader': resolve(process.cwd(), 'src/js/app.loader.js')
        // (nếu sau này muốn bundle khác vào assets/public, thêm entry kiểu 'public/js/main': 'src/js/main.js')
      },
      output: {
        // JS bundle cho swup
        entryFileNames: (chunk) => {
          if (chunk.name === 'swup/js/app') return 'swup/js/app.bundle.js'
          // fallback cho entry khác (vd public)
          return 'public/js/[name].js'
        },
        // CSS từ entry 'swup/css/app'
        assetFileNames: (asset) => {
          if (asset.name && asset.name.endsWith('swup/css/app.css')) {
            return 'swup/css/app.bundle.css'
          }
          // fallback cho asset khác (ảnh, font, css khác)
          return 'public/assets/[name][extname]'
        }
      }
    }
  }
})
