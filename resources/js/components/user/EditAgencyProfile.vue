<template>
  <Form role="agency" v-on:refresh-list="refreshList" />
</template>

<script>
import { mapActions } from "vuex";
import Form from "./Form";

export default {
  components: {
    Form,
  },

  methods: {
    ...mapActions({
      getItems: "agency/getItems",
    }),
    refreshList() {
      this.getItems({
        endpoint: "users",
        query: { role: "agency" },
      });
      this.$router.push({ name: "agency_list" }, () => {});
    },
  },
  created() {
    // if (this.$route.params.id == "new") this.item.role = "agency";
  },
};
</script>
