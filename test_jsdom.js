const fs = require('fs');
const jsdom = require("jsdom");
const { JSDOM } = jsdom;
const html = fs.readFileSync('resources/views/packages/show.blade.php', 'utf8');

const dom = new JSDOM(`<!DOCTYPE html><html><head><script src="https://unpkg.com/lucide@latest"></script></head><body>${html}</body></html>`, { runScripts: "dangerously", resources: "usable" });

dom.window.onerror = function(msg) {
    console.log("ERROR:", msg);
}

setTimeout(() => {
    try {
        dom.window.lucide.createIcons();
        console.log("SUCCESS");
    } catch(e) {
        console.log("EXCEPTION:", e.message);
    }
}, 5000);
