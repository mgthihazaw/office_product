import Vue from "vue";
import vuetify from "../plugins/vuetify"; // path to vuetify export
import "@mdi/font/css/materialdesignicons.css";
import router from "./router";

import "@ag-grid-community/core/dist/styles/ag-grid.css";
import "@ag-grid-community/core/dist/styles/ag-theme-balham.css";
import "@ag-grid-community/core/dist/styles/ag-theme-material.css";

import "@ag-grid-community/infinite-row-model";

router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const currentUser = JSON.parse(localStorage.getItem("auth"));

    if (requiresAuth && !currentUser) {
        next("/login");
    } else if (to.path == "/login" && currentUser) {
        next("/");
    } else {
        next();
    }
});

Vue.component("main-app", require("./components/MainApp.vue").default);
window.Bus = new Vue();
new Vue({
    vuetify,
    router
}).$mount("#app");
