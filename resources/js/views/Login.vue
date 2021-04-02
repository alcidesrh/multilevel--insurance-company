<template>
  <v-row class="h-100">
    <v-col cols="12" md="8" lg="9" class="d-none d-md-flex pa-0">
      <div class="flexible-container">
        <!-- <video autoplay muted loop id="myVidedo">
          <source src="/videos/dnf_new_world.mp4" type="video/mp4" />
          <source src="/videos/dnf_new_world.mp4" type="video/ogg" />
        </video> -->
      </div>
    </v-col>
    <v-col
      cols="12"
      md="4"
      lg="3"
      class="d-flex align-center"
      style="background-color: white; z-index: 10;"
    >
      <v-card class="pa-5 w-100" elevation="0">
        <v-alert v-if="error" type="error" class="my-3">{{error.data.error || error.data.message}}</v-alert>
        <v-img
          width="200"
          :src="host + '/images/logo.png'"
          contain
          class="mb-5 mx-auto"
          style="margin-bottom: 100px !important;"
        ></v-img>
        <div class="text-h5 mt-10">Iniciar sesión</div>
        <v-form ref="form" v-model="valid" :lazy-validation="lazy" class="mb-10 mt-5">
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

          <v-checkbox v-if="!newPassword" v-model="remember" label="Recordar contraseña?" required></v-checkbox>

          <v-btn
            min-width="100%"
            rounded
            @click="authenticate"
            color="primary"
            :loading="loading"
          >{{'Iniciar sesión'}}</v-btn>

          <div class="mt-5 font-weight-600" v-if="!newPassword">
            <a @click="dialog = true">Olvide mi contraseña</a>
          </div>
        </v-form>
      </v-card>

      <v-dialog v-model="dialog" width="500">
        <v-card>
          <v-card-title class="headline">
            Restablecer la contraseña
            <v-btn
              style="position: absolute; right: 0px; top: 0px; margin: 5px;"
              icon
              @click="dialog = false"
            >
              <v-icon size="20">mdi-close</v-icon>
            </v-btn>
          </v-card-title>

          <v-card-text>
            <p
              class="subtitle-1"
            >Ingrese la dirección de correo electrónico asociada con su cuenta, le enviaremos un correo electrónico con el enlace para restablecer su contraseña</p>
            <v-form ref="form2" v-model="valid2" :lazy-validation="lazy" class="my-5">
              <v-text-field
                v-model="email2"
                :rules="emailRules"
                label="Email"
                prepend-inner-icon="mdi-email"
                required
                solo
                outlined
              ></v-text-field>

              <v-btn
                min-width="100%"
                rounded
                class="mt-5"
                @click="sendResetPasswordEmail"
                color="primary"
                :loading="loading2"
              >{{'Enviar correo'}}</v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-col>
  </v-row>
</template>

<script>
import { mapActions } from "vuex";
import EventBus from "../event-bus";
import { mapFields } from "vuex-map-fields";
import { mapGetters } from "vuex";
import { fieldRequire, emailRequire } from "../util";

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
          this.error = { data: { error: "Las contraseñas deben ser iguales." } };
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
    this.email = this.newPassword || "";
  },
  mounted() {
    // let vid = document.getElementById("myVideo");
    // vid.volume = 0.2;
    // vid.play();
  },
};
</script>

<style scoped>
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

@media only screen and (max-width: 1366px) {
  .flexible-container video {
    max-width: 290%;
    left: 37%;
  }
}
</style>