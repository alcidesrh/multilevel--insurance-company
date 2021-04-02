<template>
  <Form role="elite" v-on:refresh-list="refreshList" />
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
      getItems: "elite/getItems",
    }),
    refreshList() {
      this.getItems({
        endpoint: "users",
        query: { role: "elite" },
      });
      this.$router.push({ name: "elite_list" }, () => {});
    },
  },
};
</script>
