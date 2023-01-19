import SourceOnline from "./root.mjs";

export default class SourceCopyManga extends SourceOnline {
    async format(raw) {
        const r = [];
        for (const item of raw.results.list) {
            r.push(this._genRlist(item.name, `https://copymanga.site/comic/${item.path_word}`));
        }
        return r.slice(0, 10);
    }
}