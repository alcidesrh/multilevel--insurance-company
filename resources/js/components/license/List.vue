<template>
  <div>
    <v-row>
      <v-col
        cols="12"
        md="6"
        order-md="2"
        class="d-flex justify-end"
        v-if="$can('create', 'license')"
      >
        <v-btn rounded color="primary" @click="$router.push({name: 'license_new'})">
          <v-icon size="20" class="mr-1">mdi-plus</v-icon>Crear licencia
        </v-btn>
      </v-col>
      <v-col cols="12" md="6" order-md="1">
        <v-text-field
          style="max-width: 300px"
          v-model="search "
          label="Buscar"
          prepend-inner-icon="mdi-magnify"
          clearable
          :loading="loadingList"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <v-card elevation="3">
          <v-data-table
            :headers="headers"
            :items="items"
            :server-items-length="total"
            :options.sync="options"
            :loading="loadingList"
            class="elevation-1"
            :items-per-page="perPage"
            :footer-props="{
            showFirstLastPage: true,
            itemsPerPageOptions: [10,15,30,100,-1]
    }"
          >
            <template v-slot:item.action="{ item }">
              <div style="white-space: nowrap;">
                <v-btn
                  icon
                  @click="$router.push({name: 'license_profile', params: { id: item.id }})"
                >
                  <v-icon size="20" color="teal">mdi-pencil</v-icon>
                </v-btn>

                <v-btn icon @click="remove(item.id)">
                  <v-icon size="20" color="red">mdi-delete</v-icon>
                </v-btn>
              </div>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";
import ListMixin from "../mixins/ListTableMixin";
import SearchMixin from "../mixins/SearchMixin";

export default {
  mixins: [ListMixin, SearchMixin],
  data: () => ({
    headers: [
      { text: "Nombre", value: "title" },
      { text: "", value: "action", sortable: false },
    ],
  }),
  computed: {
    ...mapFields("license", [
      "page",
      "items",
      "loadingList",
      "perPage",
      "pages",
      "total",
      "search",
    ]),
  },
  methods: {
    ...mapActions({
      getItemsAction: "license/getItems",
      getRemoveAction: "license/remove",
    }),
    getItems() {
      this.getItemsAction("licenses");
    },
    remove(id) {
      if (window.confirm("Seguro desea eliminar esta licencia."))
        this.getRemoveAction(`license/${id}`).then((response) => {
          if (response.data) this.getItems();
        });
    },
  },
};
</script>
