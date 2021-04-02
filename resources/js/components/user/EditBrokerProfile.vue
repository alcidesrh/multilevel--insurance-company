<template>
  <Form role="broker" v-on:refresh-list="refreshList" />
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
      getItems: "broker/getItems",
    }),
    refreshList() {
      this.getItems({
        endpoint: "users",
        query: { role: "broker" },
      });
      this.$router.push({ name: "broker_list" }, () => {});
    },
  },
  created() {
    // if (this.$route.params.id == "new") this.item.role = "broker";
  },
};
</script>
