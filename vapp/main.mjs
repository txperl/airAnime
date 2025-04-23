import SourceHandler from "./source/all.mjs";
import SearchBar from "./blocks/searchBar.mjs";
import DosArea from "./blocks/dosArea.mjs";
import SeasonPanel from "./blocks/seasonPanel.mjs";
import PinsPanel from "./blocks/pinsPanel.mjs"
import AboutArea from "./blocks/aboutArea.mjs"

export default {
    components: {
        SearchBar, DosArea, SeasonPanel, PinsPanel, AboutArea,
    },
    data() {
        return {
            q: {
                soHandler: SourceHandler,
                isSearchBarIng: false,
            },
            router: {
                default: "/home",
                cBlock: null,
                args: []
            },
            appRootUrl: "",
            isLoadingData: true,
        }
    },
    created() {
        let rurl = window.location.href.split("/#")[0];
        while (rurl.charAt(rurl.length - 1) === "/")
            rurl = rurl.substring(0, rurl.length - 1);
        this.appRootUrl = rurl;
        const vm = this;
        window.addEventListener("hashchange", (event) => {
            vm.goToSys(event.newURL.split(vm.appRootUrl + "/#")[1]);
        });
        this.doInit().then(() => {
            this.isLoadingData = false;
            this.goToHash(window.location.hash.substring(1), true);
        });
    },
    mounted() {
        const el = $("#app-cover section span");
        let next = true;
        const loopAni = () => setTimeout(() => {
            if (this.isLoadingData === false) return;
            if (next) el.addClass("flip-x");
            else el.removeClass("flip-x");
            next = !next;
            loopAni();
        }, 1500);
        loopAni();
    },
    methods: {
        async doInit() {
            await this.doInitConfig();
            const lang = await this.dget("sys.conf.lang");
            const noNames = await this.dget("sys.conf.noNames");
            const funcs = [];
            this.q.soHandler.filter.setNoNames(noNames);
            this.q.soHandler.filter.method("db").forEach(source => {
                source.setLang(lang);
                funcs.push(source.update(false));
            });
            return await Promise.all(funcs);
        },
        async doInitConfig() {
            const _ = {
                "sys.conf.lang": "zh-Hans",
                "sys.conf.noNames": ["dmzj"],
            };
            for (const key in _) {
                if (await this.dget(key, "__null__") !== "__null__")
                    continue;
                this.dset(key, _[key]);
            }
        },
        goToHash(url, isSys = false) {
            if (!url.includes("/"))
                url = this.router.default;
            window.location.hash = "#" + url;
            if (isSys) this.goToSys(url);
        },
        goToSys(path) {
            // /page/id
            const paths = path.split("?")[0].split("/").slice(1);
            const blockName = paths[0];
            const args = [];
            paths.forEach(arg => {
                if (arg) args.push(decodeURI(arg));
            });
            this.router.args = args;
            if (!blockName)
                return this.goToHash("");
            if (this.router.cBlock === blockName) return;
            this.router.cBlock = blockName;
            document.body.scrollTop = document.documentElement.scrollTop = 0;
        },
        async dget(key, default_ = null) {
            const data = await localforage.getItem(`appdata.${key}`);
            if (!data) return default_;
            return !data ? default_ : data;
        },
        async dset(key, data) {
            return await localforage.setItem(`appdata.${key}`, data);
        },
    }
}
