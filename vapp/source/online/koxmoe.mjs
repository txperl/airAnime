import SourceOnline from "./root.mjs";
import HanConvert from "../../utils/zhHan.mjs"

export default class SourceKoxMoe extends SourceOnline {
    async format(raw) {
        const r = [];
        const regex =
            /<div style="padding:0px 5px 0px 5px;">.*?<a href='(.*?)' style="font-size:13px;">(.*?)<\/a>/gms;
        let m;
        while ((m = regex.exec(raw)) !== null) {
            if (m.index === regex.lastIndex) regex.lastIndex += 1;
            r.push(this._genRlist(HanConvert.t2s(m[2]), m[1]));
        }
        return r.slice(0, 10);
    }
}