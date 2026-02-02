const fs = require('fs');
const path = require('path');

const SRC_DIR = path.join(__dirname, 'src');
const DIST_DIR = path.join(__dirname, 'dist');

// Clean and recreate dist directory
if (fs.existsSync(DIST_DIR)) {
    fs.rmSync(DIST_DIR, { recursive: true });
}
fs.mkdirSync(DIST_DIR, { recursive: true });

// Read partials
const partials = {};
const partialsDir = path.join(SRC_DIR, 'partials');
fs.readdirSync(partialsDir).forEach(file => {
    const name = path.basename(file, '.html');
    partials[name] = fs.readFileSync(path.join(partialsDir, file), 'utf8');
});

// Process pages
const pagesDir = path.join(SRC_DIR, 'pages');
fs.readdirSync(pagesDir).forEach(file => {
    let content = fs.readFileSync(path.join(pagesDir, file), 'utf8');
    
    // Replace partial placeholders
    Object.keys(partials).forEach(name => {
        const placeholder = `<!-- PARTIAL:${name} -->`;
        content = content.replace(placeholder, partials[name]);
    });
    
    fs.writeFileSync(path.join(DIST_DIR, file), content);
});

// Copy assets directory
const srcAssets = path.join(SRC_DIR, 'assets');
const distAssets = path.join(DIST_DIR, 'assets');

function copyDir(src, dest) {
    fs.mkdirSync(dest, { recursive: true });
    const entries = fs.readdirSync(src, { withFileTypes: true });
    
    for (const entry of entries) {
        const srcPath = path.join(src, entry.name);
        const destPath = path.join(dest, entry.name);
        
        if (entry.isDirectory()) {
            copyDir(srcPath, destPath);
        } else {
            fs.copyFileSync(srcPath, destPath);
        }
    }
}

copyDir(srcAssets, distAssets);

console.log('Build complete! Output in dist/');
