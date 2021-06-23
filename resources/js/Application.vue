<template>
  <v-app>
    <v-main>
      <!-- Provides the application the proper gutter -->
      <v-container fluid fill-height class="d-flex justify-center align-center">
        <v-card flat max-width="1000">
          <v-row>
            <v-col cols="12" md="4" class="d-flex justify-center" style="background-color: black">
              <div class="px-3 py-5 text-center white--text">
                <img
                  class="my-5"
                  src="https://www.dnakamafaction.com/wp-content/uploads/2020/02/DNFLogoTextStrokeSmall.png"
                  alt
                  style="width: 100%;"
                />
                <h1 class="my-5">BIENVENIDO</h1>
                <div>Decisiones las tomamos todos los días. Algunas son pequeñas e insignificantes, mientras que otras impactan nuestras vidas para siempre. ¡Buena Suerte!</div>
              </div>
            </v-col>
            <v-col cols="12" md="8" class="py-0">
              <v-stepper v-if="!sent" v-model="e1">
                <v-stepper-header>
                  <v-stepper-step step="1" :complete="e1 > 1" editable>Contacto</v-stepper-step>

                  <v-divider></v-divider>

                  <v-stepper-step step="2" :complete="e1 > 2" editable>Sobre usted</v-stepper-step>

                  <v-divider></v-divider>

                  <v-stepper-step step="3" editable>Relación con DNF</v-stepper-step>
                </v-stepper-header>

                <v-stepper-items>
                  <v-stepper-content step="1">
                    <v-form ref="formStep1" v-model="validStep1" lazy-validation>
                      <v-row>
                        <v-col cols="12" md="6">
                          <v-text-field v-model="user[0]" label="Referido por" disabled></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                          <v-text-field v-model="user[1]" label="DFN número" disabled></v-text-field>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="item.first_name"
                            label="Name"
                            :rules="fieldRequire"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="item.last_name"
                            label="Appellido"
                            :rules="fieldRequire"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="item.email"
                            label="Email"
                            :rules="emailRules"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="item.phone"
                            label="Teléfono"
                            :rules="phoneRule"
                            required
                            counter="15"
                            v-on:keyup="item.phone = isNumber($event, item.phone )"
                          ></v-text-field>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12">
                          <v-text-field
                            v-model="item.address"
                            label="Dirección"
                            :rules="fieldRequire"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="item.city"
                            label="Cuidad"
                            :rules="fieldRequire"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="3">
                          <v-text-field
                            v-model="item.postal_code"
                            label="Código postal"
                            :rules="zipRule"
                            required
                            counter="7"
                            v-on:keyup="item.postal_code = isNumber($event, item.postal_code)"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-form>
                    <v-btn rounded class="float-right" color="primary" @click="step2">Siguiente</v-btn>
                  </v-stepper-content>

                  <v-stepper-content step="2">
                    <v-form ref="formStep2" v-model="validStep2" lazy-validation>
                      <v-row>
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="item.citizenship"
                            label="Nacionalidad"
                            :rules="fieldRequire"
                            required
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="item.profession"
                            label="Profesión Actual"
                            :rules="fieldRequire"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12" md="6">
                          <v-textarea
                            rows="4"
                            v-model="item.likejob"
                            outlined
                            label="3 Cosas que le gustan de su trabajo:"
                            placeholder=" "
                          ></v-textarea>
                        </v-col>
                        <v-col cols="12" md="6">
                          <v-textarea
                            rows="4"
                            v-model="item.dislikejob"
                            outlined
                            label="3 Cosas que no le gustan de su trabajo:"
                            placeholder=" "
                          ></v-textarea>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12">
                          <v-text-field
                            v-model="item.desireincome"
                            label="Si encuentra su trabajo ideal dentro de 5 años. ¿Cuál seria el ingreso anual que desea?"
                            placeholder=" "
                          ></v-text-field>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12" class="pb-0">¿Qué seria mas atractivo para usted en 5 años?</v-col>
                        <v-col cols="12" class="pt-0">
                          <v-radio-group row v-model="item.attractivestatus">
                            <v-radio label="Un Negocio Propio" value="1"></v-radio>
                            <v-radio label="Trabajo Corporativo" value="2"></v-radio>
                          </v-radio-group>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12">
                          <v-textarea
                            rows="4"
                            v-model="item.bigachievement"
                            outlined
                            label="¿Cuál ha sido el mayor logro de su vida? ¿Por qué?"
                            placeholder=" "
                            required
                          ></v-textarea>
                        </v-col>
                      </v-row>
                    </v-form>

                    <v-btn rounded class="float-right" color="primary" @click="step3">Siguiente</v-btn>

                    <v-btn class="float-right mr-2" rounded color="primary" @click="e1 = 1">Anterior</v-btn>
                  </v-stepper-content>

                  <v-stepper-content step="3">
                    <v-form ref="formStep3" v-model="validStep3" lazy-validation>
                      <v-row>
                        <v-col cols="12" class="pb-0">¿Dónde escuchó de nosotros?</v-col>
                        <v-col cols="12" class="pt-0">
                          <v-select
                            v-model="item.knowfrom"
                            :items="[
                          {text: 'Página Web', value: 'website'},
                          {text: 'Facebook', value: 'facebook'},
                          {text: 'Instagram', value: 'instagram'},
                          {text: 'LinkedIn', value: 'linkedin'},
                          {text: 'Email', value: 'email'},
                          {text: 'TV', value: 'tv'},
                          {text: 'Radio', value: 'radio'},
                          {text: 'Folleto', value: 'brochure'},
                          {text: 'Periódico', value: 'newspaper'},
                          {text: 'Amigo o Familia', value: 'friend'},
                          {text: 'Otros', value: 'other'},
                          ]"
                          ></v-select>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12" class="pb-0">¿Cuál es su mayor interés con nosotros?</v-col>
                        <v-col cols="12" class="pt-0">
                          <v-radio-group
                            row
                            v-model="item.interested_us"
                            required
                            :rules="fieldRequire"
                          >
                            <v-radio label="Carrera en Ventas" value="1"></v-radio>
                            <v-radio label="Manejo de Equipos de Trabajo" value="2"></v-radio>
                          </v-radio-group>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12">
                          <v-textarea
                            required
                            :rules="fieldRequire"
                            rows="4"
                            v-model="item.licenses"
                            outlined
                            label="¿Tiene usted alguna licencia de Seguros o Profesional?"
                            placeholder=" "
                          ></v-textarea>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12" class="pb-0">¿Cuál sería su disponibilidad?</v-col>
                        <v-col cols="12" class="pt-0">
                          <v-row>
                            <v-col class="d-flex align-center">Lunes</v-col>
                            <v-col>
                              <TimePicker label="De" :time.sync="item.monday1" />
                            </v-col>
                            <v-col>
                              <TimePicker
                                label="Hasta"
                                :min="item.monday1"
                                :time.sync="item.monday2"
                              />
                            </v-col>
                          </v-row>
                          <v-row>
                            <v-col class="d-flex align-center">Martes</v-col>
                            <v-col>
                              <TimePicker label="De" :time.sync="item.tuesday1" />
                            </v-col>
                            <v-col>
                              <TimePicker
                                label="Hasta"
                                :min="item.tuesday1"
                                :time.sync="item.tuesday2"
                              />
                            </v-col>
                          </v-row>

                          <v-row>
                            <v-col class="d-flex align-center">Miécoles</v-col>
                            <v-col>
                              <TimePicker label="De" :time.sync="item.wednesday1" />
                            </v-col>
                            <v-col>
                              <TimePicker
                                label="Hasta"
                                :min="item.wednesday1"
                                :time.sync="item.wednesday2"
                              />
                            </v-col>
                          </v-row>

                          <v-row>
                            <v-col class="d-flex align-center">Jueves</v-col>
                            <v-col>
                              <TimePicker label="De" :time.sync="item.thursday1" />
                            </v-col>
                            <v-col>
                              <TimePicker
                                label="Hasta"
                                :min="item.thursday1"
                                :time.sync="item.thursday2"
                              />
                            </v-col>
                          </v-row>

                          <v-row>
                            <v-col class="d-flex align-center">Viernes</v-col>
                            <v-col>
                              <TimePicker label="De" :time.sync="item.friday1" />
                            </v-col>
                            <v-col>
                              <TimePicker
                                label="Hasta"
                                :min="item.friday1"
                                :time.sync="item.friday2"
                              />
                            </v-col>
                          </v-row>

                          <v-row>
                            <v-col class="d-flex align-center">Sábado</v-col>
                            <v-col>
                              <TimePicker label="De" :time.sync="item.saturday1" />
                            </v-col>
                            <v-col>
                              <TimePicker
                                label="Hasta"
                                :min="item.saturday1"
                                :time.sync="item.saturday2"
                              />
                            </v-col>
                          </v-row>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12">Referencia de tres personas (Opcional)</v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12" class="pb-0">Primera Referencia</v-col>
                        <v-col cols="12" class="pt-0">
                          <v-row class="pb-0">
                            <v-col cols="12">
                              <v-text-field v-model="item.referred_one_name" label="Nombre"></v-text-field>
                            </v-col>
                          </v-row>
                          <v-row class="pt-0">
                            <v-col cols="12" md="6">
                              <v-text-field v-model="item.referred_one_email" label="Email"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                              <v-text-field v-model="item.referred_one_phone" label="Telf"></v-text-field>
                            </v-col>
                          </v-row>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12" class="pb-0">Segunda Referencia</v-col>
                        <v-col cols="12" class="pt-0">
                          <v-row class="pb-0">
                            <v-col cols="12">
                              <v-text-field v-model="item.referred_two_name" label="Nombre"></v-text-field>
                            </v-col>
                          </v-row>
                          <v-row class="pt-0">
                            <v-col cols="12" md="6">
                              <v-text-field v-model="item.referred_two_email" label="Email"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                              <v-text-field v-model="item.referred_two_phone" label="Telf"></v-text-field>
                            </v-col>
                          </v-row>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="12" class="pb-0">Tercera Referencia</v-col>
                        <v-col cols="12" class="pt-0">
                          <v-row class="pb-0">
                            <v-col cols="12">
                              <v-text-field v-model="item.referred_three_name" label="Nombre"></v-text-field>
                            </v-col>
                          </v-row>
                          <v-row class="pt-0">
                            <v-col cols="12" md="6">
                              <v-text-field v-model="item.referred_three_email" label="Email"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                              <v-text-field v-model="item.referred_three_phone" label="Telf"></v-text-field>
                            </v-col>
                          </v-row>
                        </v-col>
                      </v-row>
                    </v-form>
                    <v-btn
                      class="float-right white--text"
                      rounded
                      color="teal"
                      @click="submit"
                    >Enviar</v-btn>

                    <v-btn class="float-right mr-2" rounded color="primary" @click="e1 = 2">Anterior</v-btn>
                  </v-stepper-content>
                </v-stepper-items>
              </v-stepper>

              <v-card v-else class="pa-0 ma-0 d-flex" min-height="100%" max-width="1000">
                <v-alert outlined class="success--text mb-0" text>
                  <v-row>
                    <v-col cols="12">
                      <h2><p>Entrevista Completada.</p></h2>

                      <p>Gracias por asistir a su primera entrevista. Para nosotros es muy importante su participación en la expansión de la agencia. El 2020 es un año para personas que saben lo que quieren por tal motivo el tiempo, dinero y energía son vital para su máximo uso.</p>

                      <p>El objetivo principal con la primera entrevista es que pueda visualizar donde estará trabajando, el ambiente, y por supuesto percibiera que puede desarrollar una carrera con nosotros tanto en el área personal, profesional como empresarial.</p>

                      <h2>¡Buena Suerte!</h2>
                    </v-col>
                  </v-row>
                </v-alert>
              </v-card>
            </v-col>
          </v-row>
        </v-card>
      </v-container>
    </v-main>
    <Snackbar :text="snackbarText" color="warning" :key="snackbarText" />
  </v-app>
</template>

<script>
import axios from "axios";
import TimePicker from "./components/util/TimePicker";
import Snackbar from "./components/util/Snackbar";
import {isNumber} from "./util";
export default {
  components: {
    TimePicker,
    Snackbar
  },
  data: () => ({
    sent: false,
    user: {},
    item: { knowfrom: 1 },
    validStep1: true,
    validStep2: true,
    validStep3: true,
    loading: false,
    e1: 1,
    fieldRequire: [v => !!v || "Este campo es requerido"],
    emailRules: [
      v => !!v || "Este campo es requerido",
      v => /.+@.+\..+/.test(v) || "El email no es válido"
    ],
    snackbarText: null,
    phoneRule: [
      v => !!v || "Este campo es requerido",
      v => (v && v.length > 7) || "7 caracteres mínimo",
      v => (v && v.length <= 15) || '15 caracteres máximo'],
    zipRule: [
      v => !!v || "Este campo es requerido",
      v => (v && v.length > 4) || "5 caracteres mínimo",
      v => (v && v.length <= 7) || '7 caracteres máximo'],
  }),
  methods: {
    isNumber(event, val){
     return isNumber(event.keyCode) ? val : val.slice(0, -1);
    },
    step2() {
      if (!this.$refs.formStep1.validate()) return false;
      this.e1 = 2;
      return true;
    },
    step3() {
      if (!this.$refs.formStep2.validate()) return;
      this.e1 = 3;
    },
    submit() {
      if (!this.$refs.formStep1.validate()) {
        this.e1 = 1;
        return false;
      }

      if (!this.$refs.formStep2.validate()) {
        this.e1 = 2;
        return false;
      }

      if (this.$refs.formStep3.validate())
        axios
          .post("/application", this.item)
          .then(response => {
            if (response.data == "saved") this.sent = true;
            else this.snackbarText = response.data;
          })
          .catch(e => {
            console.log(e);
          });
    }
  },
  created() {
    alert('sdfdsf');
    if (typeof user == typeof "string") {
      this.user = Object.values(JSON.parse(user));
      this.item.user_number = this.user[1];
    }
  }
};
</script>