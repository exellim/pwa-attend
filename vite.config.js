import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0', // Allow access from your network
        port: 5173, // Ensure this matches your Vite server port
        strictPort: true,
        cors: {
            origin: '*', // Allow all origins (for debugging, restrict in production)
            methods: ['GET', 'POST', 'PUT', 'DELETE'],
            credentials: true
        }
    }
});
