const template = `
<div v-if="sResults.length" class="pins-panel">
    <h1>Pins</h1>
    <div v-for="pin in sResults" @click="$parent.goToHash('/search/' + pin)" class="mdui-chip">
        <span class="mdui-chip-title">{{ pin }}</span>
    </div>
</div>
`;

export default {
    template: template,
    props: ["args"],
    data() {
        return {
            sResults: [],
        }
    },
    created() { this.init(); },
    watch: {
        args() { this.init(); },
    },
    methods: {
        init() {
            this.$parent.dget("pins", null).then(r => {
                if (!r) return;
                this.sResults = r;
            });
        },
    }
}
