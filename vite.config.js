import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            // refresh: [
            //     'resources/views/**', // Default view directory
            //     'resources/views/admin/**', // Include your custom admin views
            // ],
            refresh:true,
        }),
    ],
});
