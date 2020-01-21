const user = {
    namespaced: true,
    state: {
        users: []
    },
    getters: {
        users(state) {
            return state.users;
        }
    },
    mutations: {
        getUsers(state, payload) {
            state.users = payload.users;
        }
    },
    actions: {
        getUsers(context) {
            axios.get("/api/users").then(res => {
                context.commit("getUsers", res.data);
            });
        }
    }
};
export default user;
