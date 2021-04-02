<template>
  <v-container fluid v-if="item">
    <div>
      <v-row>
        <v-col>
          <v-form ref="form" lazy-validation>
            
            <v-row>
              <v-col cols="12" md="6">
                <FileUpload :image.sync="image" />
              </v-col>
              <v-col cols="12" md="6" class="d-md-flex justify-md-end">
                <v-checkbox v-model="item.active" label="Activa"></v-checkbox>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="4">
                <v-text-field v-model="item.name" label="Nombre" :rules="fieldRequire" required></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field v-model="item.email" label="Email" :rules="emailRequire" required></v-text-field>
              </v-col>

              <v-col cols="12" md="4">
                <v-text-field
                  v-model="item.phone"
                  label="Número telefónico"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="8">
                <v-text-field
                  v-model="item.address"
                  label="Dirección"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row v-if="!fromUser">
              <v-col cols="7" md="5">
                <UserSelect :user.sync="owner" :users="ownerSelect" />
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12">
                <UserSelectMultiple
                  :usersSelected.sync="personal"
                  :usersRemoved.sync="personalRemoved"
                  :users="personalSelect"
                  label="Personal"
                />
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" class="d-flex justify-end">
                <v-btn rounded color="primary" dark class="mr-3" @click="cancel">Cancelar</v-btn>
                <v-btn rounded color="primary" dark @click="save()" :loading="loadingItem">Guardar</v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-col>
      </v-row>
    </div>
  </v-container>
</template>

<script>
import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";
import { fieldRequire, emailRequire } from "../../util";
import UserSelect from "../util/UserSelect";
import FileUpload from "../util/FileUpload";
import UserSelectMultiple from "../util/UserSelectMultiple";

export default {
  props: ["fromUser", "close", "companyCreated"],
  components: {
    UserSelect,
    FileUpload,
    UserSelectMultiple,
  },
  data: () => ({
    emailRequire: emailRequire,
    fieldRequire: fieldRequire,
    owner: null,
    image: null,
    personal: null,
    personalRemoved: [],
  }),
  computed: {
    ...mapFields("company", ["item", "items", "loadingItem"]),
    ...mapFields("user", ["usersSelect"]),
    ownerSelect() {
      return this.usersSelect.filter(
        (item) => item.role.slug == "agency" || false
      );
    },
    personalSelect() {
      return this.usersSelect.filter(
        (item) =>
          item.role.slug != "agency" &&
          (!this.owner || item.id != this.owner.id)
      );
    },
  },
  methods: {
    ...mapActions({
      getItem: "company/getItem",
      getUsers: "generic/getItemsGeneric",
      saveAction: "company/save",
      getCompanies: "company/getItems",
    }),
    cancel() {
      if (!this.fromUser) this.$router.push({ name: "list" });
      else this.$emit("update:close", false);
    },
    save() {
      if (!this.$refs.form.validate()) return;
      
      this.item.owner = this.owner;
      if(this.personal)
      this.item.personal = this.personal.map((i) => i.id);
      this.item.personalRemoved = this.personalRemoved;
      if (this.image && this.image.id) this.item.image = { id: this.image.id };
      else if (this.image)
        this.item.image = { url: this.image.url, name: this.image.name };

      this.saveAction("company").then((response) => {
        if (!this.fromUser) {
          if (this.items.length) {
            let index = 0;
            if (this.item.id)
              this.items.filter((i, index2) => {
                if (i.id == this.item.id) {
                  index = index2;
                }
              });
            if (index) {
              this.item.staff = this.item.personal.length;
              if (this.owner) this.item.staff++;
              this.$set(this.items, index, this.item);
            } else this.getCompanies("companies");
          }

          this.$router.push({ name: "list" });
        } else {
          
          this.$emit("update:companyCreated", response.data);
          this.$emit("update:close", false);
        }
      });
    },
  },
  created() {
      this.getUsers({
        endpoint: "users-select",
      })
        .then((response) => {
          if (response.data.data.length) this.usersSelect = response.data.data;
        })
        .catch((e) => {
          console.log(e);
        });

    const id = this.$route.params.id;

    if (id && id != "new" && !this.fromUser)
      this.getItem({
        endpoint: "company",
        query: { id: decodeURIComponent(id), role: "elite" },
      }).then(() => {
        if (this.item.owner) {
          this.owner = this.item.owner;
        }
        this.personal = this.item.personal;
        this.image = this.item.image;
      });
    else {
      this.item = {};
    }
  },
  destroyed() {
    this.item = null;
  },
};
</script>