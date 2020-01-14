import Vue from "vue";
import VueRouter from "vue-router";
import DashBoard from "./components/DashBoard";
import Key from "./components/Key";
import Notound from "./components/NotFound";
Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        name: "dashboard",
        component: DashBoard,
        meta: {
            requiresAuth: true
        }
    },

    {
        path: "*",
        component: Notound
    }
];

const router = new VueRouter({
    routes,
    hashbang: false,
    cache: false,
    mode: "history"
    // short for `routes: routes`
});

export default router;
