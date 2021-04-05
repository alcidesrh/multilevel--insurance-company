<template>
  <div>
    <v-navigation-drawer
      v-model="drawer"
      app
      clipped
      :mini-variant.sync="mini"
      :permanent="$vuetify.breakpoint.mdAndUp"
    >
      <v-container class="mb-3" v-if="!mini && $vuetify.breakpoint.mdAndUp">
        <v-icon @click.stop="mini = !mini" class="rotate180 float-right"
          >mdi-exit-to-app</v-icon
        >
      </v-container>
      <v-container class="mb-3" v-if="$vuetify.breakpoint.smAndDown">
        <v-icon @click.stop="drawer = !drawer" class="rotate180 float-right"
          >mdi-exit-to-app</v-icon
        >
      </v-container>

      <v-list dense :disabled="user.renew || user.userPay">
        <!-- <v-list-item
          v-if="$can('list', 'agencies')"
          link
          @click="menuClick('agencies')"
          id="agencies"
        >
          <v-list-item-action>
            <v-icon>mdi-office-building</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Agencias</div>
          </v-list-item-content>
        </v-list-item> -->

        <v-list-item
          v-if="$can('list', 'agency')"
          link
          @click="menuClick('agency')"
          id="agency"
        >
          <v-list-item-action>
            <v-icon>mdi-account-tie</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div v-if="is(user, 'admin')">Agencias administrador</div>
            <div v-else>Agencias</div>
          </v-list-item-content>
        </v-list-item>

        <v-list-item
          v-if="$can('list', 'elite')"
          link
          @click="menuClick('elite')"
          id="elite"
        >
          <v-list-item-action>
            <v-icon>mdi-account-tie-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Representantes élites</div>
          </v-list-item-content>
        </v-list-item>

        <v-list-item
          v-if="$can('list', 'broker')"
          link
          @click="menuClick('broker')"
          id="broker"
        >
          <v-list-item-action>
            <v-icon>mdi-account-star-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Representantes</div>
          </v-list-item-content>
        </v-list-item>

        <v-list-item
          v-if="$can('list', 'staff')"
          link
          @click="menuClick('staff')"
          id="staff"
        >
          <v-list-item-action>
            <v-icon>mdi-account-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Staff</div>
          </v-list-item-content>
        </v-list-item>

        <v-list-item
          v-if="$can('list', 'recruitment')"
          link
          @click="menuClick('recruitment')"
          id="recruitment"
        >
          <v-list-item-action>
            <v-icon>mdi-account-plus-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Reclutamiento</div>
          </v-list-item-content>
        </v-list-item>

        <v-list-item
          v-if="!is(user, 'admin') && $can('list', 'computer')"
          link
          @click="menuClick('calendar')"
          id="calendar"
        >
          <v-list-item-action>
            <v-icon>mdi-laptop</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Reservar computadora</div>
          </v-list-item-content>
        </v-list-item>

        <!-- <v-list-item link @click="menuClick('comming-soon')" id="comming-soon">
          <v-list-item-action>
            <v-icon>mdi-account-multiple</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Clientes</div>
          </v-list-item-content>
        </v-list-item> -->

        <!-- <v-list-item link @click="menuClick('comming-soon')" id="comming-soon">
          <v-list-item-action>
            <v-icon>mdi-alpha-s-box-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Servicios</div>
          </v-list-item-content>
        </v-list-item> -->

        <v-list-group :value="openServices" v-if="$can('list', 'insurance')" prepend-icon="mdi-file-document-edit-outline">
          <template v-slot:activator>
            <v-list-item-content>
              Servicios
            </v-list-item-content>
          </template>
          
          <v-list-item            
            link
            @click="menuClick('insurance_obama_new')"
            id="insurance_obama_new"
            class="pl-8"
          >
            <v-list-item-action>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div >Obamacare nuevo cliente</div>
            </v-list-item-content>
          </v-list-item>

          <v-list-item            
            link
            @click="menuClick('insurance_obama_renew')"
            id="insurance_obama_renew"
            class="pl-8"
          >
            <v-list-item-action>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div >Obamacare renovación</div>
            </v-list-item-content>
          </v-list-item>

          <v-list-item            
            link
            @click="menuClick('insurance_obama_life_change')"
            id="insurance_obama_life_change"
            class="pl-8"
          >
            <v-list-item-action>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div >Obamacare cambio de vida</div>
            </v-list-item-content>
          </v-list-item>

          <v-list-item            
            link
            @click="menuClick('insurance_live')"
            id="insurance_live"
            class="pl-8"
          >
            <v-list-item-action>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div>Vida</div>
            </v-list-item-content>
          </v-list-item>
          
          <v-list-item            
            link
            @click="menuClick('insurance_car')"
            id="insurance_car"
            class="pl-8"
          >
            <v-list-item-action>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div>Auto</div>
            </v-list-item-content>
          </v-list-item>
          
          <v-list-item            
            link
            @click="menuClick('insurance_home')"
            id="insurance_home"
            class="pl-8"
          >
            <v-list-item-action>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div>Casa</div>
            </v-list-item-content>
          </v-list-item>
          
          <v-list-item            
            link
            @click="menuClick('insurance_business')"
            id="insurance_business"
            class="pl-8"
          >
            <v-list-item-action>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div>Negocio</div>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>

        <!-- <v-list-item link @click="menuClick('comming-soon')" id="comming-soon">
          <v-list-item-action>
            <v-icon>mdi-arrow-right-circle-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>DNF Start</div>
          </v-list-item-content>
        </v-list-item> -->

        <!-- <v-list-item link @click="menuClick('comming-soon')" id="comming-soon">
          <v-list-item-action>
            <v-icon>mdi-cart-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>DNF Company Store</div>
          </v-list-item-content>
        </v-list-item> -->

        <v-list-item link @click="menuClick('comming-soon')" id="comming-soon">
          <v-list-item-action>
            <v-icon>mdi-radio-tower</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Marketing</div>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link @click="menuClick('comming-soon')" id="comming-soon">
          <v-list-item-action>
            <v-icon>mdi-spider-web</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Tracker</div>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link @click="menuClick('comming-soon')" id="comming-soon">
          <v-list-item-action>
            <v-icon>mdi-clipboard-list-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Noticias y Eventos</div>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link @click="menuClick('comming-soon')" id="comming-soon">
          <v-list-item-action>
            <v-icon>mdi-chat-processing-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Chat</div>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link @click="menuClick('comming-soon')" id="comming-soon">
          <v-list-item-action>
            <v-icon>mdi-desktop-mac-dashboard</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Scoreboard</div>
          </v-list-item-content>
        </v-list-item>

        <!-- <v-list-item
          v-if="$can('send', 'invitation')"
          link
          @click="menuClick('invitation')"
          id="invitation"
        >
          <v-list-item-action>
            <v-icon>mdi-email-send-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Enviar Invitación</div>
          </v-list-item-content>
        </v-list-item> -->
      </v-list>
    </v-navigation-drawer>

    <v-navigation-drawer
      v-if="is(user, 'admin')"
      v-model="drawerRight"
      app
      clipped
      right
      :mini-variant.sync="mini2"
    >
      <v-container class="pb-0" v-if="!mini2">
        <v-icon @click.stop="mini2 = !mini2">mdi-exit-to-app</v-icon>
        <!-- <v-spacer></v-spacer> -->
        <div class="d-inline-block float-right font-weight-600">
          <v-icon>mdi-cogs</v-icon> Configuración
        </div>
      </v-container>
      <v-list dense>
        <v-list-item @click.stop="mini2 = !mini2" v-if="mini2">
          <v-list-item-action>
            <v-icon class="rotate180 float-right">mdi-exit-to-app</v-icon>
          </v-list-item-action>
        </v-list-item>

        <v-list-item
          v-if="$can('list', 'subscription')"
          link
          @click="menuClick('subscription')"
          id="subscription"
        >
          <v-list-item-action>
            <v-icon>mdi-file-document-edit-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Subscripción</div>
          </v-list-item-content>
        </v-list-item>

        <v-list-item
          v-if="$can('list', 'license')"
          link
          @click="menuClick('license')"
          id="license"
        >
          <v-list-item-action>
            <v-icon>mdi-card-account-details-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Licencias</div>
          </v-list-item-content>
        </v-list-item>

        <!-- <v-list-item
          v-if="$can('list', 'category')"
          link
          @click="menuClick('category')"
          id="category"
        >
          <v-list-item-action>
            <v-icon>mdi-sitemap</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Categorías</div>
          </v-list-item-content>
        </v-list-item> -->

        <v-list-item
          v-if="$can('list', 'finance')"
          link
          @click="menuClick('finance')"
          id="finance"
        >
          <v-list-item-action>
            <v-icon>mdi-cash-usd-outline</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Finanzas</div>
          </v-list-item-content>
        </v-list-item>

        <v-list-item
          v-if="$can('list', 'computer')"
          link
          @click="menuClick('calendar')"
          id="calendar"
        >
          <v-list-item-action>
            <v-icon>mdi-laptop</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <div>Reservación de computadora</div>
          </v-list-item-content>
        </v-list-item>

        <v-list-group :value="!mini2" v-if="$can('list', 'insurance')" prepend-icon="mdi-file-document-edit-outline">
          <template v-slot:activator>
            <v-list-item-content>
              Servicios
            </v-list-item-content>
          </template>
          
          <v-list-item            
            link
            @click="menuClick('insurance_obama_new')"
            id="insurance_obama_new"
            class="pl-8"
          >
            <v-list-item-action>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div >Obamacare nuevo cliente</div>
            </v-list-item-content>
          </v-list-item>

          <v-list-item            
            link
            @click="menuClick('insurance_obama_renew')"
            id="insurance_obama_renew"
            class="pl-8"
          >
            <v-list-item-action>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div >Obamacare renovación</div>
            </v-list-item-content>
          </v-list-item>

          <v-list-item            
            link
            @click="menuClick('insurance_obama_life_change')"
            id="insurance_obama_life_change"
            class="pl-8"
          >
            <v-list-item-action>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div >Obamacare cambio de vida</div>
            </v-list-item-content>
          </v-list-item>

          <v-list-item            
            link
            @click="menuClick('insurance_live')"
            id="insurance_live"
            class="pl-8"
          >
            <v-list-item-action>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div>Vida</div>
            </v-list-item-content>
          </v-list-item>
          
          <v-list-item            
            link
            @click="menuClick('insurance_car')"
            id="insurance_car"
            class="pl-8"
          >
            <v-list-item-action>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div>Auto</div>
            </v-list-item-content>
          </v-list-item>
          
          <v-list-item            
            link
            @click="menuClick('insurance_home')"
            id="insurance_home"
            class="pl-8"
          >
            <v-list-item-action>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div>Casa</div>
            </v-list-item-content>
          </v-list-item>
          
          <v-list-item            
            link
            @click="menuClick('insurance_business')"
            id="insurance_business"
            class="pl-8"
          >
            <v-list-item-action>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div>Negocio</div>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>

      </v-list>
    </v-navigation-drawer>

    <v-app-bar app clipped-right clipped-left color="primary" dark>
      <v-icon v-show="!drawer" @click.stop="drawer = !drawer" class="mr-5"
        >mdi-exit-to-app</v-icon
      >

      <v-toolbar-title style="width: 256px">
        <v-img
          :src="host + '/images/logo.png'"
          width="120"
          aspect-ratio="2.5"
          contain
          class="mx-auto"
        ></v-img>
      </v-toolbar-title>

      <v-spacer></v-spacer>

      <v-avatar color="grey" size="40">
        <v-icon v-if="!user.image" dark size="40">mdi-account</v-icon>
        <img v-else :src="user.image.url" />
      </v-avatar>

      <v-menu bottom left>
        <template v-slot:activator="{ on }">
          <v-btn dark icon v-on="on">
            <v-icon>mdi-menu-down</v-icon>
          </v-btn>
        </template>

        <v-list>
          <v-list-item
            :disabled="user.renew || user.userPay"
            v-if="$can('edit', 'profile')"
            @click="
              $router.push(
                { name: 'edit-user', params: { user: user } },
                () => {}
              )
            "
          >
            <v-list-item-action>
              <v-icon>mdi-pencil</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div>Editar perfil</div>
            </v-list-item-content>
          </v-list-item>

          <v-list-item @click="logout">
            <v-list-item-action>
              <v-icon>mdi-logout</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <div>Cerrar sesión</div>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-menu>

      <v-app-bar-nav-icon
        @click.stop="drawerRight = !drawerRight"
      ></v-app-bar-nav-icon>
    </v-app-bar>
    <v-progress-linear
      v-show="globalLoading"
      height="4"
      style="position: absolute; margin-top: 64px"
      indeterminate
    ></v-progress-linear>
  </div>
</template>

<script>
import { mapFields } from "vuex-map-fields";
import { mapGetters } from "vuex";
import EventBus from "../../event-bus";

export default {
  data: () => ({
    drawer: true,
    loading: false,
    mini: false,
    mini2: true,
    drawerRight: null,
    right: false,
    openServices: false
  }),
  computed: {
    ...mapGetters({
      host: "util/host",
    }),
    ...mapFields("user", ["user"]),
    ...mapFields("generic", ["globalLoading"]),
  },
  methods: {
    logout() {
      this.loading = true;
      this.$store.dispatch("user/logout").then(() => {
        this.loading = false;
        window.location.href = "/";
      });
    },
    menuClick(path, params) {
      if (params) this.$router.push({ name: path, params }, () => {});
      else {
        this.$router.push({ name: path }, () => {});
      }
    },

    selectMenuLink(id) {
      let element = document.getElementsByClassName("section-active");

      if (element.length) {
        [].forEach.call(element, function (el) {
          el.classList.remove("section-active");
        });
      }

      element = document.getElementById(id);

      if (!element) {
        return;
      }
      element.classList.add("section-active");
    },
  },
  created() {
    this.drawer = this.$vuetify.breakpoint.mdAndUp;
    let route = JSON.parse(data);
    if(route.route == 'services') this.openServices = true;
    EventBus.$on("route-change", (payload) => {
      this.selectMenuLink(payload.menu);
    });
  },
};
</script>

<style scoped>
.rotate180 {
  transform: rotate(180deg);
}
.section-active {
  background-color: #3A76D2;
}
.section-active.theme--light.v-list-item:not(.v-list-item--active):not(.v-list-item--disabled),
.section-active .mdi {
  color: white !important;
}

.theme--light.v-list-item:not(.v-list-item--active):not(.v-list-item--disabled):hover {
  background-color: #3A76D2;
  color: white !important;
}
.theme--light.v-list-item:not(.v-list-item--active):not(.v-list-item--disabled):hover
  > div
  > i {
  color: white !important;
}
</style>