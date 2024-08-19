import LazyLoad from "https://unpkg.com/vanilla-lazyload@17.8.3/dist/lazyload.esm.min.js";

const template = `
<div class="season-panel mdui-row-gapless">
    <div class="nav text">
        <div class="chooser">
            <section v-show="cChooser === 'season'">
                <a v-for="(season, index) in seasonName" @click="changeDate(null, 1, index)"
                    :disabled="index === cDate[1]">{{ season }}</a>
            </section>
            <section v-show="cChooser === 'dayofweek'">
                <a v-for="(dow, index) in dayOfWeekName" @click="changeDate(null, 2, index)"
                    :disabled="index === cDate[2]">{{ dow.substring(1) }}</a>
            </section>
        </div>
        <div class="shower">
            <a @keydown.enter.prevent @keydown.enter="changeDate($event, 0)" @blur="changeDate($event, 0)"
                :contenteditable="!$parent.q.isSearchBarIng" style="cursor: text;">{{ cDate[0] }}</a>
            <a @click="toggleChooser('season')">{{ showSeasonName }}</a>
            <a @click="toggleChooser('dayofweek')">{{ dayOfWeekName[cDate[2]] }}</a>
        </div>
    </div>
    <div class="gallery mdui-row-xs-2 mdui-row-md-5">
        <section v-for="item in rOfHasImg" class="mdui-col">
            <a :href="'./#/search/' + item.showTitle">
                <img class="lazy" :data-src="item.imgUrl" />
                <span><a>{{ item.showTitle }}</a></span>
            </a>
        </section>
    </div>
    <div v-if="sResults.length === 0" class="gblock mdui-xs-12">
        <div class="body">
            <div class="title">(ㆆᴗㆆ)</div>
            <div class="content">暂无当前时间点的新番数据<br>再等一下，可能马上就会有了</div>
        </div>
        <div class="panel">
            <a @click="setLastSeason">查看上季度番剧</a>
            <a href="./#/about/">刷新 DB 缓存</a>
        </div>
    </div>
</div>
`;

export default {
    template: template,
    props: ["args"],
    data() {
        return {
            seasonName: ["冬", "春", "夏", "秋"],
            dayOfWeekName: ["周日", "周一", "周二", "周三", "周四", "周五", "周六", "完全"],
            cDate: [null, null, null],
            cChooser: null,
            myLazyLoad: null,
            sResults: [],
        }
    },
    created() {
        const now = new Date();
        this.cDate = [now.getFullYear(), parseInt(now.getMonth() / 3), now.getDay()];
        this.init();
        // lazyload
        this.myLazyLoad = new LazyLoad({
            callback_error: (img) => {
                img.setAttribute("src", "./vapp/assets/images/img-error.svg");
            }
        });
    },
    computed: {
        rOfHasImg() {
            const r = [];
            // lazyload
            $(".lazy").removeClass("entered").removeClass("loaded").removeAttr("data-ll-status");
            this.sResults.forEach(item => {
                const bangumi = item.sites.filter(s => s.site === "bangumi")[0];
                if (!bangumi) return item.imgUrl = "none";
                item.imgUrl = `https://api.bgm.tv/v0/subjects/${bangumi.id}/image?type=large`;
                r.push(item);
            });
            // lazyload
            setTimeout(() => this.myLazyLoad.update(), 150);
            return r;
        },
        showSeasonName() {
            const date = new Date();
            const year = date.getFullYear(), mon = date.getMonth(), day = date.getDate();
            if (year !== this.cDate[0] || parseInt(mon / 3) !== this.cDate[1] || mon % 3 == 1)
                return this.seasonName[this.cDate[1]];
            switch (mon % 3) {
                case 0: return `初${this.seasonName[this.cDate[1]]}`
                case 2:
                    if (day >= 15)
                        return `临${this.seasonName[(this.cDate[1] + 1) % 4]}`
                    return `${this.seasonName[this.cDate[1]]}末`
            }
        },
    },
    methods: {
        init() {
            const [year, season, day] = this.cDate;
            if (year === null || year < 1970 || season === null) return;
            this.$parent.q.soHandler.filter.name("bgmd")
                .filter(null, null, (item) => {
                    if (item.season.year !== year || item.season.season !== season) return false;
                    return day === 7 || item.season.dayOfWeek === day;
                }).then(r => this.sResults = r);
        },
        changeDate(event, index, val) {
            if (event) val = parseInt(event.target.innerText);
            this.cDate[index] = val;
            this.init();
        },
        toggleChooser(c) {
            if (this.cChooser === c)
                return this.cChooser = null;
            this.cChooser = c;
        },
        setLastSeason() {
            const [y, s, _] = this.cDate;
            if (s === 0) {
                this.cDate[1] = 3;
                this.changeDate(null, 0, y - 1);
            } else {
                this.changeDate(null, 1, s - 1);
            }
        },
    }
}
