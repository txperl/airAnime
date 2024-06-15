import { parse } from "url";

const SUB_URLS = {
    file: "https://raw.githubusercontent.com/txperl/airAnime/master/api/_examples/data/{kt}",
    agefans: "https://www.agedm.org/search?query={kt}",
    nicotv: "http://www.nicotv.wtf/video/search/{kt}.html",
    zzzfun: "http://www.zzzfun.one/vod_search.html?wd={kt}",
    mikanani: "https://mikanani.me/Home/Search?searchstr={kt}",
    copymanga: "https://www.copymanga.tv/api/v3/search/comic?format=json&platform=1&limit=10&offset=1&q={kt}",
    koxmoe: "https://kox.moe/list.php?s={kt}",
    dmzj: "https://sacg.dmzj.com/comicsum/search.php?s={kt}",
};

export default async function handler(req, res) {
    const paths = parse(req.url).pathname.split("/").slice(2);

    if (paths.length <= 1)
        return res.json(SUB_URLS)

    const subName = paths[0];
    const kt = paths.slice(1).join("");

    if (!SUB_URLS[subName] || !kt)
        return res.status(400) && res.send("missing subname or keyword");

    const finalUrl = SUB_URLS[subName].replace("{kt}", kt);

    process.env.NODE_TLS_REJECT_UNAUTHORIZED = "0";
    const response = await fetch(finalUrl, {
        headers: {
            "Referer": finalUrl,
            "User-Agent": "Vercel Serverless Functions",
            "platform": "1",
        }
    });

    res.setHeader("Access-Control-Allow-Headers", "*");
    res.setHeader("Access-Control-Allow-Origin", "*");
    res.setHeader("Vary", "Origin");
    if (response.headers.get("content-type").includes("json")) {
        res.setHeader("Content-Type", "application/json; charset=utf-8");
    } else {
        res.setHeader("Content-Type", "text/plain; charset=utf-8");
    }
    return res.send(await response.text());
}