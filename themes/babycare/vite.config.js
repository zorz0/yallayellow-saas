import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";



export default defineConfig({
    plugins: [
        laravel({
            input: [
                "themes/babycare/sass/app.scss",
                "themes/babycare/js/app.js"
            ],
            buildDirectory: "babycare",
        }),
        
        
        {
            name: "blade",
            handleHotUpdate({ file, server }) {
                if (file.endsWith(".blade.php")) {
                    server.ws.send({
                        type: "full-reload",
                        path: "*",
                    });
                }
            },
        },
    ],
    resolve: {
        alias: {
            '@': '/themes/babycare/js',
            '~bootstrap': path.resolve('node_modules/bootstrap'),
        }
    },
    
});
