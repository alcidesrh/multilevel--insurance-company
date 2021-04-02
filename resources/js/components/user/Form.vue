<template>
  <v-container fluid v-if="item">
    <div>
      <v-row>
        <v-col>
          <v-form ref="form" lazy-validation>
            <v-row>
              <v-col cols="12" md="6">
                <FileUpload
                  :image.sync="image"
                  icon="mdi-account"
                  input-file="userImageInput"
                />
              </v-col>
              <v-col
                v-if="$can('user', 'admin')"
                cols="12"
                md="6"
                class="d-md-flex justify-md-end"
              >
                <v-checkbox v-model="item.active" label="Activado"></v-checkbox>
              </v-col>
            </v-row>
            <v-row v-if="is(item, 'agency')">
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="item.company_name"
                  label="Nombre de Agencia"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="item.name"
                  label="Nombre"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="item.last_name"
                  label="Apellido"
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="item.email"
                  label="Email"
                  :rules="emailRequire"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="3">
                <DatePicker
                  label="Fecha nacimiento"
                  birthday="true"
                  :date-param.sync="item.birthday"
                  :max="
                    new Date(
                      new Date().setFullYear(new Date().getFullYear() - 18)
                    )
                      .toISOString()
                      .substr(0, 10)
                  "
                />
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="item.phone"
                  label="Número telefónico"
                  v-on:keyup="item.phone = isNumber($event, item.phone)"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  v-model="item.address"
                  label="Dirección"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="5">
                <v-select
                  v-model="item.licenses"
                  :items="licenses"
                  label="Licencias"
                  item-value="id"
                  item-text="title"
                  multiple
                ></v-select>
              </v-col>
              <v-col cols="12" md="5" v-if="role != 'staff'">
                <v-text-field
                  v-model="item.title"
                  label="Título"
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="2" v-if="role != 'staff'">
                <v-text-field
                  v-model="item.commission_rate"
                  label="Comisión"
                  v-on:keyup="
                    item.commission_rate = isNumber(
                      $event,
                      item.commission_rate
                    )
                  "
                ></v-text-field>
              </v-col>
            </v-row>

            <div v-if="$can('change', 'family')">
              <v-row>
                <!-- <v-col cols="7" md="5" v-if="$can('change', 'company')">
                  <v-select
                    v-model="item.company_id"
                    :items="companySelect"
                    label="Agencias"
                    item-value="id"
                    item-text="name"
                  ></v-select>
                </v-col>-->
                <v-col cols="5" md="5">
                  <UserSelect
                    :user.sync="parent"
                    :users="usersSelect"
                    label="Upline"
                  />
                </v-col>
              </v-row>

              <v-row>
                <v-col cols="12">
                  <UserSelectMultiple
                    :usersSelected.sync="children"
                    :usersRemoved.sync="childrenRemoved"
                    :users="childrenSelect"
                    label="Downline"
                  />
                </v-col>
              </v-row>
            </div>

            <v-row v-if="role != 'staff'">
              <v-col cols="12">
                <v-radio-group v-model="item.salary" row label="Salario">
                  <v-radio label="$ 1000" :value="1000"></v-radio>
                  <v-radio label="$ 2000" :value="2000"></v-radio>
                </v-radio-group>
              </v-col>
            </v-row>

            <v-row v-if="paid && !item.id && $can('pay', 'subscription')">
              <v-col cols="12">
                <v-radio-group v-model="pay" :rules="fieldRequire" required>
                  <v-radio label="Pagar con tarjeta" value="yes"></v-radio>
                  <v-radio label="Enviar link de pago" value="no"></v-radio>
                </v-radio-group>
              </v-col>
            </v-row>

            <v-row
              v-if="
                pay == 'yes' && paid && !item.id && $can('pay', 'subscription')
              "
            >
              <v-col cols="12">
                <p>
                  Usted va a pagar la membresía del nuevo representante. Una
                  nueva cuenta será creada en el sistema y un correo será
                  enviado al nuevo representante para que active su cuenta.
                </p>
                <strong v-if="subscription"
                  >Membresia: {{ subscription.price | money }}</strong
                >
              </v-col>
              <v-col cols="12" class="pt-0">
                <PaymentForm
                  ref="payment"
                  :nonce.sync="nonce"
                  :loading.sync="loading"
                />
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" class="d-md-flex justify-md-end text-center">
                <v-btn
                  rounded
                  color="primary"
                  dark
                  class="mr-3"
                  @click="$router.push({ name: role + '_list' })"
                  >Cancelar</v-btn
                >
                <v-btn
                  rounded
                  color="primary"
                  dark
                  @click="save()"
                  :loading="loadingItem || loading"
                  >Guardar</v-btn
                >
                <v-btn
                  class="ml-3 mt-2 mt-md-0"
                  v-if="item && item.id && $can('promote', item)"
                  rounded
                  color="teal"
                  dark
                  @click="promote = promoteDialog = true"
                  :loading="loadingItem || loading"
                  >Promover</v-btn
                >
                <v-btn
                  class="ml-3 mt-2 mt-md-0"
                  v-if="item && item.id && $can('descender', item)"
                  rounded
                  color="warning"
                  dark
                  @click="
                    promote = false;
                    promoteDialog = true;
                  "
                  :loading="loadingItem || loading"
                  >Descender</v-btn
                >
              </v-col>
            </v-row>
          </v-form>
        </v-col>
      </v-row>
    </div>

    <v-dialog v-model="promoteDialog" width="600" persistent>
      <v-card>
        <v-card-text class="py-5">
          <p v-if="promote" class="headline" primary-title>
            Esta seguro que desea promover a {{ item.name }} a usuario
            {{ is(item, "broker") ? "Elite" : "Agencia" }}?
          </p>

          <p v-else class="headline" primary-title>
            Esta seguro que desea descender a {{ item.name }} a usuario
            {{ is(item, "agency") ? "Elite" : "Representante" }}?
          </p>
        </v-card-text>

        <!-- <v-card-text class="py-5" v-else-if="is(item, 'elite')">
          <p class="headline" primary-title>Promover a {{item.name}} a usuario Agencia.</p>

          <v-form ref="company">
            <v-row>
              <v-col cols="7" md="6">
                <v-select
                  v-model="company"
                  :items="companySelect"
                  label="Agencias"
                  item-value="id"
                  item-text="name"
                  :rules="fieldRequire"
                  required
                ></v-select>
              </v-col>
              <v-col cols="7" md="6" class="d-flex align-center">
                <v-btn small rounded color="teal" dark @click="agencyDialog = true">Crear Agencia</v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>-->

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text color="primary" @click="promoteDialog = false"
            >Cancelar</v-btn
          >
          <v-btn text color="primary" @click="savePromote" :loading="loading"
            >Promover</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- <v-dialog v-model="agencyDialog" width="800">
      <v-card>
        <v-card-text>
          <v-row>
            <v-col cols="12">
              <AgencyForm
                from-user="true"
                :close.sync="agencyDialog"
                :company-created.sync="company_new"
              />
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-dialog>-->

    <v-dialog v-model="messageDialog" width="600" persistent>
      <v-card>
        <v-card-title class="headline" primary-title
          >El pago se ha realizado</v-card-title
        >
        <v-card-text class="my-5">
          <p style="font-size: 16px">{{ message }}</p>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            text
            color="primary"
            @click="$emit('refresh-list')"
            :loading="loading"
            >Ok</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>


<script>
import { fieldRequire, emailRequire } from "../../util";
import { isNumber } from "../../util";

import UserSelect from "../util/UserSelect";
import FileUpload from "../util/FileUpload";
import UserSelectMultiple from "../util/UserSelectMultiple";
import DatePicker from "../util/DatePicker";
import PaymentForm from "../util/PaymentForm";
// import AgencyForm from "../company/Profile";

import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";

import EventBus from "../../event-bus";

export default {
  props: ["role"],
  components: {
    UserSelect,
    FileUpload,
    UserSelectMultiple,
    DatePicker,
    PaymentForm,
    // AgencyForm,
  },
  data: () => ({
    emailRequire: emailRequire,
    fieldRequire: fieldRequire,
    image: null,
    parent: null,
    children: null,
    childrenRemoved: [],
    pickerDialog: null,
    nonce: null,
    loading: null,

    promoteDialog: false,
    promote: null,
    // agencyDialog: false,
    // company_new: null,
    // company: null,

    message: null,
    messageDialog: null,

    subscription: null,

    paid: false,
    pay: null,
  }),
  watch: {
    // 'item.company_id'(v){
    //    this.getItems({ endpoint: "users-select", query: { company: v, parent: this.item.parent ? this.item.parent.id : null } })
    //     .then((response) => {
    //       if (response.data.data.length) this.usersSelect = response.data.data;
    //     })
    //     .catch((e) => {
    //       console.log(e);
    //     });
    // },
    // promoteDialog(val) {
    //   if (val) {
    //     this.company = this.company_new = null;
    //   }
    // },
    // company_new() {
    //   this.getCompanies({
    //     endpoint: "company-dinamic-list",
    //     fields: ["id", "name", "image"],
    //   }).then((response) => {
    //     this.companySelect = response.data.data;
    //     this.company = this.company_new;
    //     this.getCompaniesList("companies");
    //   });
    // },
    pickerDialog(val) {
      val && setTimeout(() => (this.$refs.picker.activePicker = "YEAR"));
    },
    nonce() {
      this.item.nonce = this.nonce;
      this.saveAction("user")
        .then((response) => {
          if (response.data.error)
            EventBus.$emit("alert", {
              text: response.data.error,
              color: "warning",
            });
          else {
            if (response.data) {
              this.paymentDialog = false;
              this.message = response.data;
              this.messageDialog = true;
            }
          }
        })
        .finally(() => (this.loading = false));
    },
  },
  computed: {
    ...mapFields("user", ["loadingItem", "usersSelect", "item", "user"]),
    ...mapFields("subscription", { subscriptions: "items" }),
    ...mapFields("license", { licenses: "items" }),
    ...mapFields("role", { roles: "items" }),
    // ...mapFields("company", ["companySelect"]),
    childrenSelect() {
      return this.usersSelect.filter(
        (item) => !this.parent || item.id != this.parent.id
      );
    },
  },
  methods: {
    savePromote() {
      this.promoteDialog = false;
      // if (this.company) {
      //   if (!this.$refs.company.validate()) return;

      //   this.item.company_id = this.company;
      //   this.item.role = 2;
      // } else this.item.role = 3;
      if (this.promote) this.item.role = this.is(this.item, "broker") ? 3 : 2;
      else this.item.role = this.is(this.item, "agency") ? 3 : 4;

      this.save().then(() => {
        this.getItemsAgency({
          endpoint: "users",
          query: { role: "agency", noFilters: true },
        });
        this.getItemsElite({
          endpoint: "users",
          query: { role: "elite", noFilters: true },
        });
        this.getItemsBroker({
          endpoint: "users",
          query: { role: "broker", noFilters: true },
        });
      });
    },
    isNumber(event, val) {
      return isNumber(event.keyCode) ? val : val.slice(0, -1);
    },
    ...mapActions({
      getItem: "user/getItemGeneric",
      getItems: "generic/getItemsGeneric",
      // getCompanies: "generic/getItemsGeneric",
      getRoles: "role/getTableList",
      getSubscription: "subscription/getTableList",
      // getCompaniesList: "company/getItems",
      getLicenses: "license/getTableList",
      saveAction: "user/save",
      getItemsAgency: "agency/getItems",
      getItemsElite: "elite/getItems",
      getItemsBroker: "broker/getItems",
      getUserFreeCount: "generic/getItemGenericTable",
    }),

    save() {
      if (!this.$refs.form.validate()) return;

      if (this.$can("change", "family")) {
        this.item.parent = this.parent;
        this.item.children = this.children
          ? this.children.map((i) => i.id)
          : [];
        this.item.childrenRemoved = this.childrenRemoved;
      }
      if (this.image && this.image.id) this.item.image = { id: this.image.id };
      else if (this.image)
        this.item.image = { url: this.image.url, name: this.image.name };

      if (
        this.pay == "yes" &&
        this.paid &&
        !this.item.id &&
        this.$can("pay", "subscription")
      ) {
        this.loading = true;
        this.$refs.payment.onGetCardNonce();
      } else {
        this.item.sendPayLink = this.pay == "no";

        return this.saveAction("user")
          .then((response) => {
            if (response.data.error)
              EventBus.$emit("alert", {
                text: response.data.error,
                color: "warning",
              });
            else {
              if (response.data) {
                EventBus.$emit("alert", {
                  text: response.data,
                  color: "success",
                });
                this.paymentDialog = false;
              }
              this.$emit("refresh-list");
            }
          })
          .finally(() => (this.loading = false));
      }
    },
  },

  async created() {
    this.parent = {};
    const id = this.$route.params.id;

    if (this.licenses.length == 0)
      this.getLicenses({
        query: { table: "licenses", fields: ["id", "title"] },
        saveState: true,
      });

    // if (this.companySelect.length == 0)
    //   this.getCompanies({
    //     endpoint: "company-dinamic-list",
    //     fields: ["id", "name", "image"],
    //   }).then((response) => (this.companySelect = response.data.data));

    if (!this.usersSelect.length) {
      this.getItems({
        endpoint: "users-select",
        // query: {
        //   parent: this.item.parent ? this.item.parent.id : null,
        // },
      })
        .then((response) => {
          if (response.data.data.length) this.usersSelect = response.data.data;
        })
        .catch((e) => {
          console.log(e);
        });
    }

    if (id && id != "new")
      await this.getItem({
        endpoint: "user/edit",
        query: { id: decodeURIComponent(id) },
      }).then((item) => {
        if (item.parent) {
          this.parent = item.parent;
        }
        this.image = item.image;
        this.$can("user", "admin");
        this.children = item.children;
        this.item = item;
      });
    else {
      if (this.subscriptions.length == 0)
        this.getSubscription({
          query: {
            table: "subscriptions",
            fields: ["price"],
            where: { role_id: this.role, type: "default" },
          },
          saveState: true,
        }).then((resp) => {
          if (resp.data.data[0]) this.subscription = resp.data.data[0];
        });

      this.item = {};

      //Crear staff gratis------------------------------------------  
      // if (this.is(this.user, "agency") && this.role == "staff") {
      if (this.role == "staff") {
        this.paid = false;   
        // this.getUserFreeCount({
        //   table: "create_free_user_count",
        //   where: { user_id: this.user.id },
        // }).then((response) => {
        //   this.paid = response.users > 2;
        // });
      } else this.paid = true;
    }
    if (this.roles.length == 0)
      this.getRoles({
        query: { table: "roles", fields: ["id", "name", "slug"] },
        saveState: true,
      }).then(() => {
        this.item.role = this.roles.filter((i) => i.slug == this.role)[0].id;
      });
    else this.item.role = this.roles.filter((i) => i.slug == this.role)[0].id;
  },
  destroyed() {
    this.item = null;
  },
};
</script>
