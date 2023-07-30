import SourceOnline from "./root.mjs";

export default class SourceAgeFans extends SourceOnline {
    async format(raw) {
        const r = [];
        const regex = /<h5 class="card-title"><a href="(.*?)">(.*?)<\/a><\/h5>/gms;
        let m;
        while ((m = regex.exec(raw)) !== null) {
            if (m.index === regex.lastIndex) regex.lastIndex += 1;
            r.push(this._genRlist(m[2], m[1]));
        }
        return r;
    }
}