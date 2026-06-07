
import fs from 'fs';

const debugFile = 'storage/logs/checkpointspot_events.json';

try {
    const rawData = fs.readFileSync(debugFile, 'utf8');
    const data = JSON.parse(rawData);

    // Support both formats (direct objects or wrapper)
    const html = data.html || data.raw_html || "";

    if (!html) {
        console.log("No HTML found in file.");
        process.exit(0);
    }

    // Find all class="..."
    const classRegex = /class=["']([^"']+)["']/g;
    let match;
    const classes = {};

    while ((match = classRegex.exec(html)) !== null) {
        const classNames = match[1].split(/\s+/);
        classNames.forEach(cls => {
            classes[cls] = (classes[cls] || 0) + 1;
        });
    }

    // Sort by count
    const sorted = Object.entries(classes).sort((a, b) => b[1] - a[1]);

    console.log("Page Title:", data.title);
    console.log("Top 50 classes:");
    sorted.slice(0, 50).forEach(([cls, count]) => console.log(`${cls}: ${count}`));

    console.log("\nContext around 'event':");
    const eventRegex = /.{0,100}event.{0,100}/gi;
    let eventMatch;
    let count = 0;
    while ((eventMatch = eventRegex.exec(html)) !== null && count < 10) {
        console.log(eventMatch[0]);
        count++;
    }

} catch (e) {
    console.error(e);
}
