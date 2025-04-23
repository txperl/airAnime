import HanConvert from "../../utils/zhHan.mjs";
import SourceOnline from "./root.mjs";

export default class SourceKoxMoe extends SourceOnline {
    async format(raw) {
        const r = [];
        for (const item of raw) {
            r.push(this._genRlist(HanConvert.t2s(item.title), item.link));
        }
        return r;
    }
}