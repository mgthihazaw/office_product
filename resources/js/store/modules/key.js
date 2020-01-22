import Vue from "vue";
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
        initialLoad(state) {
            if (!state.data.length > 0) {
                state.show = false;
            }
        },
        filterData(state, payload) {
            state.show = true;
            state.data = new Array(res.data.count);
            this.infiniteInitialRowCount = res.data.count;
        },
        addKey(state, payload) {
            state.data.splice(0, 0, payload.data);
            state.gridData.count = state.data.length;
            state.show = true;
        },
        delete(state, payload) {
            state.show = false;
            payload.selectedNodes.forEach((node, index) => {
                state.data.splice(node.index, 1);
            });
            state.gridData.count = state.data.length;
            state.show = true;
        },
        successNotify(state, payload) {
            Vue.notify({
                group: "foo",
                type: "success",
                title: payload.title,
                text: payload.message,
                duration: 1000,
                speed: 1000
            });
        },
        errorNotify(state, payload) {
            state.show = false;
            Vue.notify({
                group: "foo",
                type: "error",
                title: payload.title,

                text: payload.message,
                duration: 1000,
                speed: 1000
            });
            state.show = true;
        }
    },
    actions: {
        count(context, payload) {
            let key = payload ? payload.key : "";
            let value = payload ? payload.value : "";
            axios.get(`/api/keys/count?key=${key}&value=${value}`).then(res => {
                context.commit("count", res.data);
            });
        },
        addKey(context, payload) {
            context.commit("load");
            axios.post("/api/keys", payload).then(res => {
                context.commit("addKey", res.data);
                context.commit("successNotify", {
                    title: "ADD KEY",
                    message: "Successfully Create"
                });
            });
        },
        delete(context, payload) {
            context.commit("load");
            axios
                .delete("/api/keys/delete", {
                    params: { keys: payload.selectedDatas }
                })
                .then(res => {
                    context.commit("delete", {
                        selectedNodes: payload.selectedNodes
                    });
                    context.commit("successNotify", {
                        title: "DELETE KEY",
                        message: "Successfully Delete"
                    });
                })
                .catch(error => {
                    context.commit("errorNotify", {
                        title: "DELETE KEY",
                        message: "Something went wrong"
                    });
                });
        },
        save(context, payload) {
            axios.put("/api/keys/update", { editData: payload }).then(res => {
                // console.log(res);

                context.commit("successNotify", {
                    title: "SAVE KEY",
                    message: "Successfully Save"
                });
            });
        }
    }
};
export default key;
