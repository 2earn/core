const fs = require('fs');
const path = require('path');
const readline = require('readline');

console.log('\x1b[36m=== Delete Unused Images ===\x1b[0m\n');

// Check if report exists
if (!fs.existsSync('unused-images-report.json')) {
    console.log('\x1b[31mError: unused-images-report.json not found!\x1b[0m');
    console.log('Please run "node find-unused-images.js" first.\n');
    process.exit(1);
}

// Load report
const report = JSON.parse(fs.readFileSync('unused-images-report.json', 'utf8'));

console.log(`Found ${report.unused} unused images (${report.totalSizeFormatted})\n`);

if (report.unused === 0) {
    console.log('\x1b[32mNo unused images to delete! 🎉\x1b[0m\n');
    process.exit(0);
}

// Show summary
console.log('\x1b[33mUnused images by directory:\x1b[0m');
const grouped = {};
report.unusedImages.forEach(img => {
    const dir = path.dirname(img.path);
    if (!grouped[dir]) grouped[dir] = [];
    grouped[dir].push(img);
});

Object.keys(grouped).sort().forEach(dir => {
    const dirSize = grouped[dir].reduce((sum, img) => sum + img.size, 0);
    console.log(`  ${dir}: ${grouped[dir].length} images (${formatBytes(dirSize)})`);
});

console.log('');

// Helper function
function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// Create readline interface
const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

// Ask for confirmation
rl.question('\x1b[33mDo you want to delete these unused images? (yes/no): \x1b[0m', (answer) => {
    if (answer.toLowerCase() === 'yes' || answer.toLowerCase() === 'y') {
        console.log('');
        console.log('\x1b[33mCreating backup...\x1b[0m');

        // Create backup directory
        const timestamp = new Date().toISOString().replace(/[:.]/g, '-').split('T')[0] + '-' +
                         new Date().toTimeString().split(' ')[0].replace(/:/g, '');
        const backupDir = `unused-images-backup-${timestamp}`;

        if (!fs.existsSync(backupDir)) {
            fs.mkdirSync(backupDir, { recursive: true });
        }

        // Copy images to backup
        report.unusedImages.forEach(img => {
            const relativePath = img.path;
            const backupPath = path.join(backupDir, relativePath);
            const backupFolder = path.dirname(backupPath);

            if (!fs.existsSync(backupFolder)) {
                fs.mkdirSync(backupFolder, { recursive: true });
            }

            try {
                fs.copyFileSync(img.path, backupPath);
            } catch (err) {
                console.log(`\x1b[31mFailed to backup: ${img.path}\x1b[0m`);
            }
        });

        console.log(`\x1b[32mBackup created: ${backupDir}\x1b[0m\n`);
        console.log('\x1b[33mDeleting unused images...\x1b[0m');

        // Delete images
        let deletedCount = 0;
        let failedCount = 0;

        report.unusedImages.forEach(img => {
            try {
                if (fs.existsSync(img.path)) {
                    fs.unlinkSync(img.path);
                    deletedCount++;
                }
            } catch (err) {
                console.log(`\x1b[31mFailed to delete: ${img.path}\x1b[0m`);
                failedCount++;
            }
        });

        console.log('');
        console.log(`\x1b[32mDeleted ${deletedCount} images (${report.totalSizeFormatted} freed)\x1b[0m`);

        if (failedCount > 0) {
            console.log(`\x1b[31mFailed to delete ${failedCount} images\x1b[0m`);
        }

        console.log(`\x1b[36mBackup location: ${backupDir}\x1b[0m\n`);
        console.log('\x1b[32m✓ Complete!\x1b[0m\n');

        // Clean up empty directories
        console.log('\x1b[33mCleaning up empty directories...\x1b[0m');
        cleanEmptyDirectories('resources/images');
        cleanEmptyDirectories('resources/img');
        console.log('\x1b[32mDone!\x1b[0m\n');

    } else {
        console.log('\x1b[33mNo images deleted.\x1b[0m\n');
    }

    rl.close();
});

// Function to clean empty directories
function cleanEmptyDirectories(directory) {
    if (!fs.existsSync(directory)) return;

    const files = fs.readdirSync(directory);

    if (files.length > 0) {
        files.forEach(file => {
            const filePath = path.join(directory, file);
            if (fs.statSync(filePath).isDirectory()) {
                cleanEmptyDirectories(filePath);
            }
        });

        // Check again after recursion
        const filesAfter = fs.readdirSync(directory);
        if (filesAfter.length === 0) {
            try {
                fs.rmdirSync(directory);
                console.log(`  Removed empty directory: ${directory}`);
            } catch (err) {
                // Directory might not be empty or in use
            }
        }
    } else {
        try {
            fs.rmdirSync(directory);
            console.log(`  Removed empty directory: ${directory}`);
        } catch (err) {
            // Directory might be in use
        }
    }
}

