<template>
  <v-menu
    v-model="menu"
    :close-on-content-click="false"
    :nudge-right="40"
    transition="scale-transition"
    offset-y
    min-width="290px"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-text-field
        clearable
        :value="computedDateFormattedMomentjs"
        :label="label"
        readonly
        v-bind="attrs"
        v-on="on"
        @click:clear="date = null"
        prepend-inner-icon="mdi-calendar-month"                
      ></v-text-field>
    </template>
    <v-date-picker ref="picker" v-model="date" @input="menu = false" :allowed-dates="allowedDates"
      :min="min || null" :max="max || null"></v-date-picker>
  </v-menu>
</template>
<script>
export default {
  props: ["dateParam", "label", "birthday", "max", "min", "allowedDates"],
  data() {
    return {
      menu: false,
      date: null
    };
  },
  computed: {
    computedDateFormattedMomentjs() {
      return this.date ? this.$options.filters.dateFormat(this.date) : "";
    }
  },
  watch: {
    menu (val) {
      if(!this.birthday)return;
        val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'))
      },
    date(val) {
      this.$emit("update:dateParam", val);
    },
    dateParam(val){
      if(!val)this.date = null;
    }
  },
  created() {
    this.date = this.dateParam || null;
  }
};
</script>
