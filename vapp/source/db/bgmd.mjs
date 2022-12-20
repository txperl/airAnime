import SourceDB from "./root.mjs";

export default class SourceBgmd extends SourceDB {
    async get(keyword, amount) {
        const r = [];
        const data = await this._getData();
        for (const item of data.bgms) {
            if (amount && r.length >= amount) break;
            if (!item.keyword.includes(keyword)) continue;
            item.sites.forEach(site => {
                const url = data.extra[site.site].urlTemplate.replace("{{id}}", site.id);
                r.push(this._genRlist(item.showTitle, url, data.extra[site.site].title));
            });
        }
        return r;
    }

    async filter(keyword, amount, func) {
        if (typeof func !== "function")
            return await this.get(keyword, amount);
        const r = [];
        const data = await this._getData();
        for (const item of data.bgms) {
            if (amount && r.length >= amount) break;
            if (!func(item)) continue;
            r.push(item);
        }
        return r;
    }

    async _store(key, raw) {
        const r = { extra: raw.siteMeta, bgms: [], ctime: Date.now() };
        raw.items.reverse();
        raw.items.forEach(item => {
            const titles = { ja: item.title };
            const time = new Date(item.begin);
            let keyword = item.title + ",";
            for (const transKey in item.titleTranslate) {
                titles[transKey] = item.titleTranslate[transKey][0];
                keyword += item.titleTranslate[transKey].join("/")
            }
            r.bgms.push({
                titles: titles,
                showTitle: titles[this._lang] ? titles[this._lang] : titles.ja,
                keyword: keyword.toLowerCase(),
                season: {
                    year: time.getFullYear(),
                    season: time.getMonth() / 3,
                    dayOfWeek: time.getDay(),
                },
                sites: item.sites
            });
        });
        return super._store(key, r);
    }
}