<template>
  <v-autocomplete
    clearable
    v-model="item"
    :items="users"
    color="blue-grey lighten-2"
    :label="label || 'Administrador'"
    item-text="name"
    item-value="id"
    return-object
    :filter="customFilter"
    :search-input.sync="search"
    
  >
    <template v-slot:no-data>
      <v-list-item>
        <v-list-item-title>Escribe nombre o correo del usuario</v-list-item-title>
      </v-list-item>
    </template>
    <template v-slot:selection="data">
      <v-chip
        v-bind="data.attrs"
        :input-value="data.selected"
        close
        @click="data.select"
        @click:close="item = null"
      >
        <v-avatar left>
          <v-icon v-if="!data.item.image" dark size="24">mdi-account</v-icon>
          <img v-else :src="data.item.image.url" />
        </v-avatar>
        {{ data.item.name }}
      </v-chip>
    </template>
    <template v-slot:item="data">
      <template v-if="typeof data.item !== 'object'">
        <v-list-item-content v-text="data.item"></v-list-item-content>
      </template>
      <template v-else>
        <v-list-item-avatar>
          <v-icon color="grey" v-if="!data.item.image" dark size="40">mdi-account</v-icon>
          <img v-else :src="data.item.image.url" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title v-html="data.item.name"></v-list-item-title>
        </v-list-item-content>
      </template>
    </template>
  </v-autocomplete>
</template>
<script>
import { mapActions } from "vuex";
export default {
  props: ["user", "users", "label"],
  data() {
    return {
      item: null,
      search: null
    };
  },
  watch: {
    item(val) {
      this.search = null;
      this.$emit("update:user", val);
    },
    user(val) {
      this.item = this.user;
    }
  },
  methods: {
    customFilter(item, queryText, itemText) {
      const textOne = item.name.toLowerCase();
      const textTwo = item.email.toLowerCase();
      const searchText = queryText.toLowerCase();

      return (
        textOne.indexOf(searchText) > -1 || textTwo.indexOf(searchText) > -1
      );
    }
  },
  created(){
    this.item = this.user;
  }
};
</script>
