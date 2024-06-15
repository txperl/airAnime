import HanConvert from "../../utils/zhHan.mjs";
import SourceOnline from "./root.mjs";

export default class SourceKoxMoe extends SourceOnline {
    async format(raw) {
        const r = [];
        const regex =
            /<div style="padding:0px 5px 0px 5px;">.*?<a href='(.*?)' style="font-size:13px;">(.*?)<\/a>/gms;
        let m;
        while ((m = regex.exec(raw)) !== null) {
            if (m.index === regex.lastIndex) regex.lastIndex += 1;
            const title = m[2].replaceAll(new RegExp("\</?b\>", "gm"), "");
            r.push(this._genRlist(HanConvert.t2s(title), m[1]));
        }
        return r.slice(0, 10);
    }
}