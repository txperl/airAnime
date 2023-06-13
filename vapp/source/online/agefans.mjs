import SourceOnline from "./root.mjs";

export default class SourceAgeFans extends SourceOnline {
    async format(raw) {
        const r = [];
        for (const item of raw.AniPreL) {
            r.push(this._genRlist(item["R动画名称"], `https://www.agemys.vip/detail/${item["AID"]}`));
        }
        return r;
    }
}