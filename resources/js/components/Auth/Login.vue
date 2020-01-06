<template>
    <v-app>
        <v-container class="container ">
            <v-layout
                row
                class="justify-center"
                style="position: relative;top: 20%;"
                xs6
            >
                <v-flex xs7 class="grey lighten-4">
                    <v-container style="top: 5%;" class="text-center">
                        <v-card flat>
                            <v-card-title>
                                <h3 class="text-center">Microstack</h3>
                            </v-card-title>
                            <v-divider></v-divider>

                            <v-form>
                                <div class="mx-4">
                                    <v-text-field
                                        prepend-icon="mdi-account"
                                        label="E-mail"
                                        v-model="form.email"
                                        type="email"
                                        required
                                    ></v-text-field>
                                </div>

                                <div class="mx-4">
                                    <v-text-field
                                        prepend-icon="mdi-lock"
                                        label="Password"
                                        v-model="form.password"
                                        type="password"
                                        required
                                    ></v-text-field>
                                </div>

                                <div class="mx-5">
                                    <v-btn
                                        type="submit"
                                        color="primary"
                                        class="mt-5 "
                                        large
                                        block
                                        @click.prevent="login"
                                    >
                                        Login
                                    </v-btn>
                                </div>
                            </v-form>
                        </v-card>
                    </v-container>
                </v-flex>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
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
                window.location.replace("/");
            });
        }
    },
    computed: {
        disabled() {
            return !(this.form.email && this.form.password);
        }
    }
};
</script>

<style></style>
