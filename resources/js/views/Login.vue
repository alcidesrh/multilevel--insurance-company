<template>
  <v-row class="h-100">
    <v-col cols="12" class="d-md-flex pa-0">
      <div class="flexible-container">
        <video autoplay muted loop id="myVidedo">
          <source src="/videos/company-video.mp4" type="video/mp4" />
          <source src="/videos/company-video.mp4" type="video/ogg" />
        </video>
        <v-card class="w-100 mr-0 mr-md-10 login-card" elevation="0">
          <div class="pa-5">
            <v-alert v-if="error" type="error" class="my-3">{{
              error.data.error || error.data.message
            }}</v-alert>
            <div class="text-h5 mt-10 white--text">Iniciar sesión</div>
            <v-form
              ref="form"
              v-model="valid"
              :lazy-validation="lazy"
              class="mb-10 mt-5"
            >
              <v-select
                :items="items"
                v-model="item"
                label="Select a test user"
                outlined
                solo
              >
                <template v-slot:selection="data">
                  <v-chip v-bind="data.attrs">
                    <v-avatar left>
                      <v-icon
                        color="grey"
                        v-if="!data.item.image"
                        dark
                        size="40"
                        >mdi-account</v-icon
                      >
                      <img v-else :src="data.item.image.url" alt="" />
                    </v-avatar>
                    {{ data.item.name }}
                  </v-chip>
                </template>
                <template v-slot:item="data">
                  <template v-if="typeof data.item == 'object'">
                    <v-list-item-avatar>
                      <v-icon
                        color="grey"
                        v-if="!data.item.image"
                        dark
                        size="40"
                        >mdi-account</v-icon
                      >
                      <img v-else :src="data.item.image.url" alt="" />
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title
                        v-html="data.item.name"
                      ></v-list-item-title>
                      <v-list-item-subtitle
                        v-html="data.item.group"
                      ></v-list-item-subtitle>
                    </v-list-item-content>
                  </template>
                  <template v-else>
                    <v-list-item-content
                      v-text="data.item"
                    ></v-list-item-content>                    
                  </template>
                </template>
              </v-select>

              <v-text-field
                :disabled="newPassword != ''"
                v-model="email"
                :rules="emailRules"
                label="Email"
                prepend-inner-icon="mdi-email"
                required
                solo
                outlined
              ></v-text-field>

              <v-text-field
                v-model="password"
                :rules="passwordRules"
                :label="newPassword ? 'Crear contraseña' : 'Contraseña'"
                prepend-inner-icon="mdi-lock"
                type="password"
                required
                solo
                outlined
              ></v-text-field>

              <v-text-field
                v-if="newPassword"
                v-model="password2"
                :rules="passwordRules"
                label="Repetir contraseña"
                prepend-inner-icon="mdi-lock"
                type="password"
                required
                solo
                outlined
              ></v-text-field>

              <v-checkbox
                dark
                v-if="!newPassword"
                v-model="remember"
                label="Recordar contraseña?"
                required
              ></v-checkbox>

              <v-btn
                min-width="100%"
                rounded
                @click="authenticate"
                color="primary"
                :loading="loading"
                >{{ "Iniciar sesión" }}</v-btn
              >

              <div class="mt-5 font-weight-600 white--text" v-if="!newPassword">
                <a @click="dialog = true" class="white--text"
                  >Olvide mi contraseña</a
                >
              </div>
            </v-form>
          </div>
        </v-card>
      </div>
    </v-col>
  </v-row>
</template>

<script>
import { mapActions } from "vuex";
import EventBus from "../event-bus";
import { mapFields } from "vuex-map-fields";
import { mapGetters } from "vuex";
import { fieldRequire, emailRequire } from "../util";

import axios from "axios";

export default {
  data: () => ({
    dialog: false,
    valid: true,
    valid2: true,
    password: "",
    password2: "",
    passwordRules: fieldRequire,
    email: "",
    email2: "",
    emailRules: emailRequire,
    remember: false,
    lazy: false,
    loading: false,
    loading2: false,
    items: [],
    item: null,
  }),

  computed: {
    ...mapGetters({
      host: "util/host",
    }),
    ...mapFields("user", ["error"]),
    newPassword() {
      if (typeof data == typeof "string") {
        const user = JSON.parse(data);
        return typeof user.email != typeof undefined ? user.email : false;
      }
      return false;
    },
  },

  watch: {
    item(val) {
      this.loading = true;
      this.$store
        .dispatch("user/authenticate", {
          email: val.email,
          password: val.role.slug == "admin" ? "admin" : "123",
          remember: true,
          newPassword: false,
        })
        .then(() => {
          this.loading = false;
        })
        .catch((e) => {
          this.error = e;
          console.log(error);
        });
    },
  },

  methods: {
    ...mapActions({
      resetPassword: "user/resetPassword",
    }),
    sendResetPasswordEmail() {
      if (!this.$refs.form2.validate()) return;
      this.loading2 = true;
      this.resetPassword({ email: this.email2 })
        .then((response) => {
          this.loading2 = false;
          if (response.status == 200) {
            this.dialog = false;
            EventBus.$emit("alert", {
              text: response.data,
              color: "success",
            });
          }
        })
        .catch((e) => {
          EventBus.$emit("alert", {
            text: e.response.data,
            color: "error",
          });
        })
        .finally(() => (this.loading2 = false));
    },
    reset() {
      this.$refs.form.reset();
    },
    resetValidation() {
      this.$refs.form.resetValidation();
    },
    authenticate() {
      if (this.$refs.form.validate()) {
        if (this.newPassword && this.password != this.password2) {
          this.error = {
            data: { error: "Las contraseñas deben ser iguales." },
          };
          return;
        }
        this.loading = true;
        this.$store
          .dispatch("user/authenticate", {
            email: this.email,
            password: this.password,
            remember: this.remember,
            newPassword: this.newPassword ? true : false,
          })
          .then(() => {
            this.loading = false;
          })
          .catch((e) => {
            this.error = e;
            console.log(error);
          });
      }
    },
  },
  created() {
    alert('login')
    this.email = this.newPassword || "";

    axios.get("/users-select-test").then((response) => {
      this.items = [{ header: "Role Admin" }, { divider: true }].concat(
        response.data.data
      );
      this.items.splice(
        3,
        0,
        ...[{ header: "Role Agency" }, { divider: true }]
      );
      this.items.splice(7, 0, ...[{ header: "Role Elite" }, { divider: true }]);
      this.items.splice(
        11,
        0,
        ...[{ header: "Role Broker" }, { divider: true }]
      );
    });
  },
};
</script>

<style scoped>
.v-messages__message {
  color: white !important;
  font-weight: bold;
}
#myVideo {
  position: fixed;
  min-height: 100%;
  left: -20%;
}

.flexible-container {
  position: relative;
  background-color: black;
  min-height: 100%;
  width: 100%;
  overflow: hidden;
}
.flexible-container video {
  position: absolute;
  top: 50%;
  left: 50%;
  min-width: 100%;
  min-height: 100%;
  width: auto;
  height: auto;
  z-index: 0;
  -ms-transform: translateX(-50%) translateY(-50%);
  -moz-transform: translateX(-50%) translateY(-50%);
  -webkit-transform: translateX(-50%) translateY(-50%);
  transform: translateX(-50%) translateY(-50%);
}

.login-card {
  position: absolute;
  max-width: 400px;
  right: 0;
  background-color: rgba(0, 0, 0, 0.4) !important;
  top: 50%;
  margin-top: -285px;
}

@media only screen and (max-width: 768px) {
  /* For mobile phones: */
  .login-card{
    top: 0;
    margin-top: 0px;
    height: 100%;
    background-color: transparent !important;
  }
}

@media only screen and (max-width: 1366px) {
  .flexible-container video {
    max-width: 290%;
    left: 37%;
  }
}
</style>