import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";



export default defineConfig({
    plugins: [
        laravel({
            input: [
                "themes\giftsforher\sass/app.scss",
                "themes\giftsforher\js/app.js"
            ],
            buildDirectory: "giftsforher",
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
            '@': '/themes\giftsforher\js',
            '~bootstrap': path.resolve('node_modules/bootstrap'),
        }
    },
    
});
