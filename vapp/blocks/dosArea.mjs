const template = `
<div class="dos-area">
    <div v-for="(type, rIndex) in keyOfTypes" :id="'source-' + type" class="dos-sources">
        <h1>
            <span>{{ type }}</span>
            <span v-if="type !== 'Official'">
                ({{ rOfAll[type] ? rOfAll[type].length : 0 }})
            </span>
        </h1>
        <span v-for="(titleSites, index) in rOfAll[type]">
            <div :mdui-menu="getPopupMenuCode(index, rIndex)" class="mdui-chip">
                <img v-if="rIndex === 0" :src="getImgUrlOfBgmd(titleSites[1])"
                    onerror="this.src='./vapp/assets/images/img-error.svg'" class="mdui-chip-icon"
                    style="object-fit: cover;" />
                <span class="mdui-chip-title">{{ titleSites[0] }}</span>
            </div>
            <ul :id="'pu' + rIndex +'m-' + index" class="mdui-menu">
                <li v-for="siteNameItem in titleSites[1]" class="mdui-menu-item">
                    <a :href="siteNameItem[1].url" target="_blank" class="mdui-ripple">
                        <img v-if="siteNameItem[1].source !== 'bgmd'"
                            :src="'./vapp/assets/images/icon_' + siteNameItem[1].source + '.ico'">
                        <span>{{ siteNameItem[0] }}</span>
                    </a>
                </li>
            </ul>
        </span>
    </div>
</div>
`;

export default {
    template: template,
    props: ["args"],
    data() {
        return {
            sResults: {},
            taskIngNum: {},
        }
    },
    mounted() {
        this.init();
    },
    watch: {
        args() { this.init(); }
    },
    computed: {
        keyOfTypes() {
            const r = [];
            this.$parent.q.sourceAll.filter.getAllKeys("type").forEach(type => r.push(type));
            return r;
        },
        rOfAll() {
            const r = {}, fr = {};
            for (const name in this.sResults) {
                const bgms = this.sResults[name];
                bgms.forEach(item => {
                    if (!r[item.type]) r[item.type] = {};
                    if (!r[item.type][item.title]) r[item.type][item.title] = {};
                    r[item.type][item.title][item.siteName] = item;
                });
            }
            for (const type in r) {
                fr[type] = [];
                Object.keys(r[type]).map(title => {
                    const sites = [];
                    Object.keys(r[type][title]).map(siteName => {
                        sites.push([siteName, r[type][title][siteName]]);
                    });
                    sites.sort((a, b) => a[0].localeCompare(b[0]));
                    fr[type].push([title, sites]);
                });
                fr[type].sort((a, b) => a[0].localeCompare(b[0]));
            }
            return fr;
        }
    },
    methods: {
        init() {
            this.sResults = {};
            this.doGetSourceData(this.args[1]);
        },
        getImgUrlOfBgmd(sites) {
            for (const siteNameItem of sites)
                if (siteNameItem[0] === "番组计划")
                    return `https://api.bgm.tv/v0/subjects/${siteNameItem[1].url.split("/").at(-1)}/image?type=common`;
            return null;
        },
        getPopupMenuCode(index, num) {
            return `{target: '#pu${num}m-${index}', fixed: true, align: 'right'}`;
        },
        doGetSourceData(kt) {
            if (!kt) return;
            const vm = this;
            this.$parent.q.sourceAll.filter.name().forEach(li => {
                const [name, source] = li;
                const type = source.type;
                if (!vm.taskIngNum[type]) {
                    vm.taskIngNum[type] = 0;
                    if (name !== "bgmd")
                        vm.doLoadingAni(type);
                }
                vm.taskIngNum[type] += 1;
                source.get(kt.toLowerCase()).then(items => {
                    vm.taskIngNum[type] -= 1;
                    vm.sResults[name] = items;
                }).catch(() => vm.taskIngNum[type] -= 1);
            });
        },
        doLoadingAni(type) {
            const el = $(`#source-${type} h1 span:first-child`);
            if (el.length !== 1) return;
            const raw = el.html();
            let ns = raw.split("");
            let index = 1, prev = 0, plus = 1, ms = 300, maxCount = 60;
            const loop = () => setTimeout(() => {
                ns[prev] = ns[prev].toLowerCase();
                ns[index] = ns[index].toUpperCase();
                el.html(ns.join(""));
                if (this.taskIngNum[type] <= 0 || maxCount <= 0) {
                    if (index === 0)
                        return this.taskIngNum[type] = 0;
                    plus = -1;
                    ms = 50;
                }
                prev = index;
                index = index + plus;
                if (index === 0) plus = 1;
                if (index === raw.length - 1) plus = -1;
                maxCount -= 1;
                loop();
            }, ms);
            loop();
        }
    }
}
