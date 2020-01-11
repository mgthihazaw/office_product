<template>
  <v-app>
    <notifications group="foo" position="bottom right" />
    <NavBar></NavBar>

    <v-content>
      <v-card flat app>
        <v-toolbar color="teal accent-4" extended flat prominent>
          <v-app-bar-nav-icon class="d-lg-none" @click.stop="setDrawer"></v-app-bar-nav-icon>
          <h1 class="pt-2 toolbar-title">Microstack</h1>

          <v-spacer></v-spacer>

          <v-btn icon>
            <v-icon color="red" @click="logout">mdi-logout</v-icon>
          </v-btn>
        </v-toolbar>

        <router-view></router-view>
      </v-card>
    </v-content>
    <v-card>
      <v-footer class="font-weight-medium mt-5" color="blue-grey lighten-4">
        <v-col class="text-center">
          {{ new Date().getFullYear() }} —
          <strong>Microstack Technology Co.ltd</strong>
        </v-col>
      </v-footer>
    </v-card>
  </v-app>
</template>
<script>
import NavBar from "./layouts/NavBar";

export default {
  components: {
    NavBar
  },
  data() {
    return {};
  },

  methods: {
    setDrawer() {
      // eslint-disable-next-line
      Bus.$emit("setDrawer");
    },
    logout() {
      localStorage.removeItem("auth");
      window.location.replace("/");
    }
  }
};
</script>
<style>
.toolbar-title {
  font-family: "Lobster", cursive;
  color: rgb(206, 223, 219);
  letter-spacing: 0.1em;
  font-size: 35px;
  padding-left: 20px;
}
.v-content__wrap {
  background-color: white;
}
.vue-notification {
  padding: 13px;
  margin: 10px 20px 20px;

  font-size: 15px;
}
</style>
