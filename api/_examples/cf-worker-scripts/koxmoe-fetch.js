export default {
    async fetch(request, env, ctx) {
        const url = new URL(request.url);
        const keywords = url.searchParams.get("keyword");

        // !! Secrets !!
        // !! You can set secrets at `[Cloudflare Worker Dashboard]-[Settings]-[Variables and Secrets]` !!
        if (!env || !env.VOLSESS_SECRET || !env.VOLSKEY_SECRET) {
            console.error("VOLSESS_SECRET and VOLSKEY_SECRET are not configured.");
            return new Response("Secrets not set.", { status: 500 });
        }
        const volSessValue = env.VOLSESS_SECRET;
        const volKeyValue = env.VOLSKEY_SECRET;

        if (!keywords) {
            return new Response(JSON.stringify({ error: "Missing \"keyword\" query parameter" }), {
                status: 400,
                headers: { "Content-Type": "application/json" },
            });
        }

        // Prepare fetch options
        const targetUrl = `https://kox.moe/list.php?s=${encodeURIComponent(keywords)}`;
        const cookieString = `VOLSESS=${volSessValue}; VOLSKEY=${volKeyValue}`;
        const fetchOptions = {
            method: "GET",
            headers: {
                "Cookie": cookieString,
                "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
                "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                "Accept-Language": request.headers.get("Accept-Language") || "en-US,en;q=0.5",
            },
            redirect: "follow"
        };

        try {
            // Fetch the HTML content from the target server
            const response = await fetch(targetUrl, fetchOptions);

            // Check if the fetch was successful
            if (!response.ok) {
                const errorText = await response.text();
                console.error(`Origin server error: ${response.status} ${response.statusText} - ${errorText}`);
                return new Response(JSON.stringify({ error: `Origin server error: ${response.status}` }), {
                    status: response.status,
                    headers: { "Content-Type": "application/json" },
                });
            }

            // Get the HTML content as text
            const htmlText = await response.text();

            // Parse the HTML

            // Group 1: book_url (the link)
            // Group 2: book_name (the title)
            const regex = /disp_divinfo\(\s*[^,]+\s*,\s*"([^"]+)"\s*,\s*"[^"]*"\s*,\s*"[^"]*"\s*,\s*"[^"]*"\s*,\s*"[^"]*"\s*,\s*"[^"]*"\s*,\s*"[^"]*"\s*,\s*"[^"]*"\s*,\s*"([^"]+)"/g;

            const results = [];
            let match;
            while ((match = regex.exec(htmlText)) !== null) {
                if (match[1] && match[2]) {
                    results.push({
                        title: match[2].replaceAll(new RegExp("\</?b\>", "gm"), ""), // The captured book name
                        link: match[1]   // The captured book URL
                    });
                }
            }

            // Convert to a JSON string
            const jsonResponse = JSON.stringify(results);

            // Return
            return new Response(jsonResponse, {
                headers: {
                    "Content-Type": "application/json;charset=UTF-8",
                    "Access-Control-Allow-Origin": "*"
                },
                status: 200
            });

        } catch (error) {
            console.error(`Worker error processing request for ${targetUrl}: ${error}`);
            return new Response(JSON.stringify({ error: `Worker execution failed` }), {
                status: 500,
                headers: { "Content-Type": "application/json" },
            });
        }
    },
}
