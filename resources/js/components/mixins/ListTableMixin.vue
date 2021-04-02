<script>
export default {
  data: () => ({
    options: {},
    backToList: false,
  }),
  watch: {
    options: {
      handler() {

        // const { sortBy, sortDesc, page, itemsPerPage } = this.options;
        if(this.backToList && (this.options.page != this.page || (this.perPage == this.total && this.options.itemsPerPage != -1) || this.options.itemsPerPage != this.perPage)){
          this.options.page = this.page;
          this.options.itemsPerPage = this.perPage == this.total ? -1 : this.perPage;          
          return;
        }
        else if(this.backToList){this.backToList = false; return;}

        if (this.options.itemsPerPage != this.perPage) {
          this.perPage =
            this.options.itemsPerPage == -1
              ? this.total
              : this.options.itemsPerPage;
          this.page = 1;
        } else this.page = this.options.page;
        this.getItems();
      },
      deep: true
    }
  },
  created() {
    this.backToList = this.page;
  }
};
</script>