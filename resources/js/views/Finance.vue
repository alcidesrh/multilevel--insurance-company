<template>
  <div>
    <v-row>
      <v-col cols="12" class="d-flex justify-end"> 
        <a
                target="_blank"
                href="/finance/pdf/download"
                style="text-decoration: none"
              >
                <v-icon class="cursor-pointer" color="teal" size="40"
                  >mdi-file-pdf</v-icon
                >
              </a>
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
              itemsPerPageOptions: [10, 15, 30, 100, -1],
            }"
          >
            <template v-slot:item.created_at="{ item }">{{
              item.created_at | dateFormat
            }}</template>
            <template v-slot:item.user="{ item }">
              <div v-if="item.user" style="white-space: nowrap;">
                <v-avatar size="40" color="grey">
                  <v-icon v-if="!item.user.image" dark size="40"
                    >mdi-account</v-icon
                  >
                  <img v-else :src="item.user.image.url" />
                </v-avatar>
                <label class="ml-3"
                  >{{ item.user.name }}</label
                >
              </div>
              <div v-else>No disponible</div>
            </template>
            <template v-slot:item.user2="{ item }">
              <div v-if="item.user2" style="white-space: nowrap;">
                <v-avatar size="40" color="grey">
                  <v-icon v-if="!item.user2.image" dark size="40"
                    >mdi-account</v-icon
                  >
                  <img v-else :src="item.user2.image.url" />
                </v-avatar>
                <label class="ml-3"
                  >{{ item.user2.name }}</label
                >
              </div>
              <div v-else>No disponible</div>
            </template>
            <template v-slot:item.amount="{ item }">{{
              item.amount | money
            }}</template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";
import ListMixin from "../components/mixins/ListTableMixin";

export default {
  mixins: [ListMixin],
  data: () => ({
    headers: [
      { text: "Creado", value: "created_at" },
      {
        text: "Remitente",
        align: "start",
        sortable: false,
        value: "user",
      },
      { text: "Rol", value: "user.role.name" },
      { text: "Beneficiado", value: "user2" },
      { text: "Rol", value: "user2.role.name" },
      { text: "Acción", value: "action" },
      { text: "Status", value: "status" },
      { text: "Transferido", value: "amount" },

      { text: "Id", value: "id" },
    ],
  }),
  computed: {
    ...mapFields("finance", [
      "page",
      "items",
      "loadingList",
      "perPage",
      "pages",
      "total",
      "search",
      ,
      "filterActive",
      "filters",
    ]),
    ...mapFields("user", ["usersSelect"]),
  },
  watch: {
    "filters.from": function (val) {
      this.getItems();
    },
    "filters.to": function (val) {
      this.getItems();
    },
    "filters.state": function (val) {
      this.getItems();
    },
    "filters.first_name"(val) {
      if (!val) {
        this.loadingList = false;
        if (this.page != 1) this.page = 1;
        else this.getItems();
        return;
      }

      clearTimeout(this.timeout);

      // Make a new timeout set to go off in 1000ms to wait stop write
      this.timeout = setTimeout(() => {
        if (val.length < 3) {
          return;
        }

        if (this.page != 1) this.page = 1;
        else this.getItems();
      }, 1000);
    },
    "filters.email"(val) {
      if (!val) {
        this.loadingList = false;
        if (this.page != 1) this.page = 1;
        else this.getItems();
        return;
      }

      clearTimeout(this.timeout);

      // Make a new timeout set to go off in 1000ms to wait stop write
      this.timeout = setTimeout(() => {
        if (val.length < 3) {
          return;
        }

        if (this.page != 1) this.page = 1;
        else this.getItems();
      }, 1000);
    },
    "filters.last_name"(val) {
      if (!val) {
        this.loadingList = false;
        if (this.page != 1) this.page = 1;
        else this.getItems();
        return;
      }

      clearTimeout(this.timeout);

      // Make a new timeout set to go off in 1000ms to wait stop write
      this.timeout = setTimeout(() => {
        if (val.length < 3) {
          return;
        }

        if (this.page != 1) this.page = 1;
        else this.getItems();
      }, 1000);
    },
    "filters.phone"(val) {
      if (!val) {
        this.loadingList = false;
        if (this.page != 1) this.page = 1;
        else this.getItems();
        return;
      }

      clearTimeout(this.timeout);

      // Make a new timeout set to go off in 1000ms to wait stop write
      this.timeout = setTimeout(() => {
        if (val.length < 3) {
          return;
        }

        if (this.page != 1) this.page = 1;
        else this.getItems();
      }, 1000);
    },
  },
  methods: {
    ...mapActions({
      getItemsAction: "finance/getItems",
      getRemoveAction: "finance/remove",
      getUsers: "generic/getItemsGeneric",
      getCompanies: "generic/getItemsGeneric",
    }),
    getItems() {
      this.getItemsAction("finance");
    },
    // remove(id) {
    //   if (window.confirm("Seguro desea eliminar este usuario."))
    //     this.getRemoveAction(`finance/${id}`).then((response) => {
    //       if (response.data == "deleted") this.getItems();
    //     });
    // }
  },
};
</script>