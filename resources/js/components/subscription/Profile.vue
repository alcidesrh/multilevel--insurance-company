<template>
  <v-container fluid v-if="item">
    <v-row>
      <v-col>
        <v-form ref="form" lazy-validation>
          <v-row>
            <v-col cols="12" md="6" lg="4">
              <v-text-field v-model="item.title" label="Nombre" :rules="fieldRequire" required></v-text-field>
            </v-col>
            <v-col cols="6" md="4" lg="2">
              <v-checkbox v-model="item.active" label="Activa"></v-checkbox>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="2">
              <v-select
                v-model="item.role"
                :items="roles.filter(i => i.id != 1)"
                label="Rol"
                item-value="id"
                item-text="name"
              ></v-select>
            </v-col>
            <v-col cols="12" md="3">
              <v-text-field v-model="item.type" label="Tipo"></v-text-field>
            </v-col>
            <v-col cols="6" md="2">
              <v-text-field v-model="item.price" label="Precio" ></v-text-field>
            </v-col>

            <v-col cols="6" md="2">
              <v-text-field v-model="item.duration" label="Duración"></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-textarea rows="4" v-model="item.description" outlined label="Descripción"></v-textarea>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" class="d-flex justify-end">
              <v-btn
                rounded
                color="primary"
                dark
                class="mr-3"
                @click="$router.push({ name: 'subscription_list' })"
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
    ...mapFields("subscription", ["item", "items", "loadingItem"]),
    ...mapFields("role", { roles: "items" }),
  },
  methods: {
    ...mapActions({
      getItem: "subscription/getItem",
      saveAction: "subscription/save",
      getRoles: "role/getTableList",
    }),
    save() {
      if (!this.$refs.form.validate()) return;
      this.saveAction("subscription").then((response) => {

        let index = 0;
        if (this.item.id) {
          this.items.filter((i, index2) => {
            if (i.id == this.item.id) {
              index = index2;
            }
          });
        }
        this.$set(this.items, index, response.data.data);
        this.$router.push({name: 'subscription_list'});
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
        endpoint: "subscription",
        query: { id: decodeURIComponent(id) },
      }).then(() => {
      });
    else {
      this.item = {};
    }
  },
  destroyed() {
    this.item = null;
  },
};
</script>