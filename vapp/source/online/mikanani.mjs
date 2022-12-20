import SourceOnline from "./root.mjs";

export default class SourceMikanAni extends SourceOnline {
    async format(raw) {
        const r = [];
        const regex = /<a href="\/Home\/Bangumi\/(.*?)".*?class="an-text" title="(.*?)"/gms;
        let m;
        while ((m = regex.exec(raw)) !== null) {
            if (m.index === regex.lastIndex) regex.lastIndex += 1;
            r.push(this._genRlist(this.decodeEntities(m[2]), `https://mikanani.me/Home/Bangumi/${m[1]}`));
        }
        return r;
    }

    decodeEntities(str) {
        const element = document.createElement("div");
        if (str && typeof str === "string") {
            str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, "");
            str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, "");
            element.innerHTML = str;
            str = element.textContent;
            element.textContent = "";
        }
        element.remove();
        return str;
    }
}