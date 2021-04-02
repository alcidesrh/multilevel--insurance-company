<template>
  <v-dialog ref="dialog" v-model="modal" :return-value.sync="value" persistent width="290px">
    <template v-slot:activator="{ on, attrs }">
      <v-text-field
        clearable
        v-model="computedTimeFormattedMomentjs"
        :label="label"
        prepend-icon="mdi-clock-outline"
        readonly
        v-bind="attrs"
        v-on="on"
      ></v-text-field>
    </template>
    <v-time-picker :min="min" v-if="modal" v-model="value" full-width>
      <v-spacer></v-spacer>
      <v-btn text color="primary" @click="modal = false">Cancelar</v-btn>
      <v-btn text color="primary" @click="$refs.dialog.save(value)">OK</v-btn>
    </v-time-picker>
  </v-dialog>
</template>
<script>
import moment from "moment";
export default {
  props: ["time", "min", "label"],
  data() {
    return {
      value: null,
      modal: false
    };
  },
  watch: {
    value(val) {
      this.$emit("update:time", val);
    }
  },
  computed: {
    computedTimeFormattedMomentjs:{
      get(){
        return this.value ? moment(this.value, 'hh:mm').format('hh:mm A') : null;
      },
      set(val){
        this.value = val;
        return val
      }
      
    }
  },
  created(){
    this.value = this.time || null;
  }
};
</script>
