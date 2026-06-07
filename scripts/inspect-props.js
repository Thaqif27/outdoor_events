
import fs from 'fs';

const debugFile = 'storage/logs/checkpointspot_events.json';

try {
    const rawData = fs.readFileSync(debugFile, 'utf8');
    let json = JSON.parse(rawData);

    if (json.props && json.props.pageProps) {
        console.log("Keys in props.pageProps:");
        console.log(Object.keys(json.props.pageProps));

        const pp = json.props.pageProps;
        if (pp.events) console.log("Found props.pageProps.events (Type: " + typeof pp.events + ")");
        if (pp.searchResults) console.log("Found props.pageProps.searchResults");
        if (pp.data) console.log("Found props.pageProps.data");
        if (pp.eventList) console.log("Found props.pageProps.eventList");

        // Check initialLocale just to verify depth
        console.log("initialLocale:", pp.initialLocale);

    } else {
        console.log("props.pageProps not found");
        console.log("Root keys:", Object.keys(json));
    }

} catch (e) {
    console.error(e);
}
