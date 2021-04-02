<template>
  <div v-if="!fileType">
    <v-avatar size="80" color="grey">
      <v-icon v-if="!(file && file.url)" dark size="60">{{
        icon || "mdi-office-building"
      }}</v-icon>
      <img v-else :src="file.url" />
    </v-avatar>

    <v-btn small rounded color="primary" dark @click="clickFile"
      ><v-icon class="mr-1">mdi-upload</v-icon> Subir imagen</v-btn
    >

    <input type="file" :id="id" name="files" v-show="false" accept="image/*" />
  </div>
  <div v-else>
    <v-file-input
      :value="files"
      show-size
      counter
      small-chips
      multiple
      :id="id"
      label="Adjuntar archivo o imagen"
      @click:clear="clear"
    >
      <template v-slot:selection="{ text, index }">
        <v-chip small close @click:close="removeFile(index)">
          {{ text }}
        </v-chip>
      </template>
    </v-file-input>
  </div>
</template>
<script>
export default {
  props: ["image", "icon", "inputFile", "fileType", "multiple", "filesInput"],
  data: () => ({
    files: [],
    cont: 0,
    file: {},
  }),
  watch: {
    files(val) {
      this.$emit("update:files", val);
    },
    file(val) {
      this.$emit("update:image", val);
    },
    image(val) {
      this.file = this.image;
    },
  },
  computed: {
    id() {
      return this.inputFile ? this.inputFile : "files";
    },
  },
  methods: {
    clear() {
      this.$emit("remove", "all");
      this.$emit("update:files", []);
    },
    removeFile(index) {
      if (this.files[index].id) this.$emit("remove", this.files[index].id);
      this.files.splice(index, 1);
    },
    clickFile() {
      document.getElementById(this.id).click();
    },
    readFile(file, index) {
      const reader = new FileReader();
      reader.addEventListener("load", (event) => {
        if (!this.multiple && file.type && file.type.indexOf("image") !== -1) {
          this.file = { name: file.name, size: file.size };
          this.$set(this.file, "url", event.target.result);
        } else if (this.multiple) {
          // let file2 = { name: file.name, size: file.size, base64: event.target.result };
          this.$set(this.files, this.files.length, file);
        }
      });

      // reader.addEventListener("progress", event => {
      //   if (event.loaded && event.total) {
      //     const percent = (event.loaded / event.total) * 100;
      //     this.$set(this.files[index], "load", Math.round(percent));
      //   }
      // });
      reader.readAsDataURL(file);
    },
  },
  created() {
    this.file = this.image;
    this.files = this.filesInput || [];
  },
  mounted() {
    const fileSelector = document.getElementById(this.id);
    fileSelector.addEventListener("change", (event) => {
      if (!this.multiple) this.readFile(event.target.files[0]);
      else {
        // this.files = [];
        for (let i = 0; i < event.target.files.length; i++) {
          this.readFile(event.target.files[i], i);
        }
      }
    });
  },
};
</script>