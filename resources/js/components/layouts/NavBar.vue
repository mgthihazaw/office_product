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
        <div>
          <v-treeview :items="navs" open-on-click class="pt-2">
            <template v-slot:prepend="{ item }">
              <div>
                <router-link :to="item.link">
                  <div>
                    <v-icon :color="item.iconColor" class="mr-5">{{ item.icon }}</v-icon>
                    <span
                      class=".headline"
                      v-if="
                                                !drawer ||
                                                    !$vuetify.breakpoint.lgAndUp
                                            "
                    >{{ item.linkName }}</span>
                  </div>
                </router-link>
              </div>
            </template>
          </v-treeview>
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
          name: "Dashboard",
          link: "/",
          icon: "mdi-view-dashboard",
          iconColor: "black"
        },
        {
          name: "Key",
          link: "/keys",
          icon: "mdi-key",
          iconColor: "black"
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
  background-color: black;
}
</style>
