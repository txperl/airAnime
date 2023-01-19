import SourceOnline from "./root.mjs";
import HanConvert from "../../utils/zhHan.mjs"

export default class SourceMoxMoe extends SourceOnline {
    async format(raw) {
        const r = [];
        const regex = /<\/div><\/a>.*?<a href='(.*?)'>(.*?)<\/a>/gms;
        let m;
        while ((m = regex.exec(raw)) !== null) {
            if (m.index === regex.lastIndex) regex.lastIndex += 1;
            r.push(this._genRlist(HanConvert.t2s(m[2]), m[1]));
        }
        return r.slice(0, 10);
    }
}