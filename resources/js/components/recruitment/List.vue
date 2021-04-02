<template>
  <div>
    <v-row>
      <v-col class="d-flex justify-start">
        <v-btn rounded color="primary" @click="invitationDialog = true">
          <v-icon size="20" class="mr-1">mdi-email</v-icon>Enviar Invitación
        </v-btn>
      </v-col>
      <v-col class="d-flex justify-end">
        <v-btn
          icon
          :color="!filterActive ? 'primary' : 'teal'"
          @click="filterActive = !filterActive"
        >
          <v-icon size="30">mdi-filter-menu</v-icon>
        </v-btn>
      </v-col>
    </v-row>

    <v-row v-if="filterActive">
      <v-col cols="12" md="6" lg="4">
        <v-text-field
          v-model="filters.first_name"
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
      <v-col cols="12" md="6" lg="4">
        <UserSelect :user.sync="parent" :users="usersSelect" label="Referido por" />
      </v-col>
      <v-col cols="12" md="6" lg="4">
        <v-select
          clearable
          v-model="filters.state"
          :items="[
          {text: 'Primera entrevista', value: 1, color: 'red'},
          {text: 'Contratado', value: 'hired', color: 'amber'},
          {text: 'Segunda entrevista', value: 'second_interview_assisted', color: 'warning'},
          {text: 'Primer entrenamiento', value: 'first_training_assisted', color: 'teal'},
          {text: 'Segundo entrenamiento', value: 'second_training_assisted', color: 'green'}]"
          label="Estados"
        >
          <template v-slot:item="data">
            <v-row>
              <v-card class="mx-3" width="20" height="20" :color="data.item.color"></v-card> {{data.item.text}}
            </v-row>
          </template>
        </v-select>
      </v-col>
      <v-col cols="12" md="6" lg="4">
        <DatePicker label="Desde" :date-param.sync="filters.from" />
      </v-col>
      <v-col cols="12" md="6" lg="4">
        <DatePicker :min="filters.from" label="Hasta" :date-param.sync="filters.to" />
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
            <template v-slot:item.created_at="{ item }">{{ item.created_at | dateFormat }}</template>
            <template v-slot:item.status="{ item }">
              <v-chip v-if="item.second_training_assisted" color="green" dark>segundo entrenamiento</v-chip>
              <v-chip
                v-else-if="item.first_training_assisted"
                color="teal"
                dark
              >primer entrenamiento</v-chip>
              <v-chip
                v-else-if="item.second_interview_assisted"
                color="warning"
                dark
              >segunda entrevista</v-chip>
              <v-chip v-else-if="item.hired" color="amber" dark>contratado</v-chip>
              <v-chip v-else color="red" dark>primera entrevista</v-chip>
            </template>
            <template v-slot:item.action="{ item }">
              <div style="white-space: nowrap;">
                <v-btn
                  icon
                  @click="$router.push({name: 'recruitment_profile', params: { id: item.id }})"
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

    <v-dialog v-model="invitationDialog" width="600" persistent>
      <v-card>
        <v-card-text class="py-5">

          <v-form ref="invitationPayment" lazy-validation>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="invitation.emails"
                  label="Uno o mas emails separados por coma"
                  required
                  :rules="fieldRequire"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text color="primary" @click="invitationDialog = false">Cancelar</v-btn>
          <v-btn text color="primary" @click="sendInvitation" :loading="loading">Enviar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";
import ListMixin from "../mixins/ListTableMixin";
import DatePicker from "../util/DatePicker";
import UserSelect from "../util/UserSelect";
import axios from "axios";
import { fieldRequire } from "../../util";
import EventBus from "../../event-bus";

export default {
  mixins: [ListMixin],
  components: {
    DatePicker,
    UserSelect,
  },
  data: () => ({
    parent: null,
    invitationDialog: false,
    fieldRequire: fieldRequire,
    invitation: {},
    loading: false,
    headers: [
      { text: "Creado", value: "created_at" },
      {
        text: "Nombre",
        align: "start",
        sortable: false,
        value: "first_name",
      },
      { text: "Apellido", value: "last_name" },
      { text: "	Email", value: "email" },
      { text: "Teléfono", value: "phone" },
      { text: "Referido por", value: "referrer" },
      { text: "Estado", value: "status" },
      { text: "", value: "action", sortable: false },
    ],
  }),
  computed: {
    ...mapFields("recruitment", [
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
    parent(val) {
      if (val) this.filters.parent = val.id;
      else this.filters.parent = null;
      this.getItems();
    },
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
      getItemsAction: "recruitment/getItems",
      getRemoveAction: "recruitment/remove",
      getUsers: "generic/getItemsGeneric",
      getCompanies: "generic/getItemsGeneric",
    }),
    getItems() {
      this.getItemsAction("recruitment");
    },
    remove(id) {
      if (window.confirm("Seguro desea eliminar este usuario."))
        this.getRemoveAction(`recruitment/${id}`).then((response) => {
          if (response.data == "deleted") this.getItems();
        });
    },
    sendInvitation() {
      if (!this.$refs.invitationPayment.validate()) return;

      this.loading = true;
      axios
        .post("/api/send-invitation", { emails: this.invitation.emails })
        .then((response) => {
          if (response.data.invalid) {
            const emails = response.data.invalid.join(", ");
            this.invitation.emails = emails;
            EventBus.$emit("alert", {
              text:
                response.data.invalid.length == 1
                  ? `Correo invalido: ${emails}.`
                  : `Correo invalido: ${emails}. Lo otros correos se han enviado.`,
              color: "warning",
            });
          } else {
            this.invitationDialog = false;
            this.invitation = {};
            EventBus.$emit("alert", {
              text: response.data,
              color: "success",
            });
          }
        })
        .catch((e) => {
          EventBus.$emit("alert", {
            text: e.response.data.error,
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
  created() {
      this.getUsers("users-select")
        .then((response) => {
          if (response.data.data.length) this.usersSelect = response.data.data;
        })
        .catch((e) => {
          console.log(e);
        });
  },
};
</script>
