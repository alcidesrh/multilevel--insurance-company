<template>
  <v-card max-width="100%" min-height="100%" class="d-inline-block" outlined elevation="5" style="width: 100%">
    <v-list-item three-line>
      <v-list-item-avatar size="80" color="grey">
        <v-icon v-if="!item.image" dark size="60">mdi-account</v-icon>
        <img v-else :src="item.image.url" />
      </v-list-item-avatar>

      <v-list-item-content>
        <v-row no-gutters>
          <v-col cols="12 mt-5" class="headline">
            <label class="mt-5">{{item.name}} <span v-if="role != 'agency'">({{item.number_account}})</span></label>
            <div v-if="$can('edit', item)" class="d-inline-block absolute" style="top: 0px; right: 0px">
              <v-btn icon class="float-right" @click="$emit('remove', item.id)">
                <v-icon size="20" color="red">mdi-delete</v-icon>
              </v-btn>
              <v-btn
                icon
                class="float-right"
                @click="$router.push({name: 'edit-user', params: { user: item }}, () => {})"
              >
                <v-icon size="20" color="teal">mdi-pencil</v-icon>
              </v-btn>
            </div>
          </v-col>
        </v-row>

        <v-list-item-subtitle v-if="role == 'agency'" class="mt-2 nowrap">
          Admin: {{item.fullName}} ({{item.number_account}})
        </v-list-item-subtitle>

        <v-list-item-subtitle v-if="item.email" class="mt-2 nowrap">
          <v-icon size="14">mdi-email</v-icon>
          {{item.email}}
        </v-list-item-subtitle>
        <v-list-item-subtitle v-if="item.phone" class="mt-1">
          <v-icon size="14">mdi-phone</v-icon>
          {{item.phone}}
        </v-list-item-subtitle>

        <v-list-item-title class="my-3">
          <div class="mb-2">
            Upline:
            <label v-show="item.parent">
              {{item.parent}}
              <span>({{item.parent_number}})</span>
            </label>
          </div>
          <!-- <div>Agencia: {{item.company}}</div> -->
        </v-list-item-title>
      </v-list-item-content>
    </v-list-item>

    <div style="height: 90px;" class="d-flex align-end">
      <div style="bottom: 0px; position: absolute; width: 100%;">
        <div class="px-5">
          <v-divider class="my-2"></v-divider>
        </div>
        <div
          class="d-flex justify-space-between px-5 py-2 font-weight-bold font-12"
          style="color:#757575"
        >
          <div>Comisión: $0</div>

          <div>Ventas: $0</div>

          <v-btn
            small
            color="teal"
            icon
            @click="$router.push({name: role + '_profile', params: { id: item.id, role: role }})"
          >
            <v-icon>mdi-eye</v-icon>
          </v-btn>
        </div>
      </div>
    </div>
  </v-card>
</template>

<script>
import { mapActions } from "vuex";
export default {
  props: ["item", "role"]
};
</script>
