<template>
  <div class="navigation-drawer">
    <v-navigation-drawer
      v-model="drawer"
      color="lighten-5"
      app
      :mini-variant.sync="drawer && $vuetify.breakpoint.lgAndUp"
      :permanent="$vuetify.breakpoint.lgAndUp"
    >
      <v-list>
        <v-list-item class="pb-2">
          <v-list-item-action v-if="drawer == true && $vuetify.breakpoint.lgAndUp">
            <v-app-bar-nav-icon small @click.stop="setDrawer"></v-app-bar-nav-icon>
          </v-list-item-action>
          <v-img :src="logo" width="40" />
          <v-list-item-action v-if="drawer == false">
            <v-app-bar-nav-icon small @click.stop="setDrawer"></v-app-bar-nav-icon>
          </v-list-item-action>
        </v-list-item>
        <v-divider></v-divider>
        <div v-for="(item, i) in navs" :key="i">
          <router-link :to="item.link">
            <v-list-item>
              <v-list-item-action>
                <v-icon :color="item.iconColor">{{ item.icon }}</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title class="sidebarTitle">{{ item.name }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </router-link>
        </div>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>
<script>
export default {
  data() {
    return {
      drawer: true,
      logo: "./2.png",
      tree: [],
      open: ["public"],
      navs: [
        {
          name: "DASHBOARD",
          link: "/",
          icon: "mdi-view-dashboard",
          iconColor: "#00BFA5"
        }
      ]
    };
  },
  methods: {
    setDrawer() {
      this.drawer = !this.drawer;
    },
    navigateTo(link) {
      this.$router.go(link);
    },
    logout() {
      localStorage.removeItem("auth");
      window.location.replace("/");
    }
  },

  created() {
    // eslint-disable-next-line
    Bus.$on("setDrawer", () => {
      this.drawer = !this.drawer;
    });
  }
};
</script>
<style scoped>
.v-application a {
  text-decoration: none;
  color: rgb(48, 54, 56);
}
v-list {
  background-color: #000000;
}
.sidebarTitle {
  font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;
  font-size: 18;
  color: #00bfa5;
}
.activeLink {
  background-color: red;
  color: yellow;
}
</style>
