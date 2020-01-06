import Vue from "vue";
import Vuetify from "vuetify/lib";

window.axios = require("axios");
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
if (localStorage.getItem("auth")) {
    const auth = JSON.parse(localStorage.getItem("auth"));
    const jwtToken = auth.access_token;

    if (jwtToken) {
        window.axios.defaults.headers.common[
            "Authorization"
        ] = `Bearer ${jwtToken}`;
    }
}

Vue.use(Vuetify);

const opts = {};

export default new Vuetify(opts);
