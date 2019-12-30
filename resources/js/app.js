import Vue from "vue";
import vuetify from "../plugins/vuetify"; // path to vuetify export

Vue.component("main-app", require("./components/MainApp.vue").default);

new Vue({
    vuetify
}).$mount("#app");
