import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig(({ command }) => {
    return {
        base: command === 'build'
            ? 'https://heuristic-report.onrender.com/' // production
            : '/', // local dev
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
        ],
        server: {
            watch: {
                ignored: ['**/storage/framework/views/**'],
            },
        },
    };
});
