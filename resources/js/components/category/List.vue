<template>
  <v-row>
    <v-col>
      <v-row>
        <v-col class="d-flex justify-end" v-if="$can('create', 'category')">
          <v-btn rounded color="primary" @click="$router.push({name: 'category_new'})">
            <v-icon size="20" class="mr-1">mdi-plus</v-icon>Crear Categoría
          </v-btn>
        </v-col>
      </v-row>
      <v-row v-if="items.length">
        <v-col cols="12" md="4" class="pa-1">
            <v-treeview open-all :items="items" activatable open-on-click>
              <template v-slot:label="{ item }">
                <v-hover v-slot:default="{ hover }">
                  <div>
                    {{item.name}}
                    <v-btn
                      @click="$router.push({name: 'category_edit', params: { id: item.id }})"
                      v-if="hover"
                      small
                      color="teal"
                      icon
                    >
                      <v-icon>mdi-pencil</v-icon>
                    </v-btn>
                  </div>
                </v-hover>
              </template>
            </v-treeview>
        </v-col>
      </v-row>
      <v-row v-else>
        <v-col cols="12">
          <Alert text="No se encontraron categorías" />
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";
import Alert from "../util/Alert";
import EventBus from "../../event-bus";

export default {
  components: { Alert },

  computed: {
    ...mapFields("category", [
      "items",
      "loadingList"
    ])
  },
  methods: {
    ...mapActions({
      getItemsAction: "category/getItems",
      getRemoveAction: "category/remove"
    }),
    getItems() {
      return this.getItemsAction("categories");
    },
    remove(id) {
      if (window.confirm("Seguro desea eliminar esta agencia."))
        this.getRemoveAction(`category/${id}`).then(response => {
          if (response.data == "deleted") {
            EventBus.$emit("alert", {
              text: "Se ha eliminado la agencia",
              color: "success"
            });
            this.getItems();
          }
        });
    }
  },
  created() {
    this.getItems();
  }
};
</script>
