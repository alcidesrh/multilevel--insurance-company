<template>
  <v-row>
    <v-col>
      <v-row>

        <v-col cols="12" md="6" class="d-flex justify-end" order-md="2" v-if="$can('create', 'agency')">
          <v-btn rounded color="primary" @click="$router.push({name: 'new'})">
            <v-icon size="20" class="mr-1">mdi-plus</v-icon>Crear Agencia
          </v-btn>
        </v-col>
        <v-col cols="12" md="6" v-if="pages" order-md="1">
          <v-text-field
            style="max-width: 300px"
            v-model="search "
            label="Buscar"
            prepend-inner-icon="mdi-magnify"
            clearable
            :loading="loadingList"
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row v-if="items.length">
        <v-col cols="12" md="6" lg="4" class="pa-1" v-for="(item, index) in items" :key="index">
          <v-card class="d-inline-block" width="100%" height="100%" outlined elevation="5">
            <v-list-item three-line>
              <v-list-item-content>
                <div class="mb-4">
                  <v-btn
                    icon
                    @click="$router.push({name: 'show', params: { id: item.id}})"
                  >
                    <v-icon size="20" color="orange">mdi-eye</v-icon>
                  </v-btn>
                  <v-btn
                    icon
                    @click="$router.push({name: 'profile', params: { id: item.id }})"
                  >
                    <v-icon size="20" color="teal">mdi-pencil</v-icon>
                  </v-btn>

                  <v-btn v-if="$can('delete', 'agency')" icon @click="remove(item.id)">
                    <v-icon size="20" color="red">mdi-delete</v-icon>
                  </v-btn>
                </div>
                <div class="headline mb-1">{{item.name}} <span v-if="item.code">({{item.code}})</span></div>

                <v-list-item-subtitle v-if="item.email" class="mt-2 nowrap">
                  <v-icon size="14">mdi-email</v-icon>
                  {{item.email}}
                </v-list-item-subtitle>
                <v-list-item-subtitle v-if="item.phone" class="mt-1 nowrap">
                  <v-icon size="14">mdi-phone</v-icon>
                  {{item.phone}}
                </v-list-item-subtitle>
                <v-list-item-subtitle v-if="item.address" class="mt-1">
                  <v-icon size="14">mdi-map-marker</v-icon>
                  {{item.address}}
                </v-list-item-subtitle>
              </v-list-item-content>

              <v-list-item-avatar size="80" color="grey">
                <v-icon v-if="!item.image" dark size="60">mdi-office-building</v-icon>
                <img v-else :src="item.image.url" />
              </v-list-item-avatar>
            </v-list-item>

            <div style="height: 90px;" class="d-flex align-end">
              <div style="bottom: 0px; position: absolute; width: 100%;">
                <div
                  class="d-flex justify-space-between px-5 mt-5 font-weight-bold font-12"
                  style="color:#757575"
                >
                  <div>
                    <div class="text-center">0</div>
                    <div class="text-center">Clientes</div>
                  </div>

                  <div>
                    <div class="text-center">0</div>
                    <div class="text-center">Póliza</div>
                  </div>

                  <div>
                    <div class="text-center">{{item.staff}}</div>
                    <div class="text-center">Personal</div>
                  </div>
                </div>

                <div class="px-5">
                  <v-divider class="my-2"></v-divider>
                </div>

                <v-card-actions class="d-flex px-5">
                  <div>Admin: {{item.owner ? item.owner.name : 'unknow'}}</div>
                  <v-spacer></v-spacer>
                  <div class="float-right" style="color:#757575">
                    <v-icon size="20">mdi-cash-multiple</v-icon>$0
                  </div>
                </v-card-actions>
              </div>
            </div>
          </v-card>
        </v-col>
      </v-row>
      <v-row v-else>
        <v-col cols="12">
          <Alert text="No se encontraron agencia" />
        </v-col>
      </v-row>

      <v-pagination
        class="mt-5"
        v-show="!loadingList && pages"
        v-model="page"
        :length="pages"
        :total-visible="pages - perPage > 5 ? 5 : pages"
      ></v-pagination>
    </v-col>
  </v-row>
</template>

<script>
import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";
import Alert from "../util/Alert";
import EventBus from "../../event-bus";
import SearchMixin from "../mixins/SearchMixin";
import ListMixin from "../mixins/ListMixin";

export default {
  mixins: [SearchMixin, ListMixin],
  components: { Alert },
  computed: {
    ...mapFields("company", [
      "page",
      "items",
      "loadingList",
      "perPage",
      "pages",
      "search"
    ])
  },
  methods: {
    ...mapActions({
      getItemsAction: "company/getItems",
      getRemoveAction: "company/remove"
    }),
    getItems() {
      this.getItemsAction("companies");
    },
    remove(id) {
      if (window.confirm("Seguro desea eliminar esta agencia."))
        this.getRemoveAction(`company/${id}`).then(response => {
          if (response.data == "deleted") {
            EventBus.$emit("alert", {
              text: "Se ha eliminado la agencia",
              color: "success"
            });
            this.getItems();
          }
        });
    }
  },
  
};
</script>
