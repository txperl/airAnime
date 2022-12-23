const template = `
<div class="about-area">
    <section class="dos-sources">
        <h1>About</h1>
        <p>airAnime 是一款聚合「番剧搜索」工具，也许你会喜欢。</p>
        <a href="https://yumoe.com" target="_blank" class="mdui-chip">
            <span class="mdui-chip-title">Version@v3.01 / Created by Trii Hsia with ❤️</span>
        </a>
        <a href="mailto:txperl@gmail.com" target="_blank" class="mdui-chip">
            <span class="mdui-chip-title">#Email</span>
        </a>
        <a href="https://github.com/txperl/airAnime" target="_blank" class="mdui-chip">
            <span class="mdui-chip-title">#GitHub</span>
        </a>
    </section>
    <section class="dos-sources">
        <h1>Setting</h1>
        <div class="mdui-table-fluid">
            <table class="mdui-table text" style="white-space: nowrap;">
                <thead>
                    <tr><th v-for="key in tableColKeys">{{ key }}</th></tr>
                </thead>
                <tbody>
                    <tr v-for="(item, name) in rowsOfTable">
                        <td>{{ item[0] }}</td>
                        <td v-html="item[1]"></td>
                        <td>{{ item[2] }}</td>
                        <td>{{ item[3] }}</td>
                        <td class="mdui-table-col-numeric">
                            <label @click="toggleNoNames(name)" class="mdui-checkbox">
                                <input type="checkbox" :checked="onSources[name]" :disabled="name === 'bgmd'" />
                                <i class="mdui-checkbox-icon"></i>
                            </label>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="float: right;">
            <br>
            <button @click="doSourceTest" class="mdui-btn mdui-ripple">源测试</button>
            <button @click="doDbUpdateAll" class="mdui-btn mdui-ripple">
                {{ isUpdatingDB ? '更新中...' : '更新 DB 缓存' }}
            </button>
        </div>
    </section>

</div>
`;

export default {
    template: template,
    props: ["args"],
    data() {
        return {
            tableColKeys: ["#", "源", "类型", "", ""],
            isUpdatingDB: false,
            allNames: [],
            onSources: {},
            testResults: {},
        }
    },
    created() { this.init(); },
    computed: {
        rowsOfTable() {
            const r = {};
            this.allNames.forEach((name, index) => {
                const fr = [index, name, "", "", ""];
                const source = this.onSources[name];
                if (source) {
                    fr[1] = `<a href="http://${source.siteUrl}" target="_blank">${source.siteName}</a>`;
                    let strType = source.type;
                    strType = `${strType} / ${source.method.toUpperCase()}`;
                    if (source.method === "db") {
                        if (source.latestTime)
                            strType += " / " + new Date(source.latestTime)
                        else
                            strType += " / !!! 源错误，可能是网络问题导致"
                    }
                    fr[2] = strType;
                    if (this.testResults[name] !== undefined)
                        fr[3] = this.testResults[name] ? "Success" : "Fail";
                }
                r[name] = fr;
            });
            return r;
        }
    },
    methods: {
        init() {
            this.onSources = {};
            this.allNames = this.$parent.q.sourceAll.filter.getAllKeys("name");
            this.$parent.q.sourceAll.filter.name().forEach(source => {
                this.onSources[source[0]] = source[1];
            });
        },
        async toggleNoNames(name) {
            if (!name || name === "bgmd") return;
            let noNames = await this.$parent.dget("sys.conf.noNames", []);
            if (noNames.includes(name))
                noNames = noNames.filter(n => n && n !== name);
            else
                noNames.push(name);
            this.$parent.dset("sys.conf.noNames", [...noNames]).then(() => {
                this.$parent.q.sourceAll.filter.setNoNames(noNames);
                this.init();
            });
        },
        async doDbUpdateAll() {
            if (this.isUpdatingDB) return;
            this.isUpdatingDB = true;
            const funcs = [];
            this.$parent.q.sourceAll.filter.method("db").forEach(source => {
                funcs.push(source.update(true));
            });
            Promise.all(funcs).then(() => {
                this.isUpdatingDB = false;
                this.allNames = [...this.allNames]
            });
        },
        async doSourceTest() {
            if (this.isUpdatingDB) return;
            this.testResults = {};
            this.$parent.q.sourceAll.filter.name().forEach(source => {
                source[1].test().then(r => this.testResults[source[0]] = r);
            });
        }
    }
}
