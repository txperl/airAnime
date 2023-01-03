import { genAxiosGetFunc, pureShowTitle } from "../../utils/ops.mjs"

export default class SourceOnline {
    constructor(name, siteUrl, searchUrl) {
        this.method = "online";
        [this.type, this.name, this.siteName] = name.split("/");
        this.siteUrl = siteUrl;
        this.searchUrl = searchUrl;
    }

    async get(keyword, amount) {
        let r;
        try {
            const rep = await genAxiosGetFunc()(this.searchUrl.replace("{kt}", keyword));
            r = await this.format(rep.data);
        } catch {
            r = [];
        }
        return amount ? r.slice(0, amount) : r;
    }

    async format() {
        return [];
    }

    async test() {
        try {
            await genAxiosGetFunc()(this.searchUrl.replace("{kt}", "jojo"));
        } catch (err) {
            return 0;
        }
        return 1;
    }

    _genRlist(title, url) {
        return {
            title: pureShowTitle(title),
            url: url,
            type: this.type,
            source: this.name,
            siteName: this.siteName
        };
    }
}
