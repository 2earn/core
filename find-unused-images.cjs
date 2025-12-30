const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

console.log('\x1b[36m=== Finding Unused Images ===\x1b[0m\n');

// Configuration
const imageDirs = ['resources/images', 'resources/img', 'public'];
const imageExtensions = ['.png', '.jpg', '.jpeg', '.gif', '.svg', '.webp', '.ico'];
const searchDirs = ['resources/views', 'resources/js', 'resources/css', 'app', 'public'];
const excludeDirs = ['node_modules', 'vendor', 'storage', 'public/build', 'public/uploads'];

// Helper functions
function getAllFiles(dirPath, arrayOfFiles = [], extensions = null) {
    if (!fs.existsSync(dirPath)) return arrayOfFiles;

    const files = fs.readdirSync(dirPath);

    files.forEach(file => {
        const filePath = path.join(dirPath, file);

        // Skip excluded directories
        if (excludeDirs.some(exc => filePath.includes(exc))) {
            return;
        }

        if (fs.statSync(filePath).isDirectory()) {
            arrayOfFiles = getAllFiles(filePath, arrayOfFiles, extensions);
        } else {
            if (!extensions || extensions.some(ext => file.endsWith(ext))) {
                arrayOfFiles.push(filePath);
            }
        }
    });

    return arrayOfFiles;
}

function getFileSize(filePath) {
    const stats = fs.statSync(filePath);
    return stats.size;
}

function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// Step 1: Find all images
console.log('\x1b[33mScanning for images...\x1b[0m');
let allImages = [];

imageDirs.forEach(dir => {
    const images = getAllFiles(dir, [], imageExtensions);
    allImages = allImages.concat(images);
});

console.log(`\x1b[32mFound ${allImages.length} total images\x1b[0m\n`);

// Step 2: Get all code files
console.log('\x1b[33mScanning code files...\x1b[0m');
const codeExtensions = ['.php', '.blade.php', '.js', '.jsx', '.ts', '.tsx', '.css', '.scss', '.vue'];
let codeFiles = [];

searchDirs.forEach(dir => {
    const files = getAllFiles(dir, [], codeExtensions);
    codeFiles = codeFiles.concat(files);
});

console.log(`\x1b[32mFound ${codeFiles.length} code files to search\x1b[0m\n`);

// Step 3: Check which images are used
console.log('\x1b[33mChecking image usage...\x1b[0m');
const unusedImages = [];
const usedImages = [];
let processed = 0;

allImages.forEach(imagePath => {
    processed++;
    if (processed % 50 === 0) {
        console.log(`Progress: ${processed} / ${allImages.length}`);
    }

    const imageName = path.basename(imagePath);
    const imageBaseName = path.parse(imageName).name;
    let isUsed = false;

    // Search for image reference in code files
    for (const codeFile of codeFiles) {
        try {
            const content = fs.readFileSync(codeFile, 'utf8');

            // Check if image name or base name is referenced
            if (content.includes(imageName) || content.includes(imageBaseName)) {
                isUsed = true;
                break;
            }
        } catch (err) {
            // Skip files that can't be read
        }
    }

    if (isUsed) {
        usedImages.push(imagePath);
    } else {
        unusedImages.push(imagePath);
    }
});

// Step 4: Display results
console.log('\n\x1b[36m=== Results ===\x1b[0m\n');
console.log(`Total Images: ${allImages.length}`);
console.log(`\x1b[32mUsed Images: ${usedImages.length}\x1b[0m`);
console.log(`\x1b[31mUnused Images: ${unusedImages.length}\x1b[0m\n`);

if (unusedImages.length > 0) {
    console.log('\x1b[33m=== Unused Images ===\x1b[0m\n');

    // Group by directory
    const grouped = {};
    unusedImages.forEach(img => {
        const dir = path.dirname(img);
        if (!grouped[dir]) grouped[dir] = [];
        grouped[dir].push(img);
    });

    let totalSize = 0;

    Object.keys(grouped).sort().forEach(dir => {
        console.log(`\x1b[36mDirectory: ${dir}\x1b[0m`);
        grouped[dir].forEach(img => {
            const size = getFileSize(img);
            totalSize += size;
            console.log(`  - ${path.basename(img)} (${formatBytes(size)})`);
        });
        console.log('');
    });

    console.log(`\x1b[33mTotal unused images size: ${formatBytes(totalSize)}\x1b[0m\n`);

    // Save report
    const report = unusedImages.map(img => {
        const size = getFileSize(img);
        return `${img} (${formatBytes(size)})`;
    }).join('\n');

    fs.writeFileSync('unused-images-report.txt', report, 'utf8');
    console.log('\x1b[32mReport saved to: unused-images-report.txt\x1b[0m\n');

    // Save as JSON for easier processing
    const jsonReport = {
        total: allImages.length,
        used: usedImages.length,
        unused: unusedImages.length,
        totalSize: totalSize,
        totalSizeFormatted: formatBytes(totalSize),
        unusedImages: unusedImages.map(img => ({
            path: img,
            size: getFileSize(img),
            sizeFormatted: formatBytes(getFileSize(img))
        }))
    };

    fs.writeFileSync('unused-images-report.json', JSON.stringify(jsonReport, null, 2), 'utf8');
    console.log('\x1b[32mJSON report saved to: unused-images-report.json\x1b[0m\n');

    console.log('\x1b[33mTo delete unused images, run:\x1b[0m');
    console.log('  node delete-unused-images.js\n');

} else {
    console.log('\x1b[32mAll images are being used! 🎉\x1b[0m\n');
}

console.log('\x1b[36m=== Complete ===\x1b[0m');

