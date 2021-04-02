<template>
   <v-snackbar :color="color ? color : 'primary'" top
      v-model="snackbar"
    >
      <div v-text="text" class="font-weight-bold subtitle-1"></div>
    </v-snackbar>
</template>
<script>
export default {
  props: {
    text:{
      required: true
    },
    color:{
      default: 'success'
    }
  },
  data() {
    return {
      snackbar: false,
      date: null
    };
  },
  created(){
     if(this.text)this.snackbar = true;
  },
};
</script>
