import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/lib/bootstrap/scss/bootstrap-icons.scss','resources/lib/bootstrap/js/bootstrap.bundle.min.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/',
        },
    },
});
