<template>
  <v-container fluid v-if="item">
    <v-row>
      <v-col cols="12" md="5" lg="3">
        <v-row>
          <v-col cols="12" class="py-0">
            <DatePicker label="Primer Entrenamiento" :date-param.sync="item.first_training_date" />
          </v-col>
          <v-col cols="12" class="d-flex py-0">
            <v-checkbox v-model="item.first_training_assisted" label="Atendido" class="ma-0"></v-checkbox>
            <!-- <v-checkbox v-model="item.oxygen" class="ma-0" label="Oxígeno"></v-checkbox> -->
          </v-col>
        </v-row>
      </v-col>

      <v-col cols="12" md="3" lg="2" class="d-flex align-center">
        <!-- <v-row> -->
        <!-- <v-col cols="12" class="py-0">
            <DatePicker label="Segunda Entrevista" :date-param.sync="item.second_interview_date" />
        </v-col>-->
        <!-- <v-col cols="12" class="d-flex py-0"> -->
        <!-- <v-checkbox v-model="item.second_interview_assisted" label="Atendida" class="ma-0"></v-checkbox> -->
        <v-checkbox v-model="item.hired" @change="hiredChange" class="ma-0" label="Contratado"></v-checkbox>
        <!-- </v-col> -->
        <!-- </v-row> -->
      </v-col>

      <!-- <v-col cols="12" md="2">
        <v-radio-group v-model="item.salary" label="Salario">
          <v-radio label="$ 1000" :value="1000"></v-radio>
          <v-radio label="$ 2000" :value="2000"></v-radio>
        </v-radio-group>
      </v-col>

      <v-col cols="12" md="5" lg="4">
        <v-row>
          <v-col cols="12" class="py-0">
            <DatePicker label="Segundo Entrenamiento" :date-param.sync="item.second_training_date" />
          </v-col>
          <v-col cols="12" class="d-flex py-0">
            <v-checkbox v-model="item.second_training_assisted" label="Atendido" class="ma-0"></v-checkbox>
          </v-col>
        </v-row>
      </v-col>-->
    </v-row>

    <v-row>
      <v-col cols="12" class="d-flex justify-end">
        <v-btn
          rounded
          color="primary"
          dark
          class="mr-3"
          @click="$router.push({ name: 'recruitment' })"
        >Cancelar</v-btn>
        <v-btn
          rounded
          color="primary"
          dark
          @click="save('recruitment')"
          :loading="loadingItem"
        >Guardar</v-btn>
      </v-col>
    </v-row>

    <v-row>
      <v-col>
        <v-btn rounded @click="showMore = !showMore" color="green" dark min-width="100%">
          <span v-if="!showMore">Mostrar mas</span>
          <span v-else>Mostrar menos</span>
        </v-btn>
      </v-col>
    </v-row>

    <div v-if="showMore">
      <v-row>
        <v-col>
          <v-form ref="form" lazy-validation>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field v-model="item.referrer" label="Referido por" disabled></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field v-model="item.number" label="Número de referencia" disabled></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="item.first_name"
                  label="Nombre"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="item.last_name"
                  label="Apellido"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field v-model="item.email" label="Email" :rules="emailRequire" required></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="item.phone"
                  label="Númerico telefónico"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="8">
                <v-text-field
                  v-model="item.address"
                  label="Dirección"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="item.citizenship"
                  label="Ciudadanía"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field v-model="item.city" label="Ciudad" :rules="fieldRequire" required></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="item.zip_code"
                  label="Código postal"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="item.profession"
                  label="Profesión"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="item.likejob"
                  label="Trabajo preferido"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="item.dislikejob"
                  label="Trabajo menospreciado"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="item.desireincome"
                  label="Ingresos deseables"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="8">
                <v-radio-group row v-model="item.attractivestatus">
                  <v-radio label="Dueño(a) de negocio" value="1"></v-radio>
                  <v-radio label="Empleado(a)" value="2"></v-radio>
                </v-radio-group>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12">
                <v-textarea
                  rows="4"
                  v-model="item.bigachievement"
                  outlined
                  label="Logro más grande"
                  placeholder=" "
                  required
                ></v-textarea>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="3">
                <v-select
                  label="¿Dónde escuchó de nosotros?"
                  v-model="item.knowfrom"
                  :items="[
                          {text: 'Web', value: 'website'},
                          {text: 'Facebook', value: 'facebook'},
                          {text: 'Instagram', value: 'instagram'},
                          {text: 'LinkedIn', value: 'linkedin'},
                          {text: 'Email', value: 'email'},
                          {text: 'TV', value: 'tv'},
                          {text: 'Radio', value: 'radio'},
                          {text: 'Folleto', value: 'brochure'},
                          {text: 'Periódico', value: 'newspaper'},
                          {text: 'Amigos o familia', value: 'friend'},
                          {text: 'Otros', value: 'other'},
                          ]"
                ></v-select>
              </v-col>
              <v-col cols="12" md="9">
                <v-radio-group
                  label="¿Cuál es su mayor interés con nosotros?"
                  :row="$vuetify.breakpoint.lgOnly"
                  v-model="item.interested_us"
                  required
                  :rules="fieldRequire"
                >
                  <v-radio label="Ventas" value="1"></v-radio>
                  <v-radio label="Equipo" value="2"></v-radio>
                </v-radio-group>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" class="pb-0">Disponibilidad</v-col>
              <v-col cols="12" class="pt-0">
                <v-row>
                  <v-col class="d-flex align-center">Lunes</v-col>
                  <v-col>
                    <TimePicker label="Desde" :time.sync="item.monday1" />
                  </v-col>
                  <v-col>
                    <TimePicker label="Hasta" :min="item.monday1" :time.sync="item.monday2" />
                  </v-col>
                </v-row>
                <v-row>
                  <v-col class="d-flex align-center">Martes</v-col>
                  <v-col>
                    <TimePicker label="Desde" :time.sync="item.tuesday1" />
                  </v-col>
                  <v-col>
                    <TimePicker label="Hasta" :min="item.tuesday1" :time.sync="item.tuesday2" />
                  </v-col>
                </v-row>

                <v-row>
                  <v-col class="d-flex align-center">Miércoles</v-col>
                  <v-col>
                    <TimePicker label="Desde" :time.sync="item.wednesday1" />
                  </v-col>
                  <v-col>
                    <TimePicker label="Hasta" :min="item.wednesday1" :time.sync="item.wednesday2" />
                  </v-col>
                </v-row>

                <v-row>
                  <v-col class="d-flex align-center">Jueves</v-col>
                  <v-col>
                    <TimePicker label="Desde" :time.sync="item.thursday1" />
                  </v-col>
                  <v-col>
                    <TimePicker
                      label="Hasta"
                      :min="item.thursday1 | timeFormat"
                      :time.sync="item.thursday2"
                    />
                  </v-col>
                </v-row>

                <v-row>
                  <v-col class="d-flex align-center">Viernes</v-col>
                  <v-col>
                    <TimePicker label="Desde" :time.sync="item.friday1" />
                  </v-col>
                  <v-col>
                    <TimePicker label="Hasta" :min="item.friday1" :time.sync="item.friday2" />
                  </v-col>
                </v-row>

                <v-row>
                  <v-col class="d-flex align-center">Sábado</v-col>
                  <v-col>
                    <TimePicker label="Desde" :time.sync="item.saturday1" />
                  </v-col>
                  <v-col>
                    <TimePicker label="Hasta" :min="item.saturday1" :time.sync="item.saturday2" />
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
          </v-form>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">Tres personas recomendadas</v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="4">
          <v-text-field v-model="item.referred_one_name" label="Nombre"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="item.referred_one_email" label="Email"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="item.referred_one_phone" label="Número telefónico"></v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="4">
          <v-text-field v-model="item.referred_two_name" label="Nombre"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="item.referred_two_email" label="Email"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="item.referred_two_phone" label="Número telefónico"></v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="4">
          <v-text-field v-model="item.referred_three_name" label="Nombre"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="item.referred_three_email" label="Email"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="item.referred_three_phone" label="Número telefónico"></v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" class="d-flex justify-end">
          <v-btn
            rounded
            color="primary"
            dark
            class="mr-3"
            @click="$router.push({ name: 'recruitment' })"
          >Cancelar</v-btn>
          <v-btn
            rounded
            color="primary"
            dark
            @click="save('recruitment')"
            :loading="loadingItem"
          >Save</v-btn>
        </v-col>
      </v-row>
    </div>
    <v-dialog v-model="paymentDialog" width="600" persistent>
      <v-card>
        <v-card-text class="py-5" v-if="pay != 'yes'">
          <v-radio-group v-model="pay" >
            <v-radio label="Pagar con tarjeta" value="yes"></v-radio>
            <v-radio label="Enviar link de pago" value="no"></v-radio>
          </v-radio-group>
        </v-card-text>
        <v-card-text class="py-5" v-if="pay == 'yes'">
          <v-card-title class="headline" primary-title>Pago de Membresia</v-card-title>
          <v-card-text>
            <p>Usted va a pagar la membresía del nuevo representante. Una nueva cuenta será creada en el sistema y un correo será enviado al nuevo representante para que active su cuenta.</p>
            <strong>
              Membresia:
              <span v-if="subscription">{{subscription.price | money}}</span>
            </strong>
          </v-card-text>

          <v-row>
            <v-col cols="12">
              <PaymentForm ref="payment" :nonce.sync="nonce" :loading.sync="loading" dialog="true" />
            </v-col>
          </v-row>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text color="primary" @click="item.hired = false; paymentDialog = false">Cancelar</v-btn>
          <v-btn text color="primary" @click="getNonce()" :loading="loading" v-if="pay">Confirmar & Pagar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="messageDialog" width="600" persistent>
      <v-card>
        <v-card-title class="headline" primary-title>{{header}}</v-card-title>
        <v-card-text class="my-5">
          <p style="font-size: 16px">{{message}}</p>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text color="primary" @click="messageDialog = null" :loading="loading">Ok</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="confirmDialog" width="600" persistent>
      <v-card v-if="subscription">
        <v-card-title class="headline" primary-title>Enviar correo {{item.first_name}}</v-card-title>
        <v-card-text class="my-5">
          <p
            style="font-size: 16px"
          >Se le enviará un correo a {{item.email}} para que ingrese al sistema y pague su membresía de {{subscription.price | money}}.</p>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            text
            color="primary"
            @click="item.hired = false; confirmDialog = false"
          >Cancelar</v-btn>
          <v-btn text color="primary" @click="sendCard(true)" :loading="loading">Enviar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";
import DatePicker from "../util/DatePicker";
import { fieldRequire, emailRequire } from "../../util";
import TimePicker from "../util/TimePicker";
import moment from "moment";
import EventBus from "../../event-bus";
import PaymentForm from "../util/PaymentForm";

export default {
  components: {
    DatePicker,
    TimePicker,
    PaymentForm,
  },
  data: () => ({
    emailRequire: emailRequire,
    fieldRequire: fieldRequire,
    showMore: false,
    paymentDialog: null,
    pickerDialog: null,
    paymentLoading: null,
    subscription: null,
    nonce: null,
    loading: null,
    message: null,
    header: null,
    messageDialog: null,
    confirmDialog: null,
    pay: null
  }),
  filters: {
    timeFormat(val) {
      return val ? moment(val, "hh:mm A").format("hh:mm") : null;
    },
  },
  computed: {
    ...mapFields("recruitment", ["item", "items", "loadingItem"]),
    computedDateFormattedMomentjs() {
      return this.date ? this.$options.filters.dateFormat(this.date) : "";
    },
  },
  watch: {
    paymentDialog(val){
     if(val){
       this.pay = null;
     }
    },
    pay(val){
      if(val == 'no'){
        this.paymentDialog = false; 
        this.confirmDialog = true
      }
    },
    pickerDialog(val) {
      val && setTimeout(() => (this.$refs.picker.activePicker = "YEAR"));
    },
    nonce() {
      this.item.nonce = this.nonce;
      this.sendCard();
    },
  },
  methods: {
    ...mapActions({
      getItem: "recruitment/getItem",
      saveAction: "recruitment/save",
      getSubscription: "generic/getItemGenericTable",
    }),
    hiredChange() {
      if (this.item.hired) {
        this.paymentDialog = true;
        if (!this.subscription)
          this.getSubscription({
            fields: ["price"],
            table: "subscriptions",
            where: { active: 1, type: "default", role_id: 4 },
          }).then((response) => (this.subscription = response));
      }
    },
    getNonce() {
      this.loading = true;
      this.$refs.payment.onGetCardNonce();
    },
    sendCard(noPaid = false) {
      this.loading = true;
      this.saveAction({
        endpoint: "recruitment",
        data: {
          nonce: this.nonce,
          recruitment: this.item.id,
          payment: true,
          noPaid: noPaid,
        },
      })
        .then((response) => {
          if (response.data.error)
            EventBus.$emit("alert", {
              text: response.data.error,
              color: "warning",
            });
          else {
            this.paymentDialog = this.confirmDialog = false;
            this.message = response.data;
            this.header = noPaid ? "Correo enviado" : "El pago se ha realizado";
            this.messageDialog = true;
            this.save();
          }
        })
        .finally(() => (this.loading = false));
    },
    save(userPay = false) {
      this.loading = true;
      this.saveAction("recruitment")
        .then((item) => {
          let index = 0;
          this.items.filter((i, index2) => {
            if (i.id == item.data.data.id) {
              index = index2;
            }
          });
          this.$set(this.items, index, item.data.data);
        })
        .finally(() => (this.loading = false));
    },
  },
  created() {
    this.getItem({
      endpoint: "recruitment-item",
      query: { id: decodeURIComponent(this.$route.params.id) },
    });
  },
  destroyed() {
    this.item = null;
  },
};
</script>