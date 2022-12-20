const template = `
<div class="search-bar">
    <div @click="doFocusInput($event)" :ing="cIndex === -1" class="input-box">
        <input id="input-search-keyword" v-model="kt" @keydown="doSearchKeydown" @keyup="doSearchKeyup"
            @focus="ing = true" @blur="doLazy(100, () => ing = false)" @mouseover="cIndex = -1"
            placeholder="(ฅ´ω\`ฅ) 要搜点什么呢？">
        <button v-show="kt && args[0] === 'search'" @click="doToggleFavorite"
            class="mdui-btn mdui-btn-icon mdui-ripple">
            <i class="mdui-icon material-icons">{{ isFavorited ? 'favorite' : 'favorite_border' }}</i>
        </button>
        <button v-show="args[0] !== 'home'" @click="$parent.goToHash('/home')"
            class="mdui-btn mdui-btn-icon mdui-ripple" mdui-tooltip="{content: '主页'}">
            <i class="mdui-icon material-icons">chevron_left</i>
        </button>
        <button  v-show="args[0] === 'home'" @click="$parent.goToHash('/about')"
            class="mdui-btn mdui-btn-icon mdui-ripple">
            <i class="mdui-icon material-icons">more_vert</i>
        </button>
    </div>
    <div class="maybe-card mdui-list" v-show="ing && maybeKeys.length">
        <li v-for="(key, index) in maybeKeys" :ing="index === cIndex" @mouseover="cIndex = index"
            @click="doSearchKeyup(null, 'Enter')" class="mdui-list-item mdui-ripple">
            {{ key }}
        </li>
    </div>
</div>
`

export default {
    template: template,
    props: ["args"],
    data() {
        return {
            kt: "",
            ing: false,
            timer: null,
            maybeKeys: [],
            cIndex: -1,
            isFavorited: false,
        }
    },
    created() { this.init(); },
    mounted() {
        const dynamicStyle = () => {
            $(".search-bar .maybe-card").width($(".search-bar").width());
        };
        setTimeout(dynamicStyle, 100);
        $(window).on("resize", () => dynamicStyle());
    },
    watch: {
        args() { this.init(); },
        ing(val) {
            if (val) return this.doSearchKeyup(null, "on");
            this.cIndex = -1;
            this.maybeKeys = [];
        }
    },
    methods: {
        init() {
            this.maybeKeys = [];
            if (this.args[0] !== "search" || !this.args[1])
                return this.kt = "";
            this.kt = this.args[1];
            this.doGetIsFavorited();
        },
        doSearchKeydown(event) {
            // 搜索框关键词联想上下切换操作
            let cKeyIndex = this.cIndex;
            if (event.code === "ArrowDown") {
                event.preventDefault();
                cKeyIndex += 1;
                this.cIndex = cKeyIndex < this.maybeKeys.length ? cKeyIndex : -1;
            } else if (event.code === "ArrowUp") {
                event.preventDefault();
                cKeyIndex -= 1;
                this.cIndex = cKeyIndex < -1 ? this.maybeKeys.length - 1 : cKeyIndex;
            } else {
                return true;
            }
            return false;
        },
        doSearchKeyup(event, manual) {
            const action = manual ? manual : event.code;
            if (action === "ArrowDown" && action === "ArrowUp") return;
            if (this.timer) clearTimeout(this.timer);
            if (action === "Enter") {
                // 回车搜索
                if (this.cIndex > -1) {
                    $("#input-search-keyword").blur();
                    this.kt = this.maybeKeys[this.cIndex];
                }
                this.$parent.goToHash("/search/" + this.kt);
            } else if (!this.kt) {
                this.maybeKeys = [];
            } else {
                // 关键词联想
                this.timer = this.doLazy(350, () => {
                    this.$parent.q.sourceAll.filter.name("bgmd").get(this.kt.toLowerCase(), 30)
                        .then(items => {
                            const keys = {};
                            items.forEach(item => {
                                if (!keys[item.title]) keys[item.title] = 1;
                            });
                            this.maybeKeys = Object.keys(keys);
                        });
                });
            }
        },
        doGetIsFavorited() {
            if (!this.kt) return;
            this.$parent.dget("pins", null).then(r => {
                this.isFavorited = r && r.includes(this.kt);
            });
        },
        doToggleFavorite() {
            if (!this.kt) return;
            this.$parent.dget("pins", []).then(r => {
                if (this.isFavorited)
                    r = r.filter(a => a != this.kt);
                else
                    r.push(this.kt);
                this.$parent.dset("pins", r).then(
                    fr => this.isFavorited = fr.includes(this.kt)
                );
            });
        },
        doFocusInput(event) {
            if (event.target.className.includes("mdui")) return;
            $("#input-search-keyword").focus();
        },
        doLazy(ms, func) {
            return setTimeout(() => func(), ms);
        },
    }
}
