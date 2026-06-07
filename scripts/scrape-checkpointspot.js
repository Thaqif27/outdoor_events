
import puppeteer from 'puppeteer-extra';
import StealthPlugin from 'puppeteer-extra-plugin-stealth';
import fs from 'fs';

puppeteer.use(StealthPlugin());

const url = 'https://checkpointspot.asia/events';
const outputFile = 'storage/logs/checkpointspot_events.json';

(async () => {
    const browser = await puppeteer.launch({
        headless: "new",
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--window-size=1920,1080']
    });

    const page = await browser.newPage();
    await page.setViewport({ width: 1920, height: 1080 });

    try {
        await page.goto(url, { waitUntil: 'networkidle2', timeout: 60000 });

        try {
            await page.waitForFunction(() => document.title !== 'Just a moment...', { timeout: 30000 });
        } catch (e) {
            // Ignored
        }

        // Wait for client side rendering
        await new Promise(r => setTimeout(r, 5000));

        const events = await page.evaluate(() => {
            const items = [];
            // Select event links based on analysis
            // The analysis showed links like <a href=".../event/..." class="text-white ...">
            const links = document.querySelectorAll('a[href*="/event/"]');

            links.forEach(link => {
                const name = link.innerText.trim();
                const url = link.href;

                // Skip empty names or irrelevant links
                if (!name || name.length < 3) return;

                // Traverse up to find the card container
                // The link is usually in a text container which is a sibling of the image
                // Structure: Card > [ImageContainer, TextContainer > Name]
                // We want to find the Card

                let textContainer = link.closest('div.flex.flex-col');
                let card = textContainer ? textContainer.parentElement : link.closest('div');

                // Find image in the card or ancestors
                let image = null;

                // Helper to search image in an element
                const findImg = (el) => {
                    if (!el) return null;
                    const img = el.querySelector('img');
                    return img ? (img.src || img.getAttribute('data-src')) : null;
                };

                if (card) image = findImg(card);

                // Fallback: if no image in parent, try 2 levels up if we started from link directly
                if (!image && link.parentElement && link.parentElement.parentElement) {
                    const grandParent = link.parentElement.parentElement.parentElement;
                    image = findImg(grandParent);
                }

                // Extract raw text for date parsing
                const description = card ? card.innerText : (textContainer ? textContainer.innerText : "");

                // Avoid duplicates
                if (!items.find(i => i.url === url)) {
                    items.push({
                        name,
                        url,
                        image,
                        description,
                        location: 'Malaysia',
                    });
                }
            });
            return items;
        });

        fs.writeFileSync(outputFile, JSON.stringify(events, null, 2));
        console.log("Extracted " + events.length + " events to " + outputFile);

    } catch (err) {
        console.error("Error:", err);
    } finally {
        await browser.close();
    }
})();
