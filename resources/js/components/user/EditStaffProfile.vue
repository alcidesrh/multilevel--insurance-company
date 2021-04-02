<template>
  <Form role="staff" v-on:refresh-list="refreshList" />
</template>

<script>
import { mapFields } from "vuex-map-fields";
import { mapActions } from "vuex";
import Form from "./Form";

export default {
  components: {
    Form,
  },
  computed:{
    ...mapFields("user", ["user"]),
  },
  methods: {
    ...mapActions({
      getItems: "staff/getItems",
    }),
    refreshList() {
      this.getItems({
        endpoint: "users",
        query: { role: "staff" },
      });
      if (this.is(this.user, "staff"))
        this.$router.push({ name: "broker_list" }, () => {});
      else this.$router.push({ name: "staff_list" }, () => {});
    },
  },
};
</script>
