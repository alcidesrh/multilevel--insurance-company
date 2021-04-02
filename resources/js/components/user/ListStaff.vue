<template>
  <div>
    <v-row>
      <v-col class="d-flex justify-end">
        <v-btn
          icon
          :color="!filterActive ? 'primary' : 'teal'"
          @click="filterActive = !filterActive"
        >
          <v-icon size="30">mdi-filter-menu</v-icon>
        </v-btn>
        <v-btn
          class="ml-2"
          rounded
          color="primary"
          @click="$router.push({name: 'edit-user', params: { user: {id: 'new', role: 'staff' } }}, () => {})"
        >
          <v-icon size="20" class="mr-1">mdi-plus</v-icon>Crear Usuario
        </v-btn>
      </v-col>
    </v-row>

    <v-row v-if="filterActive">
      <v-col cols="12" md="6" lg="4">
        <v-text-field
          v-model="filters.name"
          label="Nombre"
          prepend-inner-icon="mdi-magnify"
          clearable
          :loading="loadingList"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6" lg="4">
        <v-text-field
          v-model="filters.last_name"
          label="Apellido"
          prepend-inner-icon="mdi-magnify"
          clearable
          :loading="loadingList"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6" lg="4">
        <v-text-field
          v-model="filters.email"
          label="Email"
          prepend-inner-icon="mdi-magnify"
          clearable
          :loading="loadingList"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6" lg="4">
        <v-text-field
          v-model="filters.phone"
          label="Teléfono"
          prepend-inner-icon="mdi-magnify"
          clearable
          :loading="loadingList"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="4" lg="4">
        <v-text-field
          v-model="filters.number_account"
          label="Código personal"
          prepend-inner-icon="mdi-magnify"
          clearable
          :loading="loadingList"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6" lg="4" class="d-flex">
        
        <v-select
          clearable
          v-model="filters.subscription"
          :items="[{text: 'Subscripción activa', value: 'active'}, {text: 'Subscripción expirada', value: 'inactive'}]"
          label="Subscripción"
        ></v-select>
      </v-col>
      <v-col cols="12" md="12" lg="4">
        <v-select
          clearable
          v-model="filters.license"
          :items="licenses"
          label="Licencia"
          item-value="id"
          item-text="title"
        ></v-select>
      </v-col>
      <v-col cols="12" md="6" lg="4">
        <DatePicker label="Desde" :date-param.sync="filters.from" />
      </v-col>
      <v-col cols="12" md="6" lg="4">
        <DatePicker :min="filters.from" label="Hasta" :date-param.sync="filters.to" />
      </v-col>
    </v-row>

    <v-row v-if="items.length">
      <v-col cols="12" md="6" lg="4" class="pa-1" v-for="(item, index) in items" :key="index">
        <ListUserCard :item="item" role="staff" v-on:remove="remove" />
      </v-col>
    </v-row>
    <v-row v-else>
      <v-col cols="12">
        <Alert text="No se encontraron representantes" />
      </v-col>
    </v-row>

    <v-pagination
      class="mt-5"
      v-show="!loadingList && pages"
      v-model="page"
      :length="pages"
      :total-visible="pages - perPage > 5 ? 5 : pages"
    ></v-pagination>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";
import ListUserCard from "./ListUserCard";
import Alert from "../util/Alert";
import EventBus from "../../event-bus";
import ListMixin from "../mixins/ListMixin";
import SearchUserMixin from "../mixins/SearchUserMixin";

export default {
  mixins: [ListMixin, SearchUserMixin],
  components: {
    ListUserCard,
    Alert,
  },
  computed: {
    ...mapFields("staff", [
      "page",
      "items",
      "loadingList",
      "perPage",
      "pages",
      "search",
      "filterActive",
      "filters",
    ]),
  },
  methods: {
    ...mapActions({
      removeUser: "user/remove",
      getItemsAction: "staff/getItems",
    }),
    getItems() {
      this.getItemsAction({
        endpoint: "users",
        query: { role: "staff" },
      });
    },
    remove(id) {
      if (window.confirm("Seguro desea eliminar este usuario.")) {
        this.removeUser("users/" + id).then((response) => {
          if (response.data == "deleted") {
            EventBus.$emit("alert", {
              text: "Se ha eliminado el usuario",
              color: "success",
            });
            this.getItems();
          }
        });
      }
    },
  },
};
</script>
