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
const rands = Math.random().toString(36).slice(2, 7);

export default defineConfig({
    css: {
        devSourcemap: false,
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler',
                silenceDeprecations: ['color-functions', 'import', 'mixed-decls', 'global-builtin']
            }
        }
    },
    build: {
        chunkSizeWarningLimit: 5120,
        manifest: "manifest.json",
        assetsInlineLimit: 0,
        rtl: true,
        cssCodeSplit: true,
        sourcemap: false,
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    const extType = assetInfo.name.split('.').pop();
                    if (extType === 'css') {
                        return 'assets/css/[name]-[hash]-' + rands + '.min.[ext]';
                    } else if (extType === 'js') {
                        return 'assets/js/[name]-[hash]-' + rands + '.min.[ext]';
                    } else {
                        return 'assets/[ext]/[name]-[hash]-' + rands + '.[ext]';
                    }
                },
                chunkFileNames: 'assets/js/[name]-[hash]-' + rands + '.min.js',
                entryFileNames: 'assets/js/[name]-[hash]-' + rands + '.min.js',
            },
        },
    },
    plugins: [
        inject({
            $: 'jquery',
            include: ['**/*.{js,jsx,ts,tsx,vue}', 'resources/js/**'],
            exclude: ['**/*.css', '**/*.scss', '**/*.sass', '**/*.less']
        }),
        laravel({
            input: [
                'resources/css/tailwind.css',
                'resources/css/modern-enhancements.css',
                'resources/css/modern-enhancements-rtl.css',
                'resources/sass/app.scss',
                'resources/css/dataTables.bootstrap.css',
                'resources/css/rowReorder.bootstrap.css',
                'resources/css/material-components-web.min.css',
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
                'resources/css/menumodals.css',
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
                'resources/js/pages/form-validation.init.js',
                'resources/js/pages/crypto-kyc.init.js',
                'resources/js/surveys.js',
                'resources/js/pages/passowrd-create.init.js',
                'resources/libs/apexcharts/apexcharts.min.js',
                'resources/js/ckeditor.js',
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
                },
                {
                    src: 'resources/icons/wired-gradient-751-share.json',
                    dest: 'icons'
                },
                {
                    src: 'resources/icons/146-basket-trolley-shopping-card-gradient-edited.json',
                    dest: 'icons'
                },
                {
                    src: 'resources/icons/981-consultation-gradient-edited.json',
                    dest: 'icons'
                },
                {
                    src: 'resources/icons/501-free-0-morph-gradient-edited.json',
                    dest: 'icons'
                },
                {
                    src: 'resources/icons/298-coins-gradient-edited.json',
                    dest: 'icons'
                },
                {
                    src: 'resources/icons/qrbokoyz.json',
                    dest: 'icons'
                },
                {
                    src: 'resources/icons/nlmjynuq.json',
                    dest: 'icons'
                },
                {
                    src: 'resources/icons/1855-palmtree.json',
                    dest: 'icons'
                },
                {
                    src: 'resources/icons/1471-dice-cube.json',
                    dest: 'icons'
                }
            ]
        }),
        {
            name: 'copy-specific-packages',
            async buildStart() {
                // Copy assets during dev mode
                try {
                    await fs.ensureDir(folder.dist_assets);
                    await Promise.all([
                        fs.copy(folder.src_assets + 'fonts', folder.dist_assets + 'fonts'),
                        fs.copy(folder.src_assets + 'images', folder.dist_assets + 'images'),
                        fs.copy(folder.src_assets + 'json', folder.dist_assets + 'json'),
                        fs.copy(folder.src_assets + 'img', folder.dist_assets + 'img'),
                        fs.copy(folder.src_assets + 'icons', folder.dist_assets + 'icons'),
                        fs.copy(folder.src_assets + 'fontawesome', folder.dist_assets + 'fontawesome'),
                        fs.copy(folder.src_assets + 'webfonts', folder.dist_assets + 'webfonts'),
                    ]);
                    console.log('✓ Static assets copied successfully');
                } catch (error) {
                    console.error('Error copying assets during buildStart:', error);
                }
            },
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
