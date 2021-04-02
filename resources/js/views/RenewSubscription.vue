<template>
  <div>
    <v-row class="fill-height subscription" justify="center">
      <template v-for="(item, i) in subscriptions">
        <v-col :key="i" cols="12" md="4" v-if="!selected || selected == item">
          <v-hover v-slot:default="{ hover }">
            <v-card
              @click="selectItem(item)"
              :elevation="hover ? 12 : 2"
              :class="{ 'on-hover': hover || selected == item }"
              class="cursor-pointer"
              color="primary"
              style="min-height: 250px;"
            >
              <v-icon
                elevation="12"
                v-show="selected == item"
                size="40"
                color="success"
                style="position: absolute; right: 0; padding: 5px"
              >mdi-check-bold</v-icon>
              <v-card-title class="title white--text">
                <v-row class="fill-height flex-column" justify="space-between">
                  <p class="mt-4 text-h5 text-center font-weight-bold">{{ item.title }}</p>
                  <p class="mt-4 text-h5 text-center font-weight-bold">${{ item.price }}</p>

                  <div>
                    <p
                      class="pa-5 body-1 font-weight-bold font-italic text-center"
                    >{{ item.description }}</p>
                  </div>
                </v-row>
              </v-card-title>
            </v-card>
          </v-hover>
        </v-col>
      </template>
    </v-row>
    <v-form v-if="selected" ref="form" lazy-validation>
      <v-row>
        <v-col cols="12">
          <v-card-text>
            <strong>Pago: ${{selected.price}}</strong>
          </v-card-text>
        </v-col>
        <v-col cols="12">
          <PaymentForm ref="payment" :nonce.sync="nonce" :loading.sync="loading" />
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" class="d-flex justify-end">
          <v-btn rounded color="primary" dark class="mr-3" @click="selected = null">Cancelar</v-btn>
          <v-btn
            rounded
            color="primary"
            dark
            @click="getNonce"
            :loading="loading || loadingItem"
          >Aceptar</v-btn>
        </v-col>
      </v-row>
    </v-form>

    <v-dialog v-model="messageDialog" width="600" persistent>
      <v-card>
        <v-card-title class="headline" primary-title>El pago se ha realizado</v-card-title>
        <v-card-text class="my-5">
          <p style="font-size: 16px">{{message}}</p>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text color="primary" @click="getUser()" :loading="loading">Ok</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";
import { fieldRequire, emailRequire } from "../util";
import EventBus from "../event-bus";
import PaymentForm from "../components/util/PaymentForm";

export default {
  components: {
    PaymentForm,
  },
  data: () => ({
    fieldRequire: fieldRequire,
    pickerDialog: null,
    card: {},
    selected: null,
    subscriptions: [],
    nonce: null,
    loading: null,
    message: null,
    messageDialog: null,
  }),
  computed: {
    ...mapFields("user", ["user"]),
    ...mapFields("subscription", ["loadingItem"]),
  },
  watch: {
    nonce() {
      if (this.nonce) this.save();
    },
  },
  methods: {
    ...mapActions({
      getItems: "subscription/getTableList",
      renew: "subscription/renew",
    }),
    getNonce() {
      this.loading = true;
      this.$refs.payment.onGetCardNonce();
    },
    selectItem(item) {
      if (this.selected == item) {
        this.selected = null;
      } else {
        this.selected = item;
      }
    },
    getUser() {
      this.loading = true;
      this.$store.dispatch("user/getUser").then((response) => {
        this.$router.push({ name: "home" }, () => {});
        this.loading = false;
      });
    },
    save() {
      this.loading = true;
      this.renew({ subscription: this.selected.id, nonce: this.nonce })
        .then((response) => {
          if (response.data.error)
            EventBus.$emit("alert", {
              text: response.data.error,
              color: "warning",
            });
          else if (response.data.success) {
            this.message = response.data.success;
            this.messageDialog = true;
          }
        })
        .finally(() => (this.loading = false));
    },
  },
  created() {
    if (!this.subscriptions.length)
      this.getItems({
        query: {
          table: "subscriptions",
          fields: ["id", "title", "price", "duration", "description"],
          where: {
            role_id: this.user.role,
            type: "subscription",
            price: { operator: "!=", value: 0 },
          },
        },
      }).then((response) => {
        this.subscriptions = response.data.data;
      });
  },
};
</script>

<style scoped>
.subscription .v-card {
  transition: opacity 0.4s ease-in-out;
}

.subscription .v-card:not(.on-hover) {
  opacity: 0.6;
}

.subscription .show-btns {
  color: rgba(255, 255, 255, 1) !important;
}
</style>