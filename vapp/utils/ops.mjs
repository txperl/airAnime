function genAxiosGetFunc(timeout = 10000) {
    const source = axios.CancelToken.source();
    const timeoutFunc = setTimeout(() => {
        source.cancel();
        throw new Error("axios GET timeout");
    }, timeout);
    return async (url, options) => {
        const ops = { ...options, cancelToken: source.token };
        const rep = await axios.get(url, ops)
        clearTimeout(timeoutFunc);
        return rep;
    };
}

function pureShowTitle(title) {
    const CHS = ["零", "一", "二", "三", "四", "五", "六", "七", "八", "九"];
    let ftitle = title;
    try {
        // 移除末尾 无修 字样
        ftitle = ftitle.replace(/ ?[【\[（(]无修[正版]?[)）\]】]$/gms, "");
        // 替换末尾 S1, Part1, Season1, 1 字样
        const regPart = / ?(S|Part|Season)?.?(\d)$/gms;
        const m = regPart.exec(ftitle);
        if (m !== null && CHS[m[m.length - 1]])
            ftitle = ftitle.replace(new RegExp(`${m[0]}$`, "gms"), ` 第${CHS[m[m.length - 1]]}季`);
    } catch {
        return title;
    }
    return ftitle;
}

function doSortShowTitle(dicts) {
    const CHS = { "零": 0, "一": 1, "二": 2, "三": 3, "四": 4, "五": 5, "六": 6, "七": 7, "八": 8, "九": 9 };
    dicts.sort((a, b) => {
        const t1 = a[0], t2 = b[0];
        for (let pos = 0; pos < Math.min(t1.length, t2.length); pos++) {
            const c1 = t1.charAt(pos), c2 = t2.charAt(pos);
            if (c1 === c2) continue;
            if (c2 === " ") return 0;
            if (c1 === " ") return 1;
            if (CHS[c1] && CHS[c2])
                return CHS[c1] - CHS[c2];
            return c1 <= c2 ? 0 : 1;
        }
        return t1.length <= t2.length ? 0 : 1;
    });
}

export {
    genAxiosGetFunc, pureShowTitle, doSortShowTitle
}
