import SourceBgmd from "./db/bgmd.mjs";
import SourceBimiBimi from "./db/bimibimi.mjs";
import SourceAgeFans from "./online/agefans.mjs";
import SourceCopyManga from "./online/copymanga.mjs";
import SourceMikanAni from "./online/mikanani.mjs";
import SourceMoxMoe from "./online/moxmoe.mjs";
import SourceNicoTV from "./online/nicotv.mjs";

export default class SourceAll {
    static AIRANIME_RP_URL = "./fetch";

    static SOURCES = [
        new SourceBgmd(
            "Official/bgmd/bgmd",
            "github.com/bangumi-data/bangumi-data",
            `https://unpkg.com/bangumi-data/dist/data.json`
        ),
        new SourceBimiBimi(
            "Anime/bimibimi/BimiBimi",
            "www.bimiacg4.net",
            `${SourceAll.AIRANIME_RP_URL}/file/bimibimi.json`
        ),
        new SourceAgeFans(
            "Anime/agefans/AGE动画",
            "www.agemys.net",
            `${SourceAll.AIRANIME_RP_URL}/agefans/{kt}`
        ),
        new SourceNicoTV(
            "Anime/nicotv/妮可动漫",
            "www.nicotv.me",
            `${SourceAll.AIRANIME_RP_URL}/nicotv/{kt}`
        ),
        new SourceMikanAni(
            "Anime/mikanani/蜜柑计划",
            "mikanani.me",
            `${SourceAll.AIRANIME_RP_URL}/mikanani/{kt}`
        ),
        new SourceMoxMoe(
            "Manga/moxmoe/MoxMoe",
            "mox.moe",
            `${SourceAll.AIRANIME_RP_URL}/moxmoe/{kt}`
        ),
        new SourceCopyManga(
            "Manga/copymanga/拷贝漫画",
            "copymanga.site",
            `${SourceAll.AIRANIME_RP_URL}/copymanga/{kt}`
        ),
    ]

    static filter = {
        _cache: {},
        _keys: {},
        _noNames: [],
        doReload() {
            this._cache = {};
        },
        setNoNames(val) {
            if (!val) return;
            this._noNames = val.filter(n => n !== "bgmd");
            this.doReload();
        },
        getAllKeys(key) {
            if (!this._keys[key]) {
                const r = [];
                SourceAll.SOURCES.forEach(source => {
                    if (source[key] && !r.includes(source[key]))
                        r.push(source[key]);
                });
                this._keys[key] = r;
            }
            return this._keys[key];
        },
        method(subkey) {
            return this._filter("method", subkey);
        },
        name(subkey) {
            return this._filter("name", subkey, true);
        },
        type(subkey) {
            return this._filter("type", subkey);
        },
        _filter(key, subkey, unique = false) {
            if (!this._cache[key]) {
                const r = {};
                SourceAll.SOURCES.forEach(source => {
                    if (this._noNames.includes(source.name))
                        return;
                    if (unique)
                        return r[source[key]] = source;
                    if (!r[source[key]]) r[source[key]] = [];
                    r[source[key]].push(source);
                });
                this._cache[key] = r;
            }
            if (!subkey) return Object.entries(this._cache[key]);
            if (!this._cache[key][subkey]) return [];
            return this._cache[key][subkey];
        }
    }
}
