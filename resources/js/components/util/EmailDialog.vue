<template>
  <v-dialog v-model="dialog" width="600" persistent>
    <v-card>
      <v-card-title>
        <p>Enviar email a {{user.name}}</p>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <v-form ref="form" lazy-validation>
          <v-row>
            <v-col cols="12"></v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-text-field
                outlined
                v-model="email.subect"
                label="Asunto"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-textarea
                v-model="email.message"
                :rules="fieldRequire"
                required
                outlined
                name="input-7-4"
                label="Mensaje"
              ></v-textarea>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>

      <!-- <v-divider></v-divider> -->

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" rounded @click="$emit('close')">Cancelar</v-btn>
        <v-btn color="primary" rounded @click="send" :loading="loading">Enviar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import { fieldRequire } from "../../util";
import axios from "axios";
export default {
  props: {
    user: {
      required: true,
    },
  },
  data: () => ({
    dialog: true,
    email: {},
    fieldRequire: fieldRequire,
    loading: false,
  }),
  methods: {
    send() {
      if (!this.$refs.form.validate()) return;
      this.loading = true;
      axios
        .post("/api/send-email", {
          email: this.user.email,
          subject: this.email.subject,
          message: this.email.message,
        })
        .then((response) => {
          if(response.data == 'sent') this.$emit('close');
        }).finally(() => 
          this.loading = false);
    },
  },
};
</script>
