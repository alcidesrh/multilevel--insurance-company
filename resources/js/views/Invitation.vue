<template>
  <div>
    <v-row v-if="!paid">
      <v-col cols="12" md="8">
        <p>Usted debe pagar su membresía para poder acceder al sistema.</p>
        <strong v-if="subscription"
          >Membresia: {{ subscription.price | money }}</strong
        >
      </v-col>
      <v-col cols="12" md="8">
        <PaymentForm
          ref="payment"
          :nonce.sync="nonce"
          :loading.sync="loading"
        />
      </v-col>

      <v-col cols="12" md="8" class="d-flex justify-end">
        <v-btn rounded color="primary" @click="getNonce()" :loading="loading"
          >Confirmar & Pagar</v-btn
        >
      </v-col>
    </v-row>

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
          <v-btn text color="primary" @click="home()" :loading="loading"
            >Ok</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
  <!-- <div>
    <v-form ref="form" lazy-validation>
      <v-row>
        <v-col cols="12" md="7">
          <v-text-field
            v-model="form.emails"
            label="Correo o correos separados por comas"
            :rules="fieldRequire"
            required
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="7">
          <v-text-field
            v-model="form.subject"
            label="Asunto"
            :placeholder="'Sino escribe ningún asunto se enviará el siguiente: Invitación a ' + (company ? company.name : 'DNF')"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="7">
          <v-textarea
            rows="6"
            v-model="form.message"
            outlined
            label="Mensaje"
            :placeholder="`Sino escribe ningún mensaje se enviará el siguiente: Usted a sido invitado por ${user.name} a unirse a la agencia ${(company ? company.name : 'DNF')}.`"
          ></v-textarea>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="7" class="d-flex justify-end">
          <v-btn rounded color="primary" dark @click="send" :loading="loading">Enviar</v-btn>
        </v-col>
      </v-row>
    </v-form>
  </div>-->
</template>

<script>
import { mapFields } from "vuex-map-fields";
import { mapActions } from "vuex";
import EventBus from "../event-bus";
import axios from "axios";
import PaymentForm from "../components/util/PaymentForm";

export default {
  components: {
    PaymentForm,
  },
  data: () => ({
    subscription: null,
    nonce: null,
    loading: false,
    message: null,
    messageDialog: null,
    paid: false
  }),
  computed: {
    ...mapFields("user", ["user"]),
  },
  watch: {
    nonce() {
      this.loading = true;
      axios
        .post("/api/user/pay", { nonce: this.nonce })
        .then((response) => {
          if (response.data.error)
            EventBus.$emit("alert", {
              text: response.data.error,
              color: "warning",
            });
          else if (response.data.success) {
            this.paid = true;
            this.message = response.data.success;
            this.messageDialog = true;
          }
          this.$store.dispatch("user/getUser");
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
  methods: {
    ...mapActions({
      getSubscription: "generic/getItemGenericTable",
    }),
    home() {
      this.$router.push({ name: "home" }, () => {});
    },
    getNonce() {
      this.loading = true;
      this.$refs.payment.onGetCardNonce();
    },
    // send() {
    //   if (!this.$refs.form.validate()) return;
    //   this.loading = true;
    //   console.log(this.form);
    //   axios
    //     .post("/api/send-invitation", {
    //       emails: this.form.emails,
    //       subject: this.form.subject,
    //       message: this.form.message,
    //     })
    //     .then((response) => {
    //       if (response.data.invalid) {
    //         const emails = response.data.invalid.join(", ");
    //         this.form.emails = emails;
    //         EventBus.$emit("alert", {
    //           text:
    //             response.data.invalid.length == 1
    //               ? `Correo invalido: ${emails}.`
    //               : `Correo invalido: ${emails}. Lo otros correos se han enviado.`,
    //           color: "warning",
    //         });
    //       } else {
    //         EventBus.$emit("alert", {
    //           text: response.data,
    //           color: "success",
    //         });
    //       }
    //     })
    //     .catch((e) => {
    //       EventBus.$emit("alert", {
    //         text: e.response.data.error,
    //       });
    //     })
    //     .finally(() => {
    //       this.loading = false;
    //     });
    // },
  },
  created() {
    if (!this.subscription)
      this.getSubscription({
        fields: ["price"],
        table: "subscriptions",
        where: { active: 1, type: "default", role_id: this.user.role },
      }).then((response) => (this.subscription = response));
  },
};
</script>