<template>
  <v-container fluid v-if="item">
    <div>
      <v-row>
        <v-col>
          <v-form ref="form" lazy-validation>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field v-model="item.name" label="Nombre" :rules="fieldRequire" required></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-select
                  clearable
                  v-model="item.parent_id"
                  :items="categories"
                  label="Categoría padre"
                  item-text="name"
                  item-value="id"
                ></v-select>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="8">
                <v-select
                  clearable
                  multiple
                  v-model="children"
                  :items="categoriesSelectChildren"
                  label="Categoría hijos"
                  item-text="name"
                  item-value="id"
                ></v-select>
              </v-col>

              <v-col cols="12" md="4" class="d-flex justify-end align-end">
                <v-btn
                  rounded
                  color="primary"
                  dark
                  class="mr-3"
                  @click="$router.push({ name: 'category_list' })"
                >Cancelar</v-btn>
                <v-btn rounded color="primary" dark @click="save()" :loading="loadingItem">Guardar</v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-col>
      </v-row>
    </div>
  </v-container>
</template>

<script>
import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";
import { fieldRequire } from "../../util";

export default {
  data: () => ({
    fieldRequire: fieldRequire,
    children: []
  }),
  computed: {
    ...mapFields("category", ["item", "items", "loadingItem", "categories"]),
    categoriesSelectChildren() {
      if (!this.item.parent_id) return this.categories;

      let ids = [];

      for (let i = 0; i < this.items.length; i++) {
        ids = this.getChildren([this.items[i]]).map(item => item.id);

        if (ids.includes(this.item.parent_id)) break;

        ids = [];
      }
      return this.categories.filter(item => !ids.includes(item.id));
    }
  },
  watch: {
    children(val) {
      if (val.includes(this.item.parent_id)) this.item.parent_id = null;
    }
  },
  methods: {
    getChildren(childs) {
      let children = [];
      childs.forEach(i => {
        children.push(i);
        if (i.id == this.item.parent_id) return children;
        if (i.children.length) children.push(...this.getChildren(i.children));
      });
      return children;
    },
    ...mapActions({
      getItem: "category/getItem",
      saveAction: "category/save",
      getCategories: "generic/getTableList",
      getItems: "category/getItems"
    }),
    save() {
      if (!this.$refs.form.validate()) return;
      this.item.children = this.children;
      this.saveAction("category").then(item => {
        this.getItems("categories");
        this.$router.push({ name: "category_list" });
      });
    }
  },
  async created() {
    const id = this.$route.params.id;

    if (!this.items.length) this.getItems("categories");

    let categories = this.categories;
    
    if (!this.categories.length)
      await this.getCategories({
        query: { table: "categories", fields: ["id", "name"] }
      }).then(response => {
        categories = response.data.data;
      });

    if (id && id != "new")
      this.getItem({
        endpoint: "category",
        query: { id: decodeURIComponent(id) }
      }).then(() => {
        this.categories = categories.filter(i => i.id != this.item.id);
        this.children = this.item.children.map(i => i.id);
      });
    else {
      this.categories = categories;
      this.item = {};
    }
  },
  destroyed() {
    this.item = null;
  }
};
</script>