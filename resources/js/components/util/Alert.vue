<template>
   <v-alert color="primary" class="white--text">
      {{text}}
    </v-alert>
</template>
<script>
export default {
  props: {
    text:{
      required: true
    },
    type:{
      default: 'error'
    },
  }
};
</script>
