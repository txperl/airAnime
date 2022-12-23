import SourceAll from "./source/all.mjs";
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
                sourceAll: SourceAll,
                isSearchBarIng: false,
            },
            router: {
                default: "/home",
                cBlock: null,
                args: []
            },
            isLoadingData: true,
            appRootUrl: `${window.location.protocol}//${window.location.host}`,
        }
    },
    created() {
        const vm = this;
        window.addEventListener("hashchange", (event) => {
            vm.goToSys(event.newURL.split(vm.appRootUrl + "/#")[1]);
        });
        this.doInit().then(() => {
            this.isLoadingData = false;
            this.goToHash(window.location.hash.substring(1), true);
        });
    },
    mouted() { },
    methods: {
        async doInit() {
            const lang = await this.dget("sys.conf.lang", "zh-Hans");
            const noNames = await this.dget("sys.conf.noNames", []);
            const funcs = [];
            this.q.sourceAll.filter.setNoNames(noNames);
            this.q.sourceAll.filter.method("db").forEach(source => {
                source.setLang(lang);
                funcs.push(source.update(false));
            });
            return await Promise.all(funcs);
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
        goToNewBlank(path) {
            window.open(`${this.appRootUrl}/#${path}`, "_blank");
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
