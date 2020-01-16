<template>
  <v-app>
    <v-row justify="center">
      <v-card height="500px" width="450px" style="margin-top:120px;border:none;">
        <v-card-title class="white mt-5">
          <v-img src="./2.png" width="100px" justify="center" />
          <v-spacer></v-spacer>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text class="mt-3">
          <v-form style="margin-top:50px;">
            <div style="margin-top:20px;" class="mx-5">
              <v-text-field
                prepend-icon="mdi-account"
                label="E-mail"
                v-model="form.email"
                type="email"
                required
              ></v-text-field>
            </div>

            <div style="margin-top:30px;" class="mx-5">
              <v-text-field
                prepend-icon="mdi-lock"
                label="Password"
                v-model="form.password"
                type="password"
                required
              ></v-text-field>
            </div>
            <div class="authError">
              &nbsp;
              <span v-if="authError">username or password is incorrect</span>
            </div>
            <div style="padding-top:40px;">
              <v-btn
                type="submit"
                color="success"
                class="mt-5"
                large
                block
                @click.prevent="login"
              >Login</v-btn>
            </div>
          </v-form>
        </v-card-text>
      </v-card>
    </v-row>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      authError: false,
      form: {
        email: null,
        password: null
      }
    };
  },
  created() {},
  methods: {
    login() {
      axios
        .post("/api/login", this.form)
        .then(res => {
          localStorage.setItem("auth", JSON.stringify(res.data));
          window.location.replace("/");
        })
        .catch(error => {
          if (error.response.status == 401) {
            this.authError = true;
          }
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

<style scoped>
.authError {
  color: red;

  text-align: center;
  font-weight: 100;
}
</style>
