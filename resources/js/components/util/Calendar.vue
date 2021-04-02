<template>
  <div>
    <v-row v-if="is(user, 'admin')">
      <v-col class="headline">Reservación de computadoras</v-col>
    </v-row>
    <v-form ref="form" lazy-validation v-else>
      <v-row>
        <v-col class="headline">Reservar computadora</v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="4">
          <DatePicker
            label="Día"
            :date-param.sync="date"
            :min="today"
            :allowed-dates="allowedDates"
          />
        </v-col>
        <v-col cols="12" md="4">
          <v-select
            :disabled="!date"
            :items="hours"
            label="Hora"
            v-model="time"
            :rules="fieldRequire"
            required
          >
          </v-select>
        </v-col>
        <v-col cols="12" md="4">
          <v-select
            :rules="fieldRequire"
            required
            :disabled="!time"
            v-model="computer"
            :items="computers"
            label="Selecciona computadora"
          >
            <template v-slot:no-data>
              <div v-if="!date" class="pa-2">Escoja un día</div>
              <div v-else class="pa-2">No hay disponibilidad</div>
            </template>
          </v-select>
        </v-col>
      </v-row>
      <v-row>
        <v-col class="d-flex justify-end">
          <v-btn rounded color="primary" dark @click="save()" :loading="loading"
            >Guardar</v-btn
          >
        </v-col>
      </v-row>
    </v-form>
    <v-row>
      <v-col>
        <v-data-table :headers="headers" :items="turns">
          <template v-slot:item.user="{ item }">
            <div class="py-2">
              <v-avatar color="grey" size="50">
              <v-icon v-if="!item.user.image" size="50">mdi-account</v-icon>
              <img v-else :src="item.user.image.url" />
            </v-avatar>
            </div>
          </template>
          <template v-slot:item.day="{ item }">
            {{ item.day | dateFormat }}
          </template>
          <template v-slot:item.turn="{ item }">
            {{ formatTurn(item) }}
          </template>
          <template v-slot:item.computer="{ item }">
            Computadora {{ item.computer }}
          </template>
          <template v-slot:item.action="{ item }">
            <div style="white-space: nowrap">
              <v-btn icon @click="remove(item.id)">
                <v-icon size="20" color="red">mdi-delete</v-icon>
              </v-btn>
            </div>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { mapFields } from "vuex-map-fields";
import moment from "moment";
import DatePicker from "../util/DatePicker";
import axios from "axios";
import EventBus from "../../event-bus";
import { fieldRequire } from "../../util";

export default {
  components: { DatePicker },
  data: () => ({
    fieldRequire: fieldRequire,
    computer: null,
    date: null,
    time: null,
    loading: false,
    headers: [
      { text: "", value: "user", sortable: false },
      { text: "Nombre", value: "user.name", sortable: false },
      { text: "Rol", value: "user.role.name", sortable: false },
      { text: "Día", value: "day", sortable: false },
      { text: "Horario", value: "turn", sortable: false },
      { text: "Computadora", value: "computer", sortable: false },
      { text: "", value: "action", sortable: false },
    ],
    turns: [],
    bussy: [],
  }),
  computed: {
    ...mapFields("user", ["user"]),
    computers() {
      if (!this.time) return [];

      let computers = [];
      for (let i = 1; i < 7; i++) {
        if (this.bussy.length && this.bussy.includes(i)) continue;
        computers.push({ text: `Computadora ${i}`, value: i });
      }
      return computers;
    },
    today() {
      return new Date().toISOString();
    },
    hours() {
      if (!this.date) return [];

      const day = moment(this.date).format("d");

      let sections = []; //, turns = this.turns.filter(i => moment(this.date).format('dmy') == moment(i.day).format('dmy') && i.computer == this.computer);

      if (day == 1 || day == 6 || day == 7) {
        for (let i = 0, j = 2; i < 3; i++, j += 2) {
          // if(turns.length && turns[0].turn == i + 1)continue;
          sections.push({
            text: `0${j}:00 pm a 0${j + 2}:00 pm`,
            value: i + 1,
          });
        }
      } else {
        for (let i = 0, j = 10; i < 5; i++, j += 2) {
          // if(turns.length && turns[0].turn == i + 1)continue;
          if (j < 12 && j > 8)
            sections.push({
              text: `${j}:00 am a ${j + 2}:00 pm`,
              value: i + 1,
            });
          else if (j == 12) {
            sections.push({ text: `12:00 pm a 02:00 pm`, value: i + 1 });
            j = 0;
          } else
            sections.push({
              text: `0${j}:00 pm a 0${j + 2}:00 pm`,
              value: i + 1,
            });
        }
      }

      return sections;
    },
  },
  watch: {
    time() {
      if (!this.time) return;
      axios
        .post("/api/computer/avialable", { day: this.date, turn: this.time })
        .then((response) => {
          this.bussy = response.data;
        })
        .catch((e) => {
          EventBus.$emit("alert", {
            text: e.response.data.error,
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
    date() {
      this.time = null;
    },
  },
  methods: {
    remove(id) {
      this.loading = true;
      axios
        .delete("/api/computer/" + id)
        .then((response) => {
          this.turns = response.data;
        })
        .catch((e) => {
          EventBus.$emit("alert", {
            text: e.response.data.error,
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
    formatTurn(item) {
      const day = moment(item.day).format("d");

      if (day == 1 || day == 6 || day == 7) {
        for (let i = 0, j = 2; i < 3; i++, j += 2) {
          if (item.turn == i + 1) return `0${j}:00 pm a 0${j + 2}:00 pm`;
        }
      } else {
        for (let i = 0, j = 10; i < 5; i++, j += 2) {
          if (j < 12 && j > 8) {
            if (item.turn == i + 1) return `${j}:00 am a ${j + 2}:00 pm`;
          } else if (j == 12) {
            if (item.turn == i + 1) return `12:00 pm a 02:00 pm`;
            j = 0;
          } else if (item.turn == i + 1) return `0${j}:00 pm a 0${j + 2}:00 pm`;
        }
      }
    },
    allowedDates(val) {
      return moment(val).format("d") != 0;
    },
    allowedHours(val) {
      return val % 2;
    },
    save() {
      if (!this.$refs.form.validate()) return;

      this.loading = true;
      axios
        .post("/api/computer", {
          day: this.date,
          computer: this.computer,
          turn: this.time,
        })
        .then((response) => {
          this.$refs.form.reset();
          this.date = this.time = this.computer = null;
          this.turns = response.data.data;
        })
        .catch((e) => {
          EventBus.$emit("alert", {
            text: e.response.data.error,
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
  created() {
    this.loading = true;
    axios
      .get("/api/computer")
      .then((response) => {console.log(response.data.data);
        this.turns = response.data.data;
      })
      .catch((e) => {
        EventBus.$emit("alert", {
          text: e.response.data.error,
        });
      })
      .finally(() => {
        this.loading = false;
      });
  },
};
</script>