<script>
import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";
import DatePicker from "../util/DatePicker";

export default {
  components: {
    DatePicker,
  },
  computed: {
    // ...mapFields("subscription", { subscriptions: "items" }),
    ...mapFields("license", { licenses: "items" }),
  },
  methods: {
    ...mapActions({
      // getCompanies: "generic/getItemsGeneric",
      getSubscription: "subscription/getTableList",
      getLicenses: "license/getTableList"
    }),

  },

  watch:{
    'filters.license': function(val){
      this.getItems();
    },
    // 'filters.subscription': function(val){
    //   this.getItems();
    // },
    'filters.subscription': function(val){
      this.getItems();
    },
    'filters.from': function(val){
      this.getItems();
    },
    'filters.to': function(val){
      this.getItems();
    },
    'filters.name'(val) {
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
    },
    'filters.email'(val) {
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
    },
    'filters.last_name'(val) {
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
    },
    'filters.phone'(val) {
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
    },
    'filters.number_account'(val) {
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

  created(){

    // if (this.subscriptions.length == 0)
    //   this.getSubscription({
    //     query: { table: "subscriptions", fields: ["id", "title"] },
    //     saveState: true,
    //   });

    if (this.licenses.length == 0)
      this.getLicenses({
        query: { table: "licenses", fields: ["id", "title"] },
        saveState: true,
      });
  }

};
</script>