import { genAxiosGetFunc } from "../../utils/ops.mjs"

export default class SourceDB {
    constructor(name, siteUrl, dbUrl) {
        this._db = localforage;
        this._lang = "ja";
        this._data = null;
        this.method = "db";
        this.latestTime = null;
        [this.type, this.name, this.siteName] = name.split("/");
        this.siteUrl = siteUrl;
        this.dbUrl = dbUrl;
    }

    async get(keyword, amount) {
        return [];
    }

    async filter(keyword, amount, func) {
        return await this.get(keyword, amount);
    }

    async update(isForce) {
        const data = await this._getData(true);
        if (!isForce && data && data.ctime && Date.now() - data.ctime < 3600 * 24 * 1000)
            return this.latestTime = data.ctime;
        let r;
        try {
            const rep = await genAxiosGetFunc()(this.dbUrl);
            r = await this._store("data", rep.data);
        } catch {
            r = { extra: null, bgms: [], ctime: null };
        }
        this.latestTime = r.ctime;
        this._data = r;
        return r;
    }

    async test() {
        try {
            await genAxiosGetFunc()(this.dbUrl);
        } catch (err) {
            return 0;
        }
        return 1;
    }

    async _getData(isFromUpdate = false) {
        if (!this._data || typeof this._data !== "object")
            this._data = await this._take("data");
        if (!this._data) {
            if (isFromUpdate) return null;
            this._data = await this.update();
        }
        return this._data;
    }

    async _store(key, raw) {
        return this._db.setItem(`source.db.${this.name}.${key}`, raw);
    }

    async _take(key) {
        return await this._db.getItem(`source.db.${this.name}.${key}`);
    }

    setLang(langCode) {
        this._lang = langCode;
    }

    _genRlist(title, url, siteName = null) {
        return {
            title: title,
            url: url,
            type: this.type,
            source: this.name,
            siteName: siteName ? siteName : this.siteName
        };
    }
}
