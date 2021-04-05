<template>
  <v-container fluid v-if="item">
    <v-row>
      <v-col cols="12" lg="3" class="px-0" order-lg="12">
        <v-card min-height="100%" class="mx-auto px-3" outlined elevation="5">
          <v-row class="justify-center mt-3">
            <v-avatar color="grey" size="80">
              <v-icon v-if="!item.image" dark size="80">mdi-account</v-icon>
              <img v-else :src="item.image.url" />
            </v-avatar>
          </v-row>
          <v-row class="justify-center mt-3 headline">{{item.full_name}}</v-row>
          <v-row>
            <v-col class="d-flex justify-center">
              <v-icon
                class="mr-2"
                size="15"
                :color="item.active ? 'teal' : 'error'"
              >mdi-checkbox-blank-circle</v-icon>
              {{item.active ? 'Activo' : 'Inactivo'}}
            </v-col>
          </v-row>
          <v-row>
            <v-col class="d-flex justify-center">
              <v-btn
                icon
                class="ml-2 mr-1"
                @click="user = {email: item.email, name: item.full_name}"
              >
                <v-icon color="orange">mdi-email</v-icon>
              </v-btn>
              <span v-if="$can('edit', item)">
                <v-btn icon class="mr-2 ml-1">
                  <v-icon
                    color="teal"
                    @click="$router.push({name: 'edit-user', params: { user: item }}, () => {})"
                  >mdi-pencil</v-icon>
                </v-btn>
                <v-btn icon>
                  <v-icon color="red">mdi-delete</v-icon>
                </v-btn>
              </span>
            </v-col>
          </v-row>

          <v-divider></v-divider>

          <v-row>
            <v-col class="d-flex justify-center px-0">
              <v-row>
                <v-col cols="12" class="py-0 justify-center d-flex">Venta total</v-col>
                <v-col cols="12" class="py-0 justify-center d-flex">$0</v-col>
              </v-row>
            </v-col>
            <v-col class="d-flex justify-center px-0">
              <v-row>
                <v-col cols="12" class="py-0 justify-center d-flex">Comisión total</v-col>
                <v-col cols="12" class="py-0 justify-center d-flex">$0</v-col>
              </v-row>
            </v-col>
          </v-row>

          <v-divider></v-divider>

          <v-row class="mt-3">
            <v-col cols="12">
              <v-icon class="mr-2">mdi-email</v-icon>
              {{item.email}}
            </v-col>
            <v-col cols="12" v-if="item.phone">
              <v-icon class="mr-2">mdi-phone</v-icon>
              {{item.phone}}
            </v-col>
            <v-col cols="12" v-if="item.birthday">
              <v-icon class="mr-2">mdi-calendar-month</v-icon>
              {{item.birthday}}
            </v-col>
            <v-col cols="12" v-if="item.address">
              <v-icon class="mr-2">mdi-map-marker</v-icon>
              {{item.address}}
            </v-col>
          </v-row>
        </v-card>
      </v-col>
      <v-col cols="12" lg="9" order-lg="1">
        <v-tabs v-model="tab">
          <v-tab href="#downlines">Downline</v-tab>
          <v-tab>Clientes</v-tab>
          <v-tab>Póliza</v-tab>
          <v-tab>Subscripción</v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab" v-if="item">
          <v-tab-item value="downlines">
            <v-card flat tile>
              <v-card-text>
                <div
                  v-if="item.parent && $can('list', 'all')"
                  class="mb-5 black--text cursor-pointer"
                  @click="$router.push({name: 'elite_profile', params: { id: item.id }})"
                >
                  <strong>Upline:</strong>
                  <v-avatar size="40" color="grey" class="ml-2">
                    <v-icon v-if="!item.parent.image" dark size="40">mdi-account</v-icon>
                    <img v-else :src="item.parent.image.url" width="40" />
                  </v-avatar>
                  {{item.parent.name}}
                  <v-btn
                    small
                    color="teal"
                    icon
                    @click="$router.push({name: 'elite_profile', params: { id: item.parent.id }})"
                  >
                    <v-icon>mdi-eye</v-icon>
                  </v-btn>
                </div>
                <v-treeview
                  v-if="item.children.length"
                  v-model="tree"
                  open-all
                  :items="[item]"
                  activatable
                  open-on-click
                >
                  <template v-slot:prepend="{ item }">
                    <v-avatar
                      size="40"
                      color="grey"
                      @click="$router.push({name: 'elite_profile', params: { id: item.id }})"
                    >
                      <v-icon v-if="!item.image" dark size="40">mdi-account</v-icon>
                      <img v-else :src="item.image.url" width="40" />
                    </v-avatar>
                  </template>

                  <template v-slot:label="{ item }">
                    <v-hover v-slot:default="{ hover }">
                      <div>
                        {{item.name}}
                        <v-btn
                          @click="$router.push({name: 'elite_profile', params: { id: item.id }})"
                          v-if="hover"
                          small
                          color="teal"
                          icon
                        >
                          <v-icon>mdi-eye</v-icon>
                        </v-btn>
                      </div>
                    </v-hover>
                  </template>
                </v-treeview>
                <v-alert type="info" v-else>No tiene descendientes</v-alert>
              </v-card-text>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-col>
    </v-row>
    <EmailDialog v-if="user" :user="user" v-on:close="user = null" />
  </v-container>
</template>

<script>
import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";
import EmailDialog from "../util/EmailDialog";

export default {
  components: { EmailDialog },
  data: () => ({
    tab: 0,
    tree: [],
    user: null,
  }),
  computed: {
    ...mapFields("user", ["item", "loadingItem"]),
    role() {
      return decodeURIComponent(this.$route.params.role);
    },
  },
  methods: {
    ...mapActions({
      getItem: "user/getItem",
    }),
    preview(id) {
      this.getItem({
        endpoint: "user-profile",
        query: { id: id },
      });
    },
  },
  created() {
    this.getItem({
      endpoint: "user-profile",
      query: { id: decodeURIComponent(this.$route.params.id) },
    });
  },
  destroyed() {
    this.item = null;
  },
};
</script>