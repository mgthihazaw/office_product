const key = {
    namespaced: true,
    state: {
        show: false,
        data: [],
        gridData: {
            count: 0,
            rowModelType: "infinite",
            rowSelection: "multiple",
            defaultColDef: {
                editable: true,
                resizable: true
            }
        }
    },
    getters: {
        show(state) {
            return state.show;
        },
        data(state) {
            return state.data;
        },
        count(state) {
            return state.gridData;
        }
    },
    mutations: {
        count(state, payload) {
            state.gridData.count = payload.count;
            state.data = new Array(payload.count);
            state.show = true;
        },
        load(state) {
            state.show = false;
        },
        filterData(state, payload) {
            state.show = true;
            state.data = new Array(res.data.count);
            this.infiniteInitialRowCount = res.data.count;
        }
    },
    actions: {
        count(context) {
            axios.get("/api/keys/count").then(res => {
                context.commit("count", res.data);
            });
        }
    }
};
export default key;
