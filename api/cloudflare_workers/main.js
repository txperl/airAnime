export default {
    async fetch(request, env) {
        // if (request.method !== "POST")
        //   return new Response("post only", { status: 400 });
        return await handleRequest(request);
    }
}

const SUB_URLS = {
    file: "https://raw.githubusercontent.com/txperl/airAnime/master/api/_examples/data/{kt}",
    agefans: "https://www.agefans.la/search?query={kt}",
    mikanani: "https://mikanani.me/Home/Search?searchstr={kt}",
    copymanga: "https://www.copymanga.tv/api/v3/search/comic?format=json&platform=1&limit=10&offset=1&q={kt}",
    koxmoe: "https://airanime-koxmoe-fetch.txperl.workers.dev/?keyword={kt}",
    dmzj: "https://sacg.dmzj.com/comicsum/search.php?s={kt}",
};

async function handleRequest(request) {
    const url = new URL(request.url);
    const paths = url.pathname.split("/").slice(2);

    if (paths.length <= 1)
        return new Response(JSON.stringify(SUB_URLS), {
            headers: {
                "Content-Type": "application/json; charset=UTF-8"
            }
        });

    const subName = paths[0];
    const kt = paths.slice(1).join("");

    if (!SUB_URLS[subName] || !kt)
        return new Response("missing subname or keyword", { status: 400 });

    const finalUrl = SUB_URLS[subName].replace("{kt}", kt);

    let response = await fetch(finalUrl, {
        headers: {
            "Referer": finalUrl,
            "User-Agent": "Cloudflare Workers",
            "platform": "1",
        }
    });

    response = new Response(response.body, response);
    response.headers.set("Access-Control-Allow-Headers", "*");
    response.headers.set("Access-Control-Allow-Origin", "*");
    response.headers.set("Vary", "Origin");
    if (response.headers.get("content-type").includes("json")) {
        response.headers.set("Content-Type", "application/json; charset=utf-8");
    } else {
        response.headers.set("Content-Type", "text/plain; charset=utf-8");
    }
    return response;
}
