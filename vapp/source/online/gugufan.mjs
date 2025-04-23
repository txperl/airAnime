import SourceOnline from "./root.mjs";

export default class SourceGuguFan extends SourceOnline {
    async format(raw) {
        const r = [];
        const regex = /<a.*?class="public-list-exp".*?href="([^"]+)".*?>.*?<\/a>.*?<div class="thumb-txt.*?".*?>([^<]+)<\/div>/gms;
        let m;
        while ((m = regex.exec(raw)) !== null) {
            if (m.index === regex.lastIndex) regex.lastIndex += 1;
            r.push(this._genRlist(m[2], `https://${this.siteUrl}${m[1]}`));
        }
        return r;
    }
}
