<template>
  <v-container fluid v-if="item">
    <v-row>
      <v-col>
        <v-form ref="form" lazy-validation>
          <v-row>
            <v-col cols="12" md="6" lg="4">
              <v-text-field v-model="item.title" label="Nombre" :rules="fieldRequire" required></v-text-field>
            </v-col>
            <v-col cols="6" md="4" lg="2" class="d-flex justify-end">
              <v-btn
                rounded
                color="primary"
                dark
                class="mr-3"
                @click="$router.push({ name: 'license_list' })"
              >Cancelar</v-btn>
              <v-btn rounded color="primary" dark @click="save()" :loading="loadingItem">Guardar</v-btn>
            </v-col>
          </v-row>
        </v-form>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";
import { fieldRequire } from "../../util";

export default {
  data: () => ({
    fieldRequire: fieldRequire,
  }),
  computed: {
    ...mapFields("license", ["item", "items", "loadingItem"]),
    ...mapFields("role", { roles: "items" }),
  },
  methods: {
    ...mapActions({
      getItem: "license/getItem",
      saveAction: "license/save",
      getRoles: "role/getTableList",
      getItems: "license/getItems",
    }),
    save() {
      if (!this.$refs.form.validate()) return;
      this.saveAction("license").then((response) => {
        if (response.data == "created") this.getItems("licenses");
        else {
          let index = 0;
          if (this.item.id) {
            this.items.filter((i, index2) => {
              if (i.id == this.item.id) {
                index = index2;
              }
            });
          }
          this.$set(this.items, index, response.data.data);
        }
        this.$router.push({ name: "license_list" });
      });
    },
  },
  created() {
    if (this.roles.length == 0)
      this.getRoles({
        query: { table: "roles", fields: ["id", "name"] },
        saveState: true,
      });
    const id = this.$route.params.id;
    if (id && id != "new")
      this.getItem({
        endpoint: "license",
        query: { id: decodeURIComponent(id) },
      }).then(() => {});
    else {
      this.item = {};
    }
  },
  destroyed() {
    this.item = null;
  },
};
</script>