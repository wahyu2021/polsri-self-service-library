import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/student/logbook.js',
                'resources/js/student/borrow.js',
                'resources/js/admin/loan-create.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
