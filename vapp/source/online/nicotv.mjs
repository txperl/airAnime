import SourceOnline from "./root.mjs";

export default class SourceNicoTV extends SourceOnline {
    async format(raw) {
        const r = [];
        const regex = /<h2 class="text-nowrap.*?href="(.*?)" title="(.*?)">/gms;
        let m;
        while ((m = regex.exec(raw)) !== null) {
            if (m.index === regex.lastIndex) regex.lastIndex += 1;
            r.push(this._genRlist(m[2], `http://www.nicotv.org${m[1]}`));
        }
        return r;
    }
}