import SourceDB from "./root.mjs";

export default class SourceBimiBimi extends SourceDB {
    async get(keyword, amount) {
        const data = await this.update(false);
        const r = [];
        for (const item of data.bgms) {
            if (amount && r.length >= amount) break;
            if (!item.title.toLowerCase().includes(keyword)) continue;
            r.push(this._genRlist(item.title, item.url))
        }
        return r;
    }

    async _store(key, raw) {
        const r = { extra: null, bgms: [], ctime: Date.now() };
        raw.forEach(item => {
            r.bgms.push({
                title: item.title,
                url: item.link,
            });
        });
        return super._store(key, r);
    }
}