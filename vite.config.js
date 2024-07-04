import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import inject from '@rollup/plugin-inject';
import {viteStaticCopy} from 'vite-plugin-static-copy'

import fs from 'fs-extra';
import path from 'path';

const folder = {
    src: "resources/",
    src_assets: "resources/",
    dist: "public/",
    dist_assets: "public/build/"
};

export default defineConfig({
    build: {
        chunkSizeWarningLimit: 5120,
        manifest: "manifest.json",
        assetsInlineLimit: 0,
        rtl: true,
        cssCodeSplit: true,
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    const extType = assetInfo.name.split('.').pop();
                    if (extType === 'css') {
                        return 'css/[name]-[hash].min.[ext]';
                    } else if (extType === 'js') {
                        return 'js/[name]-[hash].min.[ext]';
                    } else {
                        return '[ext]/[name]-[hash].[ext]';
                    }
                },
                chunkFileNames: 'js/[name].min.js',
                entryFileNames: 'js/[name].min.js',
                globals: {
                    jquery: 'jQuery',
                    $: 'jQuery'
                }
            },
        },
    },
    plugins: [
        inject({
            $: 'jquery',
        }),
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/appWithoutNav.js',
                'resources/js/plugins.js',
                'resources/js/layout.js',
                'resources/css/bootstrap-rtl.css',
                'resources/css/icons.css',
                'resources/css/icons-rtl.css',
                'resources/css/bootstrap.min.css',
                'resources/css/app.css',
                'resources/css/app-rtl.css',
                'resources/js/intlTelInput.js',
                'resources/js/turbo.js',
                'resources/js/sweetalert2@11.js',
                'resources/css/select2.min.css',
                'resources/fontawesome/all.min.css',
                'resources/css/intlTelInput.min.css',
                'resources/css/custom.css',
                'resources/css/custom-rtl.css',
                'resources/anychart/anychart-base.min.js',
                'resources/anychart/anychart-circular-gauge.min.js',
                'resources/anychart/anychart-data-adapter.min.js',
                'resources/anychart/anychart-exports.min.js',
                'resources/anychart/anychart-font.min.css',
                'resources/anychart/anychart-map.min.js',
                'resources/anychart/anychart-sankey.min.js',
                'resources/anychart/anychart-tag-cloud.min.js',
                'resources/anychart/anychart-ui.min.css',
                'resources/anychart/anychart-ui.min.js',
                'resources/anychart/proj4.js',
                'resources/anychart/world.js',
                'resources/css/bootstrap-rtl.css',
                'resources/anychart/anychart-table.min.js',
                'resources/js/livewire-turbolinks.js',
                'resources/js/pages/form-validation.init.js',
                'resources/js/pages/crypto-kyc.init.js'
            ],
            refresh: true,
            server: {
                host: '0.0.0.0',
                hmr: {
                    host: 'localhost'
                }
            },
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'resources/js/utils.js',
                    dest: 'utils.js'
                }
            ]
        }),
        {
            name: 'copy-specific-packages',
            async writeBundle() {
                try {
                    await Promise.all([
                        fs.copy(folder.src_assets + 'fonts', folder.dist_assets + 'fonts'),
                        fs.copy(folder.src_assets + 'images', folder.dist_assets + 'images'),
                        fs.copy(folder.src_assets + 'json', folder.dist_assets + 'json'),
                        fs.copy(folder.src_assets + 'img', folder.dist_assets + 'img'),
                        fs.copy(folder.src_assets + 'icons', folder.dist_assets + 'icons'),
                        fs.copy(folder.src_assets + 'fontawesome', folder.dist_assets + 'fontawesome'),
                        fs.copy(folder.src_assets + 'webfonts', folder.dist_assets + 'webfonts'),
                    ]);
                } catch (error) {
                    console.error('Error copying assets:', error);
                }

                const outputPath = path.resolve(__dirname, folder.dist_assets);
                const configPath = path.resolve(__dirname, 'package-copy-config.json');

                try {
                    const configContent = await fs.readFile(configPath, 'utf-8');
                    const {packagesToCopy} = JSON.parse(configContent);

                    for (const packageName of packagesToCopy) {
                        const destPackagePath = path.join(outputPath, 'libs', packageName)
                        const sourcePath = fs.existsSync(path.join(__dirname, 'node_modules', packageName + "/dist")) ?
                            path.join(__dirname, 'node_modules', packageName + "/dist")
                            : path.join(__dirname, 'node_modules', packageName);

                        try {
                            await fs.access(sourcePath, fs.constants.F_OK);
                            await fs.copy(sourcePath, destPackagePath);
                        } catch (error) {
                            console.error(`Package ${packageName} does not exist.`);
                        }
                    }
                } catch (error) {
                    console.error('Error copying and renaming packages:', error);
                }
            },
        },
    ],

});
