<template>
    <div>
        <Login v-if="!getAuth"></Login>
        <AppHome v-else></AppHome>
    </div>
</template>

<script>
import Login from "./Auth/Login";
import AppHome from "./AppHome";
export default {
    components: {
        AppHome,
        Login
    },
    data() {
        return {
            auth: !!JSON.parse(localStorage.getItem("auth")),
            form: {
                email: null,
                password: null
            }
        };
    },
    created() {},
    methods: {
        login() {
            axios.post("/api/login", this.form).then(res => {
                localStorage.setItem("auth", JSON.stringify(res.data));
                this.auth = true;
                window.location.replace("/");
            });
        },
        logout() {
            localStorage.removeItem("auth");
            window.location.replace("/");
        }
    },
    computed: {
        disabled() {
            return !(this.form.email && this.form.password);
        },
        getAuth() {
            return this.auth;
        }
    }
};
</script>

<style></style>
