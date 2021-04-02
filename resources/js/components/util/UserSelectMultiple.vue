<template>
  <v-autocomplete clearable
    v-model="items"
    :items="users"
    color="blue-grey lighten-2"
    :label="label || 'Administrador'"
    item-text="name"
    item-value="id"
    return-object
    :filter="customFilter"
    multiple
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
        @click:close="remove(data.item)"
      >
        <v-avatar left>
          <v-icon v-if="!data.item.image" dark size="24">mdi-account</v-icon>
          <img v-else :src="data.item.image.url" />
        </v-avatar>
        {{ data.item.name }}{{ data.item.id }}
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
  props: ["usersSelected", "users", "label", "usersRemoved"],
  data() {
    return {
      items: null,
      removed: [],
      search: null
    };
  },
  watch: {
    items(val) {
      this.search = null;
      this.$emit("update:usersSelected", val);
      this.$emit("update:usersRemoved", this.removed);
    },
    usersSelected(val) {
      this.items = this.usersSelected;
    }
  },
  methods: {
    remove(item) {
      let index;
      for (let i = 0; i < this.items.length; i++) {
        if (this.items[i].id == item.id) {
          index = i;
          this.removed.push(item.id);
          break;
        }
      }
      if (index >= 0) this.items.splice(index, 1);
    },
    customFilter(item, queryText, itemText) {
      const textOne = item.name.toLowerCase();
      const textTwo = item.email.toLowerCase();
      const searchText = queryText.toLowerCase();

      return (
        textOne.indexOf(searchText) > -1 || textTwo.indexOf(searchText) > -1
      );
    }
  },
  created() {
    this.items = this.usersSelected;
  }
};
</script>
