<script>
export default { 
  watch: {
    page() {
      this.getItems();
    }
  },
  created() {
    if (!this.page) this.page = 1;
  }
};
</script>