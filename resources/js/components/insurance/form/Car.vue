<template>
  <v-container fluid>
    <v-row v-if="!item">
      <v-col class="d-flex">
        <a
          v-if="items.length"
          target="_blank"
          :href="'/api/insurance/car' + getQuery()"
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
                  :href="'/insurance/pdf/2/' + item.id + '/show'"
                  style="text-decoration: none"
                >
                  <v-icon class="cursor-pointer" color="teal">mdi-eye</v-icon>
                </a>
                <a
                  target="_blank"
                  :href="'/insurance/pdf/2/' + item.id"
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
              <div class="headline">Asegurado</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.client.name"
                label="Nombre"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.client.last_name"
                label="Apellido"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="4">
              <DatePicker
                label="Fecha de Nacimiento"
                birthday="true"
                :date-param.sync="item.client.birthday"
                :max="
                  new Date(
                    new Date().setFullYear(new Date().getFullYear() - 18)
                  )
                    .toISOString()
                    .substr(0, 10)
                "
              />
            </v-col>
            <v-col cols="12" md="8">
              <v-text-field
                v-model="item.client.address"
                label="Dirección Actual"
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
                prepend-inner-icon="mdi-pound"
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
                v-model="item.client.ssn"
                label="Social Sec"
                prepend-inner-icon="mdi-pound"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.vin"
                label="Vin del Vehículo"
                prepend-inner-icon="mdi-pound"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.license"
                label="Licencia/ID"
                prepend-inner-icon="mdi-license"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.cant"
                label="Cantidad de vehículos incluidos en la poliza"
                prepend-inner-icon="mdi-pound"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.policy"
                row
                label="Tiene poliza actual"
                :rules="fieldRequire"
                required
              >
                <v-radio label="Si" value="si"></v-radio>
                <v-radio label="No" value="no"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>

          <v-row v-if="item.policy == 'si'">
            <v-col cols="12" md="4">
              <v-text-field
                v-model="item.actual_company"
                label="Nombre de la compañia actual"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
              <v-text-field
                v-model="item.members_coverage"
                label="Miembros a recibir cobertura"
                prepend-inner-icon="mdi-pound"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
              <DatePicker
                label="Expiración"
                :date-param.sync="item.expiration"
                :min="new Date().toISOString().substr(0, 10)"
              />
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

    headers: [
      { text: "Creado", value: "created_at" },
      { text: "Cliente", value: "name" },
      { text: "Email", value: "email" },
      { text: "Teléfono", value: "phone" },
      { text: "Miembros", value: "members" },
      { text: "Adjuntos", value: "files" },
      { text: "PDF", value: "pdf" },
      { text: "", value: "action" },
    ],
  }),
  methods: {
    edit(id) {
      this.getItem(`insurance/car/${id}`);
    },
    getItems() {
      this.getItemsAction("insurance/car");
    },
    remove(id) {
      if (window.confirm("Seguro desea eliminar este elemento."))
        this.getRemoveAction(`insurance/car/${id}`).then((response) => {
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
        url: "/api/insurance/car",
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

  destroyed() {
    this.item = null;
  },
};
</script>
