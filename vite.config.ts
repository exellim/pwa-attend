import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(), // ✅ Load Tailwind first
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        manifest: true, // ✅ Ensures manifest.json is generated
        outDir: 'public/build',
        emptyOutDir: true,
    },
    server: {
        hmr: false, // Disable HMR in production
        watch: { usePolling: true },
    },
});
