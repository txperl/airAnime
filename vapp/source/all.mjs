import SourceAgeFans from "./online/agefans.mjs";
import SourceAlidRuach from "./db/alid_ruach.mjs";
import SourceBgmd from "./db/bgmd.mjs";
import SourceDmzj from "./online/dmzj.mjs";
import SourceKoxMoe from "./online/koxmoe.mjs";
import SourceMikanAni from "./online/mikanani.mjs";
import SourceGuguFan from "./online/gugufan.mjs";
import SourceAnime1 from "./db/anime1.mjs";
const AIRANIME_RP_URL = "https://airanime-fetch.txperl.workers.dev/fetch";

const ALL_SOURCES = [
    new SourceBgmd(
        "Official/bgmd/bgmd",
        "github.com/bangumi-data/bangumi-data",
        `https://unpkg.com/bangumi-data@latest/dist/data.json`
    ),
    new SourceAnime1(
        "Anime/anime1/Anime1",
        "anime1.me",
        `${AIRANIME_RP_URL}/file/anime1.json`
    ),
    new SourceAlidRuach(
        "Anime/alid_ruach/阿里云盘@Ruach",
        "docs.qq.com/doc/DRHhIUkFqeWhGYmpT",
        `${AIRANIME_RP_URL}/file/alid_ruach.json`
    ),
    // new SourceAgeFans(
    //     "Anime/agefans/AGE动画",
    //     "www.agefans.la",
    //     `${AIRANIME_RP_URL}/agefans/{kt}`
    // ),
    new SourceGuguFan(
        "Anime/gugufan/咕咕番",
        "www.gugu3.com",
        `${AIRANIME_RP_URL}/gugufan/{kt}`
    ),
    new SourceMikanAni(
        "Anime/mikanani/蜜柑计划",
        "mikanani.me",
        `${AIRANIME_RP_URL}/mikanani/{kt}`
    ),
    new SourceKoxMoe(
        "Manga/koxmoe/KoxMoe",
        "kox.moe",
        `${AIRANIME_RP_URL}/koxmoe/{kt}`
    ),
    new SourceDmzj(
        "Manga/dmzj/动漫之家",
        "dmzj.com",
        `${AIRANIME_RP_URL}/dmzj/{kt}`
    ),
];

export default {
    filter: {
        _cache: {},
        _noNames: [],
        doReload() {
            this._cache = {};
        },
        setNoNames(val) {
            if (!val) return;
            this._noNames = val.filter(n => n !== "bgmd");
            this.doReload();
        },
        allSubKey(key) {
            if (!this._cache.allSubKey) this._cache.allSubKey = {};
            if (!this._cache.allSubKey[key]) {
                const r = [];
                ALL_SOURCES.forEach(source => {
                    if (source[key] && !r.includes(source[key]))
                        r.push(source[key]);
                });
                this._cache.allSubKey[key] = r;
            }
            return this._cache.allSubKey[key];
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
                ALL_SOURCES.forEach(source => {
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
