import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs-extra';
import path from 'path';

const folder = {
    src: "resources/", // source files
    src_assets: "resources/", // source assets files
    dist: "public/", // build files
    dist_assets: "public/assets/" // build assets files
};

export default defineConfig({
    build: {
        manifest: true,
        rtl: true,
        outDir: folder.dist_assets,
        cssCodeSplit: true,
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    const extType = assetInfo.name.split('.').pop();
                    if (extType === 'css') {
                        return 'css/[name].min.[ext]';
                    } else if (extType === 'js') {
                        return 'js/[name].min.[ext]';
                    } else {
                        return 'icons/[name].[ext]';
                    }
                },
                chunkFileNames: 'js/[name].min.js',
                entryFileNames: 'js/[name].min.js',
            },
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/plugins.js',
                'resources/js/layout.js',
                'resources/css/bootstrap-rtl.css',
                'resources/css/icons.css',
                'resources/css/icons-rtl.css',
                'resources/css/app.css',
                'resources/css/app-rtl.css',
                'resources/css/intlTelInput.scss',
                'resources/js/intlTelInput.js',
                'resources/js/utils.js',
                'resources/js/turbo.js',
                'resources/js/sweetalert2@11.js',
                'resources/css/select2.min.css',
                'resources/css/custom.css',
                'resources/css/custom-rtl.css',
                'resources/js/jquery.js'
            ],
            refresh: true,
        }),
        {
            name: 'copy-specific-packages',
            async writeBundle() {
                try {
                    // Copy images, json, fonts, and js
                    await Promise.all([
                        fs.copy(folder.src_assets + 'fonts', folder.dist_assets + 'fonts'),
                        fs.copy(folder.src_assets + 'Styles', folder.dist_assets + 'Styles'),
                        fs.copy(folder.src_assets + 'images', folder.dist_assets + 'images'),
                        fs.copy(folder.src_assets + 'js', folder.dist_assets + 'js'),
                        fs.copy(folder.src_assets + 'json', folder.dist_assets + 'json'),
                        fs.copy(folder.src_assets + 'img', folder.dist_assets + 'img'),
                        fs.copy(folder.src_assets + 'icons', folder.dist_assets + 'icons'),
                        fs.copy(folder.src_assets + 'fontawesome', folder.dist_assets + 'fontawesome'),
                    ]);
                } catch (error) {
                    console.error('Error copying assets:', error);
                }

                const outputPath = path.resolve(__dirname, folder.dist_assets); // Adjust the destination path
                const configPath = path.resolve(__dirname, 'package-copy-config.json');

                try {
                    const configContent = await fs.readFile(configPath, 'utf-8');
                    const { packagesToCopy } = JSON.parse(configContent);

                    for (const packageName of packagesToCopy) {
                        const destPackagePath = path.join(outputPath, 'libs', packageName);

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
