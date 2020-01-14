import Vue from "vue";
import VueRouter from "vue-router";
import DashBoard from "./components/DashBoard";
import Key from "./components/Key";
import Form from "./components/dialogs/Form.vue";
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
        component: DashBoard
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
