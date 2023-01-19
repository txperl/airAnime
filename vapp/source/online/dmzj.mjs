import SourceOnline from "./root.mjs";

export default class SourceDmzj extends SourceOnline {
    async format(raw) {
        raw = JSON.parse(raw.substring(20, raw.length - 1));
        const r = [];
        for (const item of raw) {
            r.push(this._genRlist(item.comic_name, `https:${item.comic_url}`));
        }
        return r.slice(0, 10);
    }
}