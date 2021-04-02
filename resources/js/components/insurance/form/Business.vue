<template>
  <v-container fluid>
    <v-row v-if="!item">
      <v-col class="d-flex">
        <a
          v-if="items.length"
          target="_blank"
          :href="'/api/insurance/business' + getQuery()"
          style="text-decoration: none"
        >
          <v-icon color="teal" class="cursor-pointer mr-3" size="35"
            >mdi-file-pdf</v-icon
          >
        </a>
        <v-icon v-else disabled class="cursor-pointer mr-3" size="35"
          >mdi-file-pdf</v-icon
        >
      </v-col>
      <v-col class="d-flex justify-end">
        <v-btn
          class="mr-2"
          icon
          :color="!filterActive ? 'primary' : 'teal'"
          @click="filterActive = !filterActive"
        >
          <v-icon size="30">mdi-filter-menu</v-icon>
        </v-btn>
        <v-btn
          rounded
          color="primary"
          @click="
            item = {
              client: {},
            }
          "
        >
          Nuevo
        </v-btn>
      </v-col>
    </v-row>

    <v-row v-if="filterActive && !item">
      <v-col cols="12" md="6" lg="4">
        <v-text-field
          v-model="clienName"
          label="Nombre del cliente"
          prepend-inner-icon="mdi-magnify"
          clearable
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="3" lg="2">
        <DatePicker
          label="Desde"
          :date-param.sync="filters.from"
          :max="new Date().toISOString().substr(0, 10)"
        />
      </v-col>
      <v-col cols="12" md="3" lg="2">
        <DatePicker
          :min="filters.from"
          :max="new Date().toISOString().substr(0, 10)"
          label="Hasta"
          :date-param.sync="filters.to"
        />
      </v-col>

      <v-col cols="12" md="12" lg="8">
        <UserSelectMultiple
          :usersSelected.sync="agentsSelected"
          :users="agents"
          label="Agentes"
        />
      </v-col>
    </v-row>

    <v-row v-if="!item">
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

            <template v-slot:item.name="{ item }">
              <span v-if="item.client">{{
                item.client.name + " " + item.client.last_name
              }}</span>
              <span v-else>----</span>
            </template>
            <template v-slot:item.email="{ item }">
              <span v-if="item.client">{{ item.client.email }}</span>
              <span v-else>----</span>
            </template>
            <template v-slot:item.phone="{ item }">
              <span v-if="item.client">{{ item.client.phone }}</span>
              <span v-else>----</span>
            </template>
            <template v-slot:item.files="{ item }">
              <div
                :style="{
                  maxHeight: item.files.length > 4 ? '100px' : 'auto',
                  overflowY: item.files.length > 4 ? 'scroll' : 'none',
                }"
              >
                <div v-for="(file, index) in item.files" :key="index">
                  <v-hover v-slot="{ hover }">
                    <div>
                      {{ file.name }}
                      <a
                        target="_blank"
                        :href="'/file/download/' + file.id"
                        style="text-decoration: none"
                      >
                        <v-icon
                          :color="hover ? 'teal' : ' '"
                          class="cursor-pointer"
                          >mdi-download</v-icon
                        >
                      </a>
                    </div>
                  </v-hover>
                </div>
              </div>
            </template>
            <template v-slot:item.pdf="{ item }">
              <div style="white-space: nowrap">
                <a
                  target="_blank"
                  :href="'/insurance/pdf/4/' + item.id + '/show'"
                  style="text-decoration: none"
                >
                  <v-icon class="cursor-pointer" color="teal">mdi-eye</v-icon>
                </a>
                <a
                  target="_blank"
                  :href="'/insurance/pdf/4/' + item.id"
                  style="text-decoration: none"
                >
                  <v-icon class="cursor-pointer" color="teal"
                    >mdi-file-pdf</v-icon
                  >
                </a>
              </div>
            </template>
            <template v-slot:item.action="{ item }">
              <div style="white-space: nowrap">
                <v-btn icon @click="edit(item.id)">
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

    <v-row v-if="item">
      <v-col>
        <v-form ref="form" lazy-validation>
          <v-row>
            <v-col cols="12">
              <div class="headline">Información del negocio</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.business_name"
                label="Nombre"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.business_name_dba"
                label="Nombre DBA"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.client.name"
                label="Persona de contacto"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.client.email"
                label="Email"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.client.phone"
                label="Teléfono"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.client.address"
                label="Dirección"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12">
              <v-text-field
                v-model="item.client.city"
                label="Ciudad, Estado, Zipcode"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.tax_id"
                label="Federal Tax Id"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.annual_gross"
                label="Ventas Brutas Estimadas"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.annual_visa_mc_sales"
                label="Anual Visa / MC Ventas"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.ticket"
                label="Promedio Ticket / Ticket más alto"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12">
              <v-radio-group v-model="item.type" row>
                <v-radio
                  label="Sole Ownership"
                  value="Sole Ownership"
                ></v-radio>
                <v-radio label="Partnership" value="Partnership"></v-radio>
                <v-radio
                  label="Non-Profit/Tax Exempt"
                  value="Non-Profit/Tax Exempt"
                ></v-radio>
                <v-radio label="Corporation" value="Corporation"></v-radio>
                <v-radio label="LLC" value="LLC"></v-radio>
                <v-radio label="Government" value="Government"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="3">
              <DatePicker
                label="Inicio del Negocio"
                :date-param.sync="item.date"
                :min="new Date().toISOString().substr(0, 10)"
              />
            </v-col>
            <v-col cols="12" md="3">
              <v-text-field
                v-model="item.product_sold"
                label="Producto / SVCS Vendidos"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="3">
              <v-text-field
                v-model="item.employees"
                label="Número de Empleados"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="3">
              <v-text-field
                v-model="item.website"
                label="Página Web"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12">
              <div class="headline">Información del dueño</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.owner_name"
                label="Nombre"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.owner_phone"
                label="Teléfono"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.owner_social_security"
                label="Seguridad Social"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.owner_address"
                label="Dirección"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <DatePicker
                label="Fecha de Nacimiento"
                birthday="true"
                :date-param.sync="item.owner_birthday"
                :max="
                  new Date(
                    new Date().setFullYear(new Date().getFullYear() - 18)
                  )
                    .toISOString()
                    .substr(0, 10)
                "
              />
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.owner_license"
                label="# Licencia de Conducción / EXP / Estado"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12">
              <div class="headline">Información del banco</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.bank_name"
                label="Nombre"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.bank_routing"
                label="Routing (9 números)"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.bank_account"
                label="Número de cuenta"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.bank_pos"
                label="Terminal / POS / Software"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="8">
              <v-textarea
                v-model="item.comment"
                label="Comentario"
                outlined
              ></v-textarea>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <FileUpload
                :files.sync="item.files"
                :file-type="true"
                :multiple="true"
                :files-input="item.files"
                v-on:remove="setRemovedFiles"
              />
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" class="d-md-flex justify-md-end text-center">
              <v-btn
                @click="item = null"
                rounded
                color="primary"
                class="mr-2"
                dark
                >Cancelar</v-btn
              >
              <v-btn
                @click="save"
                rounded
                color="primary"
                dark
                :loading="loading"
                >Guardar</v-btn
              >
            </v-col>
          </v-row>
        </v-form>
      </v-col>
    </v-row>
  </v-container>
</template>


<script>
import { fieldRequire } from "../../../util";
import { isNumber } from "../../../util";

import DatePicker from "../../util/DatePicker";

import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";

import ListMixin from "../../mixins/ListTableMixin";

import axios from "axios";

import EventBus from "../../../event-bus";

import FileUpload from "../../util/FileUpload";

import InsuranceMixin from "./InsuranceMixin";

export default {
  mixins: [ListMixin, InsuranceMixin],
  components: {
    DatePicker,
    FileUpload,
  },
  data: () => ({
    fieldRequire: fieldRequire,
    countries: [],
  }),

  methods: {
    edit(id) {
      this.getItem(`insurance/business/${id}`);
    },
    getItems() {
      this.getItemsAction("insurance/business");
    },
    remove(id) {
      if (window.confirm("Seguro desea eliminar este elemento."))
        this.getRemoveAction(`insurance/business/${id}`).then((response) => {
          if (response.data == 1) {
            EventBus.$emit("alert", {
              text: "Se ha eliminado correctamente",
              color: "success",
            });
            this.getItems();
          }
        });
    },
    save() {
      if (!this.$refs.form.validate()) return;

      this.item.removedFiles = this.removedFiles;

      this.loading = true;
      axios({
        method: "post",
        url: "/api/insurance/business",
        data: this.objectToFormData(this.item),
        headers: { "Content-Type": "multipart/form-data" },
      })
        .then((response) => {
          this.item = null;
          EventBus.$emit("alert", {
            text: response.data,
            color: "success",
          });
        })
        .catch((e) => {
          EventBus.$emit("alert", {
            text: e.response.data,
            color: "error",
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },

  created() {
    this.page = 1;
    this.getItems();

    this.getCountries({
      query: { table: "country", fields: ["id", "name"] },
    }).then((response) => {
      this.countries = response.data.data;
    });
  },
  destroyed() {
    this.item = null;
  },
};
</script>
