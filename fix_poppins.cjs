const fs = require('fs');
const path = require('path');

const targetDirs = ['app', 'resources', 'routes', 'database', 'bootstrap', 'public'];
const targetFiles = ['live_site.html'];

const replacements = {
    'Poppinsnational': 'International',
    'Poppinsface': 'Interface',
    'Poppinsval': 'Interval',
    'Poppinsactive': 'Interactive',
    'Poppinsest': 'Interest',
    'Poppinsrupt': 'Interrupt',
    'Poppinslaken': 'Interlaken',
    'Poppinsnal': 'Internal',
    'Poppinsnet': 'Internet',
    'Poppinssect': 'Intersect',
    'Poppinsview': 'Interview',
    'Poppinsceptor': 'Interceptor'
};

function processFile(filePath) {
    try {
        let content = fs.readFileSync(filePath, 'utf8');
        let modified = false;
        
        for (const [bad, good] of Object.entries(replacements)) {
            if (content.includes(bad)) {
                content = content.split(bad).join(good);
                modified = true;
            }
        }
        
        if (modified) {
            fs.writeFileSync(filePath, content, 'utf8');
            console.log(`Fixed: ${filePath}`);
        }
    } catch (e) {
        // Skip binary or unreadable files
    }
}

function processDir(dirPath) {
    const entries = fs.readdirSync(dirPath, { withFileTypes: true });
    for (const entry of entries) {
        const fullPath = path.join(dirPath, entry.name);
        if (entry.isDirectory()) {
            if (!['node_modules', 'vendor', '.git'].includes(entry.name)) {
                processDir(fullPath);
            }
        } else {
            if (['.php', '.html', '.js', '.css'].includes(path.extname(entry.name))) {
                processFile(fullPath);
            }
        }
    }
}

// Run for specific dirs
targetDirs.forEach(dir => {
    const fullPath = path.join(__dirname, dir);
    if (fs.existsSync(fullPath)) {
        processDir(fullPath);
    }
});

// Run for specific files
targetFiles.forEach(file => {
    const fullPath = path.join(__dirname, file);
    if (fs.existsSync(fullPath)) {
        processFile(fullPath);
    }
});

console.log('Cleanup completed.');
