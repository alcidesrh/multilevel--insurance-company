


<script>
import { mapActions } from "vuex";

import { mapFields } from "vuex-map-fields";

import UserSelectMultiple from "../../util/UserSelectMultiple";

import { objectToGetParameters } from "../../../util";

export default {
  components: {
    UserSelectMultiple,
  },
  data: () => ({
    loading: null,
    agentsSelected: [],
    clienName: null,
    removedFiles: [],
    headers: [
      { text: "Creado", value: "created_at" },
      { text: "Cliente", value: "name" },
      { text: "Email", value: "email" },
      { text: "Teléfono", value: "phone" },
      { text: "Adjuntos", value: "files" },
      { text: "PDF", value: "pdf" },
      { text: "", value: "action" },
    ],
  }),
  computed: {
    ...mapFields("user", ["user"]),
    ...mapFields("insurance", [
      "page",
      "items",
      "loadingList",
      "perPage",
      "pages",
      "total",
      "search",
      "item",
      "filterActive",
      "filters",
      "agents",
    ]),
  },
  watch: {
    agentsSelected() {
      this.$set(
        this.filters,
        "agents",
        this.agentsSelected.map((i) => i.id)
      );
    },
    clienName(val) {
      if (!val) {
        this.loadingList = false;
        if (this.page != 1) this.page = 1;
        else this.$set(this.filters, "name", null);
        return;
      }

      clearTimeout(this.timeout);

      // Make a new timeout set to go off in 1000ms to wait stop write
      this.timeout = setTimeout(() => {
        if (val.length < 3) {
          return;
        }

        if (this.page != 1) this.page = 1;
        else this.$set(this.filters, "name", val);
      }, 1000);
    },
    filters: {
      handler: function (val, oldVal) {
        this.page = 1;
        this.getItems();
      },
      deep: true,
    },
  },
  methods: {
    ...mapActions({
      getCountries: "generic/getTableList",
      getItemsAction: "insurance/getItems",
      getRemoveAction: "insurance/remove",
      getItem: "insurance/getItem",
      getAgents: "generic/getItemsGeneric",
    }),
    setRemovedFiles(id) {
      if (this.removedFiles == "all") return;
      if (id != "all") this.removedFiles.push(id);
      else this.removedFiles = "all";
    },

    getQuery() {
      const params = Object.keys(this.filters).length ? this.objectToGetParameters() : '';
      return `?page=${this.page}&perPage=${this.perPage}&pdf=1&` + params;
    },
    objectToGetParameters() {
      return objectToGetParameters(this.filters);
    },
    objectToFormData(obj, form, namespace) {
      let fd = form || new FormData();
      let formKey;

      for (let property in obj) {
        if (obj.hasOwnProperty(property)) {
          if (namespace) {
            formKey = namespace + "[" + property + "]";
          } else {
            formKey = property;
          }

          // if the property is an object, but not a File,
          // use recursivity.
          if (
            typeof obj[property] === "object" &&
            !(obj[property] instanceof File)
          ) {
            if (isNaN(property))
              this.objectToFormData(obj[property], fd, property);
          } else {
            // if it's a string or a File object
            fd.append(formKey, obj[property]);
          }
        }
      }

      return fd;
    },
  },
  created() {
    this.filters = {};

    this.filterActive = false;

    this.getCountries({
      query: { table: "country", fields: ["id", "name"] },
    }).then((response) => {
      this.countries = response.data.data;
    });

    if (!this.agents.length)
      this.getAgents({
        endpoint: "users-select",
      })
        .then((response) => {
          if (response.data.data.length) this.agents = response.data.data;
        })
        .catch((e) => {
          console.log(e);
        });
  },
  destroyed() {
    this.item = null;
  },
};
</script>
