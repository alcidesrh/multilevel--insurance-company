<template>
  <v-container fluid v-if="item">
    <v-row>
      <v-col cols="12" lg="3" class="px-0" order-lg="12">
        <v-card min-height="100%" class="mx-auto px-3" outlined elevation="5">
          <v-row class="justify-center mt-3">
            <v-avatar color="grey" size="80">
              <v-icon v-if="!item.image" dark size="80">mdi-office-building</v-icon>
              <img v-else :src="item.image.url" />
            </v-avatar>
          </v-row>
          <v-row class="justify-center mt-3 headline">{{item.name}}</v-row>
          <v-row>
            <v-col class="d-flex justify-center">
              <v-btn icon class="ml-2 mr-1" :disabled="!item.email" @click="user = {email: item.email, name: item.name}">
                <v-icon color="orange">mdi-email</v-icon>
              </v-btn>
              <v-btn icon class="mr-2 ml-1">
                <v-icon
                  color="teal"
                  @click="$router.push({name: 'profile', params: { id: item.id }})"
                >mdi-pencil</v-icon>
              </v-btn>
              <v-btn icon>
                <v-icon @click="remove()" color="red">mdi-delete</v-icon>
              </v-btn>
            </v-col>
          </v-row>

          <v-divider></v-divider>

          <v-row>
            <v-col class="d-flex justify-center px-0">
              <v-row>
                <v-col cols="12" class="py-0 justify-center d-flex">Venta total</v-col>
                <v-col cols="12" class="py-0 justify-center d-flex">$0</v-col>
              </v-row>
            </v-col>
            <v-col class="d-flex justify-center px-0">
              <v-row>
                <v-col cols="12" class="py-0 justify-center d-flex">Comisión total</v-col>
                <v-col cols="12" class="py-0 justify-center d-flex">$0</v-col>
              </v-row>
            </v-col>
          </v-row>

          <v-divider></v-divider>

          <v-row class="mt-3">
            <v-col cols="12" v-if="item.email">
              <v-icon class="mr-2">mdi-email</v-icon>
              {{item.email}}
            </v-col>
            <v-col cols="12" v-if="item.phone">
              <v-icon class="mr-2">mdi-phone</v-icon>
              {{item.phone}}
            </v-col>
            <v-col cols="12" v-if="item.address">
              <v-icon class="mr-2">mdi-map-marker</v-icon>
              {{item.address}}
            </v-col>
          </v-row>
        </v-card>
      </v-col>
      <v-col cols="12" lg="9" order-lg="1">
        <v-tabs v-model="tab">
          <v-tab href="#users">Personal</v-tab>
          <v-tab>Clientes</v-tab>
          <v-tab>Ventas</v-tab>
          <v-tab>Póliza</v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab" v-if="item">
          <v-tab-item value="users">
            <v-row>
              <v-col cols="12">
                <v-data-table
                  :headers="personalHeader"
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
                  <template v-slot:item.image="{ item }">
                    <div class="py-2">
                      <v-avatar color="grey" size="50">
                      <v-icon v-if="!item.image" size="50">mdi-account</v-icon>
                      <img v-else :src="item.image.url" />
                    </v-avatar>
                    </div>
                  </template>

                  <template v-slot:item.role="{ item }">{{item.role.name}}</template>

                  <template v-slot:item.active="{ item }">
                    <v-icon :color="item.active ? 'teal' : 'error'">mdi-check</v-icon>
                  </template>

                  <template v-slot:item.action="{ item }">
                    <div style="white-space: nowrap;">
                      <v-btn
                        icon
                        @click="$router.push({name: 'edit-user', params: { user: item }}, () => {})"
                      >
                        <v-icon size="20" color="teal">mdi-pencil</v-icon>
                      </v-btn>
                      <v-btn icon @click="remove(item.id)">
                        <v-icon size="20" color="red">mdi-delete</v-icon>
                      </v-btn>
                    </div>
                  </template>
                </v-data-table>
              </v-col>
            </v-row>
          </v-tab-item>
        </v-tabs-items>
      </v-col>
    </v-row>
    <EmailDialog v-if="user" :user="user" v-on:close="user = null" />
  </v-container>
</template>

<script>
import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";
import ListMixin from "../mixins/ListTableMixin";
import SearchMixin from "../mixins/SearchMixin";
import EmailDialog from "../util/EmailDialog";

export default {
  mixins: [ListMixin, SearchMixin],
  components:{
    EmailDialog
  },
  data: () => ({
    tab: 0,
    user: false,
    personalHeader: [
      { text: "", value: "image" },
      {
        text: "Nombre",
        align: "start",
        sortable: false,
        value: "fullName",
      },
      { text: "Email", value: "email" },
      { text: "	Tipo", value: "role" },
      { text: "Activo", value: "active" },
      { text: "", value: "action", sortable: false },
    ],
  }),
  computed: {
    ...mapFields("company", ["item"]),
    ...mapFields("generic", [
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
    remove(){

    },
    ...mapActions({
      getItem: "company/getItem",
      resetPersonal: "generic/reset",
      getItemsAction: "generic/getItems",
    }),
    getItems(){
      const id = this.$route.params.id;
      this.getItemsAction({
        endpoint: "company/personal",
        query: { id: decodeURIComponent(id) },
      });
    }
  },
  created() {
    const id = this.$route.params.id;
    if (id)
      this.getItem({
        endpoint: "company/show",
        query: { id: decodeURIComponent(id) },
      }).then(() => {
      });
  },
  destroyed() {
    this.item = null;
    this.resetPersonal();
  },
};
</script>