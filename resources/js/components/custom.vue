<template>
    <div>
        <div class="container" id="app">
            <div class="list-group-wrapper" id="infinite-list">
                <ul
                    class="list-group"
                    :style="{
                        paddingTop: `${pTop}px`,
                        paddingBottom: `${pBottom}px`
                    }"
                >
                    <li
                        class="list-group-item"
                        v-for="(item, index) in showItem"
                        :key="index"
                        style="height:47px;"
                    >
                        <div v-if="loading == false">{{ item }}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: false,
            nextItem: 1,
            items: [],
            page: 0,
            maxHeight: 4800000,
            time: "",
            pTop: 0,
            pBottom: 4795200,
            pTotal: 4795200,
            showItem: new Array(100)
        };
    },
    mounted() {
        // Detect when scrolled to bottom.

        this.items = new Array(100000);

        const listElm = document.querySelector("#infinite-list");
        console.log("Height", listElm.scrollHeight);

        listElm.addEventListener("scroll", e => {
            const block = listElm.scrollHeight / (100000 / 100);
            console.log("BLOCK", block);

            let offset = listElm.scrollTop + listElm.clientHeight;
            console.log("Offset", offset);
            this.page = Math.ceil(offset / block) - 1;
            let page = Math.ceil(offset / block) - 1;
            console.log("Page", page);

            if (
                offset > page * block &&
                this.maxHeight >= offset &&
                this.items[parseInt(page) * 100] == undefined
            ) {
                // if (offset >= (page + 1) * block && this.maxHeight >= offset + 1) {
                console.log(page, this.items);
                clearTimeout(this.time);
                this.loading = true;
                this.getData(page);
            }
        });

        // Initially load some items.
        this.getData(0);
    },
    methods: {
        getData(page) {
            console.log("GETDATA", page);

            setTimeout(
                function() {
                    this.loading = true;
                    this.pTop = parseInt(page) * 100 * 48;
                    this.pBottom = this.pTotal - this.pTop;
                    if (this.items[parseInt(page) * 100] == undefined) {
                        for (var i = 0; i < 100; i++) {
                            //   console.log("GETDATA", "Item" + (parseInt(page) * 100 + i));
                            this.items[parseInt(page) * 100 + i] =
                                "Item" + (parseInt(page) * 100 + i);
                            this.showItem[i] =
                                "Item" + (parseInt(page) * 100 + i);
                        }
                        this.loading = false;
                        console.log("AFTER" + page, this.items);
                        let start = parseInt(page) * 100;
                        // this.showItem = this.items.splice(start, start + 100);
                        console.log("DATA", this.showItem);
                    } else {
                        this.loading = false;
                        for (var i = 0; i < 100; i++) {
                            //   console.log("GETDATA", "Item" + (parseInt(page) * 100 + i));

                            this.showItem[i] = this.items[
                                parseInt(page) * 100 + i
                            ];
                        }
                        this.loading = false;
                        console.log("AFTER" + page, this.items);
                        let start = parseInt(page) * 100;
                        // this.showItem = this.items.splice(start, start + 100);
                        console.log("DATA", this.showItem);
                    }
                }.bind(this),
                50
            );

            /**************************************/
        }
    },
    computed: {
        getDatas() {
            return this.items;
        }
    }
};
</script>
<style scoped>
.container {
    padding: 40px 80px 15px 80px;
    background-color: #fff;
    border-radius: 8px;
    max-width: 800px;
}

.list-group-wrapper {
    position: relative;
    overflow: auto;
    height: 50vh;
    border: 2px solid #dce4ec;
    border-radius: 5px;
}
.list-group {
    display: block;
}
.list-group-item {
    margin-top: 1px;
    border-left: none;
    border-right: none;
    border-top: none;
    border-bottom: 2px solid #dce4ec;
}
</style>

// this.showItem[parseInt(page) * 100 + i] = // "Item" + (parseInt(page) * 100 +
i);
