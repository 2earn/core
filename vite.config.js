import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import inject from '@rollup/plugin-inject';
import { viteStaticCopy } from 'vite-plugin-static-copy'

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
                silenceDeprecations: ['color-functions', 'import', 'global-builtin']
            }
        }
    },
    server: {
        warmup: {
            clientFiles: [
                'resources/js/app.js',
                'resources/js/appWithoutNav.js',
                'resources/css/tailwind.css',
            ]
        }
    },
    optimizeDeps: {
        include: [
            'jquery',
            'bootstrap',
            'sweetalert2',
            'datatables.net',
            'datatables.net-bs5',
            'apexcharts',
            'moment',
            'select2',
            'intl-tel-input',
            'flatpickr',
            'simplebar',
            'sortablejs',
            'swiper'
        ],
        force: false
    },
    build: {
        chunkSizeWarningLimit: 5120,
        manifest: "manifest.json",
        assetsInlineLimit: 0,
        rtl: true,
        cssCodeSplit: true,
        sourcemap: false,
        minify: 'esbuild',
        target: 'es2015',
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
                manualChunks: {
                    'vendor': [
                        'jquery',
                        'bootstrap',
                        'moment'
                    ],
                    'datatables': [
                        'datatables.net',
                        'datatables.net-bs5',
                        'datatables.net-buttons',
                        'datatables.net-responsive-bs5'
                    ],
                    'charts': [
                        'apexcharts',
                        'chart.js',
                        'echarts'
                    ]
                }
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
                'resources/js/app.js',
                'resources/css/app.css',
            ],
            refresh: true,
            server: {
                host: '0.0.0.0',
                hmr: {
                    host: 'localhost'
                }
            },
        }),

        {
            name: 'copy-specific-packages',
            async closeBundle() {
                // Use closeBundle instead of buildStart to avoid duplicate work
                // Copy assets only once at the end of the build
                try {
                    await fs.ensureDir(folder.dist_assets);

                    // Copy assets in parallel for better performance
                    await Promise.all([
                        fs.copy(folder.src_assets + 'images', folder.dist_assets + 'images'),
                        fs.copy(folder.src_assets + 'icons', folder.dist_assets + 'icons'),
                    ]);
                    console.log('✓ Static assets copied successfully');
                } catch (error) {
                    console.error('Error copying assets:', error);
                }

                const outputPath = path.resolve(__dirname, folder.dist_assets);
                const configPath = path.resolve(__dirname, 'package-copy-config.json');

                try {
                    const configContent = await fs.readFile(configPath, 'utf-8');
                    const { packagesToCopy } = JSON.parse(configContent);

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
