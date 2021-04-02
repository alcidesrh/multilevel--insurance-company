<template>
  <v-app>
    <v-layout>
      <headerBar v-if="user" />

      <v-main v-if="!loading" style="overflow: hidden;">
        <div class="mx-auto h-100" :class="user ? 'pa-5 pa-md-10' : ''">
          <router-view :key="$route.path"></router-view>
        </div>
      </v-main>

      <snackBar :text="snackBarText" :color="snackBarColor" :key="showSnackBar" />
    </v-layout>
  </v-app>
</template>

<script>
import { mapFields } from "vuex-map-fields";
import headerBar from "./components/layout/Header";
import EventBus from "./event-bus";
import snackBar from "./components/util/Snackbar";

export default {
  components: {
    headerBar,
    snackBar,
  },
  props: {
    source: String,
  },

  data: () => ({
    drawer: null,
    drawerRight: null,
    right: false,
    left: false,
    loading: true,

    snackBarText: null,
    snackBarColor: null,
    showSnackBar: false,
  }),
  computed: {
    ...mapFields("user", ["user", "error", "refreshRoute"]),
  },
  watch: {
    user(val) {
      if (val) {
        this.loading = false;

        this.$nextTick(function () {
          this.$router.push(this.refreshRoute, ()=> {});
        });
      }
    },
  },
  async created() {
    EventBus.$on("alert", (payload) => {
      if (payload.response) {
        if (payload.response.status == 500) this.snackBarColor = payload.color;
        this.snackBarText = payload.response.data;
      } else {

        if (payload.color) this.snackBarColor = payload.color;
        this.snackBarText = payload.text;
      }

      this.showSnackBar = !this.showSnackBar;
    });

    this.$vuetify.lang.current = "es";
    if (this.newPassword) return;
    this.loading = true;
    await this.$store.dispatch("user/getUser").then((response) => {
      if (this.error) {
        this.$store.dispatch("user/reset");
      }
      this.loading = false;
      if (!this.user) this.$router.push({ name: "login" });
    });
  },
};
</script>