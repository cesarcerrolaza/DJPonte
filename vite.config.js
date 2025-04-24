import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        https: true,
    },
    build: {
        manifest: true,
        outDir: 'app/public/build', // Asegúrate de que coincida con donde Laravel está buscando
    }
});
