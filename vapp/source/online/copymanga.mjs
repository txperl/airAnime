import SourceOnline from "./root.mjs";
import HanConvert from "../../utils/zhHan.mjs"

export default class SourceCopyManga extends SourceOnline {
    async format(raw) {
        const r = [];
        for (const item of raw.results.list) {
            r.push(this._genRlist(
                HanConvert.t2s(item.name),
                `https://www.copymanga.tv/comic/${item.path_word}`)
            );
        }
        return r.slice(0, 10);
    }
}