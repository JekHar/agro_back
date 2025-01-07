import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite'
import { glob } from 'glob';
import laravelTranslations from 'vite-plugin-laravel-translations';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/main.scss',
                'resources/sass/oneui/themes/amethyst.scss',
                'resources/sass/oneui/themes/city.scss',
                'resources/sass/oneui/themes/flat.scss',
                'resources/sass/oneui/themes/modern.scss',
                'resources/sass/oneui/themes/smooth.scss',
                'resources/js/oneui/app.js',
                'resources/js/pages/datatables.js',
                ...glob.sync('resources/js/input-validators/*.js'),
                ...glob.sync('/resources/js/map/*.js'),
            ],
            refresh: true,
        }),
        laravelTranslations({
            includeJson: false,
            namespace: false,
            langFiles: ['en', 'es']
        })
    ],
});
