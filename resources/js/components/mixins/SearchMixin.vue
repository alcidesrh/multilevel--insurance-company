<script>
export default {
  watch: {
    search(val) {
      if (!val) {
        this.loadingList = false;
        if(this.page != 1) this.page = 1;
        else this.getItems();
        return;
      }

      clearTimeout(this.timeout);

      // Make a new timeout set to go off in 1000ms to wait stop write
      this.timeout = setTimeout(() => {
        if (val.length < 3) {
          return;
        }

        if(this.page != 1) this.page = 1;
        else this.getItems();

      }, 1000);
    }
  },
};
</script>