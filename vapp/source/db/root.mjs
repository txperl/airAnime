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
        if (!this._data || typeof this._data !== "object")
            this._data = await this._take("data");
        if (isForce || !(this._data && this._data.ctime
            && Date.now() - this._data.ctime < 3600 * 3 * 1000)) {
            let r;
            try {
                const rep = await genAxiosGetFunc()(this.dbUrl);
                r = await this._store("data", rep.data);
            } catch {
                r = { extra: null, bgms: [], ctime: null };
            }
            this._data = r;
        }
        this.latestTime = this._data.ctime;
        return this._data;
    }

    async test() {
        try {
            await genAxiosGetFunc()(this.dbUrl);
        } catch (err) {
            return 0;
        }
        return 1;
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
