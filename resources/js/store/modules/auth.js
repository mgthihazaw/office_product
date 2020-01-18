import {
    getLocalUser,
    setLocalUser,
    removeLocalUser
} from "../../helpers/auth_helper";
const user = getLocalUser();
const auth = {
    namespaced: true,
    state: {
        user: user,
        isLoggedIn: !!user,
        authLoading: false,
        authError: false
    },
    getters: {
        authLoading(state) {
            return state.authLoading;
        },
        user(state) {
            return state.user;
        },
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        authError(state) {
            return state.authError;
        }
    },
    mutations: {
        loginSuccess(state, payload) {
            setLocalUser(payload);
            state.user = getLocalUser();
            state.isLoggedIn = !!state.user;
            window.location.replace("/");
        },
        loginFail(state) {
            state.authError = true;
        },
        logout(state) {
            removeLocalUser();
            state.user = null;
            state.isLoggedIn = !!state.user;
            window.location.replace("/");
        }
    },
    actions: {
        login(context, payload) {
            console.log(payload);
            axios
                .post("/api/login", payload)
                .then(res => {
                    context.commit("loginSuccess", res.data);
                    console.log(res);
                })
                .catch(error => {
                    console.log(error);
                    context.commit("loginFail");
                });
        },
        logout(context) {
            axios
                .post("/api/logout")
                .then(res => {
                    context.commit("logout");
                    // localStorage.removeItem("auth");
                    // window.location.replace("/");
                })
                .catch(err => {
                    context.commit("logout");
                    // localStorage.removeItem("auth");
                    // window.location.replace("/");
                });
        }
    }
};
export default auth;
