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
            const rep = await this._genGetFunc()(this.searchUrl.replace("{kt}", keyword));
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
            await this._genGetFunc()(this.searchUrl.replace("{kt}", "jojo"));
        } catch (err) {
            return 0;
        }
        return 1;
    }

    _genRlist(title, url) {
        return {
            title: title,
            url: url,
            type: this.type,
            source: this.name,
            siteName: this.siteName
        };
    }

    _genGetFunc(timeout = 10000) {
        const source = axios.CancelToken.source();
        const timeoutFunc = setTimeout(() => {
            source.cancel();
            throw new Error("axios GET timeout");
        }, timeout);
        return async (url, options) => {
            const ops = { ...options, cancelToken: source.token };
            const rep = await axios.get(url, ops)
            clearTimeout(timeoutFunc);
            return rep;
        };
    }
}
