import Vue from "vue";
import Vuex from "vuex";
import auth from "./modules/auth";
import key from "./modules/key";
import user from "./modules/user";
Vue.use(Vuex);
const store = new Vuex.Store({
    modules: {
        auth: auth,
        key: key,
        user: user
    }
});

export default store;
