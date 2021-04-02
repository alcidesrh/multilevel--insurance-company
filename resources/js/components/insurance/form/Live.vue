<template>
  <v-container fluid>
    <v-row v-if="!item">
      <v-col class="d-flex">
        <a
          v-if="items.length"
          target="_blank"
          :href="'/api/insurance/live' + getQuery()"
          style="text-decoration: none"
        >
          <v-icon color="teal" class="cursor-pointer mr-3" size="35"
            >mdi-file-pdf</v-icon
          >
        </a>
        <v-icon v-else disabled class="cursor-pointer mr-3" size="35"
          >mdi-file-pdf</v-icon
        >
      </v-col>
      <v-col class="d-flex justify-end">
        <v-btn
          class="mr-2"
          icon
          :color="!filterActive ? 'primary' : 'teal'"
          @click="filterActive = !filterActive"
        >
          <v-icon size="30">mdi-filter-menu</v-icon>
        </v-btn>
        <v-btn
          rounded
          color="primary"
          @click="
            item = {
              client: {},
            }
          "
        >
          Nuevo
        </v-btn>
      </v-col>
    </v-row>

    <v-row v-if="filterActive && !item">
      <v-col cols="12" md="6" lg="4">
        <v-text-field
          v-model="clienName"
          label="Nombre del cliente"
          prepend-inner-icon="mdi-magnify"
          clearable
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="3" lg="2">
        <DatePicker
          label="Desde"
          :date-param.sync="filters.from"
          :max="new Date().toISOString().substr(0, 10)"
        />
      </v-col>
      <v-col cols="12" md="3" lg="2">
        <DatePicker
          :min="filters.from"
          :max="new Date().toISOString().substr(0, 10)"
          label="Hasta"
          :date-param.sync="filters.to"
        />
      </v-col>

      <v-col cols="12" md="12" lg="8">
        <UserSelectMultiple
          :usersSelected.sync="agentsSelected"
          :users="agents"
          label="Agentes"
        />
      </v-col>
    </v-row>

    <v-row v-if="!item">
      <v-col cols="12">
        <v-card elevation="3">
          <v-data-table
            :headers="headers"
            :items="items"
            :server-items-length="total"
            :options.sync="options"
            :loading="loadingList"
            class="elevation-1"
            :items-per-page="perPage"
            :footer-props="{
              showFirstLastPage: true,
              itemsPerPageOptions: [10, 15, 30, 100, -1],
            }"
          >
            <template v-slot:item.created_at="{ item }">{{
              item.created_at | dateFormat
            }}</template>

            <template v-slot:item.name="{ item }">
              <span v-if="item.client">{{
                item.client.name + " " + item.client.last_name
              }}</span>
              <span v-else>----</span>
            </template>
            <template v-slot:item.email="{ item }">
              <span v-if="item.client">{{ item.client.email }}</span>
              <span v-else>----</span>
            </template>
            <template v-slot:item.phone="{ item }">
              <span v-if="item.client">{{ item.client.phone }}</span>
              <span v-else>----</span>
            </template>
            <template v-slot:item.files="{ item }">
              <div
                :style="{
                  maxHeight: item.files.length > 4 ? '100px' : 'auto',
                  overflowY: item.files.length > 4 ? 'scroll' : 'none',
                }"
              >
                <div v-for="(file, index) in item.files" :key="index">
                  <v-hover v-slot="{ hover }">
                    <div>
                      {{ file.name }}
                      <a
                        target="_blank"
                        :href="'/file/download/' + file.id"
                        style="text-decoration: none"
                      >
                        <v-icon
                          :color="hover ? 'teal' : ' '"
                          class="cursor-pointer"
                          >mdi-download</v-icon
                        >
                      </a>
                    </div>
                  </v-hover>
                </div>
              </div>
            </template>
            <template v-slot:item.pdf="{ item }">
              <div style="white-space: nowrap">
                <a
                  target="_blank"
                  :href="'/insurance/pdf/2/' + item.id + '/show'"
                  style="text-decoration: none"
                >
                  <v-icon class="cursor-pointer" color="teal">mdi-eye</v-icon>
                </a>
                <a
                  target="_blank"
                  :href="'/insurance/pdf/2/' + item.id"
                  style="text-decoration: none"
                >
                  <v-icon class="cursor-pointer" color="teal"
                    >mdi-file-pdf</v-icon
                  >
                </a>
              </div>
            </template>
            <template v-slot:item.action="{ item }">
              <div style="white-space: nowrap">
                <v-btn icon @click="edit(item.id)">
                  <v-icon size="20" color="teal">mdi-pencil</v-icon>
                </v-btn>
                <v-btn icon @click="remove(item.id)">
                  <v-icon size="20" color="red">mdi-delete</v-icon>
                </v-btn>
              </div>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>

    <v-row v-if="item">
      <v-col>
        <v-form ref="form" lazy-validation>
          <v-row>
            <v-col cols="12">
              <div class="headline">Asegurado</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="6">
              <v-text-field
                v-model="item.client.name"
                label="Nombre"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6">
              <v-text-field
                v-model="item.client.last_name"
                label="Apellido"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="4" md="3">
              <DatePicker
                label="Fecha de Nacimiento"
                birthday="true"
                :date-param.sync="item.client.birthday"
                :max="
                  new Date(
                    new Date().setFullYear(new Date().getFullYear() - 18)
                  )
                    .toISOString()
                    .substr(0, 10)
                "
              />
            </v-col>
            <v-col cols="12" sm="8" md="9">
              <v-text-field
                v-model="item.birth_place"
                label="Lugar de nacimiento / Estado-Provincia / País"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="3" lg="4">
              <v-radio-group
                v-model="item.client.sex"
                :row="$vuetify.breakpoint.lg"
                label="Sexo"
              >
                <v-radio label="Masculino" value="Masculino"></v-radio>
                <v-radio label="Femenino" value="Femenino"></v-radio>
              </v-radio-group>
            </v-col>
            <v-col cols="12" sm="9" lg="8">
              <v-text-field
                v-model="item.client.address"
                label="Dirección Actual"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="6" md="3">
              <v-text-field
                v-model="item.client.phone"
                label="Teléfono"
                prepend-inner-icon="mdi-pound"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="3">
              <v-text-field
                v-model="item.client.ssn"
                label="Seguridad social"
                prepend-inner-icon="mdi-pound"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="3">
              <v-text-field
                v-model="item.client.driver_license"
                label="Licencia de conducción"
                prepend-inner-icon="mdi-pound"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="3">
              <v-text-field
                v-model="item.client.agency"
                label="Agencia de seguro de vida"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="6" md="3">
              <v-radio-group
                v-model="item.client.citizen"
                :row="$vuetify.breakpoint.lg"
                label="Ciudadano americano"
              >
                <v-radio label="Si" value="Si"></v-radio>
                <v-radio label="No" value="No"></v-radio>
              </v-radio-group>
            </v-col>
            <v-col cols="12" sm="6" md="3">
              <v-radio-group
                v-model="item.client.permanent_resident"
                :row="$vuetify.breakpoint.lg"
                label="Residente permanente"
              >
                <v-radio label="Si" value="Si"></v-radio>
                <v-radio label="No" value="No"></v-radio>
              </v-radio-group>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="item.client.employer"
                label="Empleador y tiempo empleado"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="6" md="3">
              <v-text-field
                v-model="item.client.ocupation"
                label="Ocupación"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="3" md="2">
              <v-text-field
                v-model="item.client.income"
                label="Ingreso anual"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="3" md="2">
              <v-text-field
                v-model="item.net_worth"
                label="Net worth"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="4" md="2">
              <v-text-field
                v-model="item.household_income"
                label="Ingreso familiar"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="4" md="3">
              <v-text-field
                v-model="item.household_net_worth"
                label="Household net worth"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12">
              <v-checkbox
                v-model="other"
                label="El propietario no es el asegurado"
              ></v-checkbox>
            </v-col>
          </v-row>

          <div v-if="other">
            <v-row>
              <v-col cols="12" sm="3">
                <v-radio-group
                  v-model="item.other_citizen"
                  :row="$vuetify.breakpoint.lg"
                  label="Ciudadano americano"
                >
                  <v-radio label="Si" value="Si"></v-radio>
                  <v-radio label="No" value="No"></v-radio>
                </v-radio-group>
              </v-col>
              <v-col cols="12" sm="8">
                <v-text-field
                  v-model="item.other_owner_name"
                  label="Nombre completo"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="4" md="3">
                <DatePicker
                  label="Fecha de Nacimiento"
                  birthday="true"
                  :date-param.sync="item.other_owner_birthday"
                  :max="
                    new Date(
                      new Date().setFullYear(new Date().getFullYear() - 18)
                    )
                      .toISOString()
                      .substr(0, 10)
                  "
                />
              </v-col>
              <v-col cols="12" sm="8" md="6">
                <v-text-field
                  v-model="item.other_owner_address"
                  label="Lugar de nacimiento / Estado-Provincia / País"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="2" md="3">
                <v-radio-group
                  v-model="item.other_owner_sex"
                  :row="$vuetify.breakpoint.lg"
                  label="Sexo"
                >
                  <v-radio label="Masculino" value="Masculino"></v-radio>
                  <v-radio label="Femenino" value="Femenino"></v-radio>
                </v-radio-group>
              </v-col>
              <v-col cols="12" sm="5" md="3">
                <v-text-field
                  v-model="item.other_ssn"
                  label="Seguridad social"
                  prepend-inner-icon="mdi-pound"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="5" md="3">
                <v-text-field
                  v-model="item.other_driver_license"
                  label="Licencia de conducción"
                  prepend-inner-icon="mdi-pound"
                ></v-text-field>
              </v-col>
            </v-row>
          </div>

          <v-row>
            <v-col cols="12">
              <div class="headline">Información de beneficiario</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="6" lg="4">
              <v-select
                clearable
                v-model="beneficiariesNumber"
                :items="[0, 1, 2, 3]"
                label="Beneficiarios"
              ></v-select>
            </v-col>
          </v-row>

          <div v-for="(e, i) in beneficiariesNumber" :key="i">
            <v-row>
              <v-col>
                <b>Beneficiario {{ i + 1 }}:</b>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="6" class="d-flex align-center">
                <v-text-field
                  v-model="beneficiaries[i].name"
                  label="Name"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" class="d-flex align-center">
                <v-text-field
                  v-model="beneficiaries[i].relationship"
                  label="Relación"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="4" class="d-flex align-center">
                <v-text-field
                  v-model="beneficiaries[i].dob"
                  label="DOB"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="4" class="d-flex align-center">
                <v-text-field
                  v-model="beneficiaries[i].ssn"
                  label="SSN"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="4" class="d-flex align-center">
                <v-text-field
                  v-model="beneficiaries[i].percentage"
                  label="Porciento"
                ></v-text-field>
              </v-col>
            </v-row>
          </div>

          <v-row>
            <v-col cols="12" sm="6" lg="4">
              <v-select
                clearable
                v-model="beneficiariesNumber2"
                :items="[0, 1, 2, 3]"
                label="Beneficiarios contigente"
              ></v-select>
            </v-col>
          </v-row>

          <div v-for="(e, i) in beneficiariesNumber2" :key="i + 3">
            <v-row>
              <v-col>
                <b>Beneficiario contigente {{ i + 1 }}:</b>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="6" class="d-flex align-center">
                <v-text-field
                  v-model="beneficiaries2[i].name"
                  label="Name"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" class="d-flex align-center">
                <v-text-field
                  v-model="beneficiaries2[i].relationship"
                  label="Relación"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="4" class="d-flex align-center">
                <v-text-field
                  v-model="beneficiaries2[i].dob"
                  label="DOB"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="4" class="d-flex align-center">
                <v-text-field
                  v-model="beneficiaries2[i].ssn"
                  label="SSN"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="4" class="d-flex align-center">
                <v-text-field
                  v-model="beneficiaries2[i].percentage"
                  label="Porciento"
                ></v-text-field>
              </v-col>
            </v-row>
          </div>

          <v-row>
            <v-col cols="12">
              <div class="headline">Información general</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.five_last_year_convicted"
                label="During the last 5 years have you plead guilty to or been convicted of any mcving violations or DUI or have you had a suspension?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.convicted"
                label="Have you ever been convicted of a felony or a misdemeanor?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.bankruptcy"
                label="Have you ever been or are you currently involved in bankruptcy that has not been discharged?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.danger_sport"
                label="Do you participate in any motor sport, automobile,
motorcycle, boat or marathon racing; scuba, skin, sport or sky
diving; parachuting; hand gliding, bunge jumping, mountain
climbing, rodeos, snowmobiling"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.aviation"
                label="Do you participate in any aviation activity other than as a
fare paying passenger?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.existing_life_insurance"
                label="Are there any existing life, disability or annuity contracts?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
            <v-col cols="12" v-if="item.existing_life_insurance == 'Si'">
              <v-row>
                <v-col cols="12" sm="5" lg="3">
                  <v-text-field
                    v-model="item.existing_life_insurance_agency"
                    label="Agencia"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" sm="3" lg="1">
                  <v-text-field
                    v-model="item.existing_life_insurance_amount"
                    label="Amount"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" sm="4" lg="3">
                  <v-text-field
                    v-model="item.existing_life_insurance_policy_number"
                    label="Poliza"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.pending_life"
                label="In the past 12 months have you applied for or do you have pending life or disability applications?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.aids"
                label="Ever tested positive for exposure to the HIV infection or been diagnose as having ARC or AIDS?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.limited_motion"
                label="Have you ever been diagnose with, consulted a medical professional for, falls, numbness, tremors, imbalance or any condition which causes limited motion?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.mental_state"
                label="Have you been diagnose, consulted a medical professional, been treated or advised to be trated for: memory loss, confusion, or amnesia?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.mechanical_devices"
                label="Use or require the use of any mechanical devices such as; a wheelchair, walker, multi-prong cane, hospital bed, dialysis machine, respirator oxygen, motorized cart or stair lifts?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.need_assistance"
                label="Need assistance or supervision in: taking medication, doing housework, laundry, and shopping or meal preparation, bathing, eating, dressing, toileting, transferring, continence?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12">
              <div class="headline">Historia familiar</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="4" lg="3" class="d-flex align-center">
              <v-radio-group
                row
                v-model="family.father.live"
                label="Padre vivo"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
            <v-col
              v-if="family.father.live == 'Si'"
              cols="12"
              sm="3"
              lg="2"
              class="d-flex align-center"
            >
              <v-text-field
                v-model="family.father.age"
                label="Edad"
              ></v-text-field>
            </v-col>
            <v-col
              v-if="family.father.live == 'Si'"
              cols="12"
              sm="9"
              lg="7"
              class="d-flex align-center"
            >
              <v-text-field
                v-model="family.father.health"
                label="	Estado de salud"
              ></v-text-field>
            </v-col>
            <v-col
              v-if="family.father.live == 'No'"
              cols="12"
              sm="3"
              lg="2"
              class="d-flex align-center"
            >
              <v-text-field
                v-model="family.father.age_at_death"
                label="Edad al morir"
              ></v-text-field>
            </v-col>
            <v-col
              v-if="family.father.live == 'No'"
              cols="12"
              sm="9"
              lg="7"
              class="d-flex align-center"
            >
              <v-text-field
                v-model="family.father.cause_of_death"
                label="Causa de muerte"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="4" lg="3" class="d-flex align-center">
              <v-radio-group
                row
                v-model="family.mother.live"
                label="Madre viva"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
            <v-col
              v-if="family.mother.live == 'Si'"
              cols="12"
              sm="3"
              lg="2"
              class="d-flex align-center"
            >
              <v-text-field
                v-model="family.mother.age"
                label="Edad"
              ></v-text-field>
            </v-col>
            <v-col
              v-if="family.mother.live == 'Si'"
              cols="12"
              sm="9"
              lg="7"
              class="d-flex align-center"
            >
              <v-text-field
                v-model="family.mother.health"
                label="Estado de salud"
              ></v-text-field>
            </v-col>
            <v-col
              v-if="family.mother.live == 'No'"
              cols="12"
              sm="3"
              lg="2"
              class="d-flex align-center"
            >
              <v-text-field
                v-model="family.mother.age_at_death"
                label="Edad al morir"
              ></v-text-field>
            </v-col>
            <v-col
              v-if="family.mother.live == 'No'"
              cols="12"
              sm="9"
              lg="7"
              class="d-flex align-center"
            >
              <v-text-field
                v-model="family.mother.cause_of_death"
                label="Causa de muerte"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="4" md="3" lg="2">
              <v-select
                clearable
                v-model="brothers"
                :items="[0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]"
                label="Hermannos"
              ></v-select>
            </v-col>
          </v-row>
          <div v-if="brothers > 0">
            <v-row v-for="(e, i) in brothers" :key="i">
              <v-col cols="12" md="4" lg="3" class="d-flex align-center">
                <v-radio-group
                  row
                  v-model="family.brothers[i].live"
                  :label="`Hermano ${i + 1} vivo`"
                >
                  <v-radio label="No" value="No"></v-radio>
                  <v-radio label="Si" value="Si"></v-radio>
                </v-radio-group>
              </v-col>
              <v-col
                v-if="family.brothers[i].live == 'Si'"
                cols="12"
                sm="3"
                lg="2"
                class="d-flex align-center"
              >
                <v-text-field
                  v-model="family.brothers[i].age"
                  label="Edad"
                ></v-text-field>
              </v-col>
              <v-col
                v-if="family.brothers[i].live == 'Si'"
                cols="12"
                sm="9"
                lg="7"
                class="d-flex align-center"
              >
                <v-text-field
                  v-model="family.brothers[i].health"
                  label="Estado de salud"
                ></v-text-field>
              </v-col>
              <v-col
                v-if="family.brothers[i].live == 'No'"
                cols="12"
                sm="3"
                lg="2"
                class="d-flex align-center"
              >
                <v-text-field
                  v-model="family.brothers[i].age_at_death"
                  label="Edad al morir"
                ></v-text-field>
              </v-col>
              <v-col
                v-if="family.brothers[i].live == 'No'"
                cols="12"
                sm="9"
                lg="7"
                class="d-flex align-center"
              >
                <v-text-field
                  v-model="family.brothers[i].cause_of_death"
                  label="Causa de muerte"
                ></v-text-field>
              </v-col>
            </v-row>
          </div>
          <v-row>
            <v-col cols="12" sm="4" md="3 " lg="2">
              <v-select
                clearable
                v-model="sisters"
                :items="[0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]"
                label="Hermanas"
              ></v-select>
            </v-col>
          </v-row>
          <div v-if="sisters > 0">
            <v-row v-for="(e, i) in sisters" :key="i">
              <v-col cols="12" md="4" lg="3" class="d-flex align-center">
                <v-radio-group
                  row
                  v-model="family.sisters[i].live"
                  :label="`Hermana ${i} vivo`"
                >
                  <v-radio label="No" value="No"></v-radio>
                  <v-radio label="Si" value="Si"></v-radio>
                </v-radio-group>
              </v-col>
              <v-col
                v-if="family.sisters[i].live == 'Si'"
                cols="12"
                sm="3"
                lg="2"
                class="d-flex align-center"
              >
                <v-text-field
                  v-model="family.sisters[i].age"
                  label="Edad"
                ></v-text-field>
              </v-col>
              <v-col
                v-if="family.sisters[i].live == 'Si'"
                cols="12"
                sm="9"
                lg="7"
                class="d-flex align-center"
              >
                <v-text-field
                  v-model="family.sisters[i].health"
                  label="Estado de salud"
                ></v-text-field>
              </v-col>
              <v-col
                v-if="family.sisters[i].live == 'No'"
                cols="12"
                sm="3"
                lg="2"
                class="d-flex align-center"
              >
                <v-text-field
                  v-model="family.sisters[i].age_at_death"
                  label="Edad al morir"
                ></v-text-field>
              </v-col>
              <v-col
                v-if="family.sisters[i].live == 'No'"
                cols="12"
                sm="9"
                lg="7"
                class="d-flex align-center"
              >
                <v-text-field
                  v-model="family.sisters[i].cause_of_death"
                  label="Causa de muerte"
                ></v-text-field>
              </v-col>
            </v-row>
          </div>

          <v-row>
            <v-col cols="12">
              <div class="headline">Médico de atención primaria</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="8" lg="4">
              <v-text-field
                v-model="item.physician_name"
                label="Nombre"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="4" lg="2">
              <v-text-field
                v-model="item.physician_phone"
                label="Teléfono"
              ></v-text-field>
            </v-col>
            <v-col cols="12" lg="6">
              <v-text-field
                v-model="item.physician_address"
                label="Dirección"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <b>Última visita</b>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="5" md="3">
              <DatePicker
                label="Fecha"
                :date-param.sync="item.physician_visit_date"
                :max="new Date().toISOString().substr(0, 10)"
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="8">
              <v-textarea
                v-model="item.physician_visit_reason"
                label="Razón"
                outlined
              ></v-textarea>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="8">
              <v-textarea
                v-model="item.physician_visit_outcome"
                label="Resultado"
                outlined
              ></v-textarea>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12">
              <div class="headline">Cuestionario médico</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="6" sm="3">
              <v-text-field v-model="item.height" label="Altura"></v-text-field>
            </v-col>
            <v-col class="6" sm="3">
              <v-text-field v-model="item.weight" label="Peso"></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.medication"
                label="Are you taking any medication? If yes, list type, dose and frequency"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row v-if="item.medication == 'Si'">
            <v-col cols="12" md="8">
              <v-textarea
                v-model="item.medication_description"
                label="Tipo, dosis y frecuencia"
                outlined
              ></v-textarea>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.tobacco_product"
                label="Have you used any type of product containing tobacco within the last five years?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row v-if="item.tobacco_product == 'Si'">
            <v-col cols="12" md="8">
              <v-textarea
                v-model="item.tobacco_product_description"
                label="Tipo, dosis y frecuencia"
                outlined
              ></v-textarea>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.worked_full_time"
                label="Within the last five years have you worked less than full time, received or applied for disability or workers compensation?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col
              ><b
                >In the past 10 years have you ever been diagnosed or treated by
                a licensed professional for:</b
              ></v-col
            >
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.disease_heart"
                label="Heart attack, heart disease, palpitations, heart murmur, chest pain, high blood pressure, stroke, anemia or any other disease of the blood or circulatory system?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.disease_respiratory"
                label="Emphysema, asthma, shortness of breath, bronchitis, tuberculosis, disorder of the throat or nose, sleep apnea or any disease of the lungs or respiratory system?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.disease_liver"
                label="Disease of the liver, stomach, intestine, pancreas, hepatitis, gallbladder, colon or any digestive system disease?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.disease_bones_muscle"
                label="Spine, hip, knee, shoulder, back, bones, muscles, arthritis, thyroid, gout, joins?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.disease_urinary"
                label="Urinary disease, protein, sugar or blood in the urine, kidney, breast, prostate, bladder?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.disease_depression"
                label="Depression, anxiety, bipolar, memory loss, Alzheimer, dementia, PTSD?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.disease_cancer"
                label="Cancer, polyp, other tumors?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.disease_diabetes"
                label="Diabetes or high blood sugar?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.amputation"
                label="Treated or diagnosed for a disease or condition that resulted in you having an amputation?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.disorder"
                label="Autoimmune disorder such as lupus, blindness, or polio, Parkinson, Huntington's, Lou Gehrig, Multiple Sclerosis, Motor Disease?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.drug"
                label="In the past 10 years have you used marijuana, cocaine, heroin or any other illicit drug or controlled substance?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.consulted_physician"
                label="Within the past five years have you consulted a physician or had x-rays, EKG, or other diagnostic test, been admitted to a hospital or do you plan to enter a hospital within the next 30 days or have any pending doctor's appointment?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.disease_family_history"
                label="Family history of cancer, heart disease, Huntington's disease or kidney disease?"
              >
                <v-radio label="No" value="No"></v-radio>
                <v-radio label="Si" value="Si"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <div class="headline">Información premiun</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="6" sm="3">
              <v-checkbox
                v-model="item.premium_anual"
                label="Anual"
              ></v-checkbox>
            </v-col>
            <v-col cols="6" sm="3">
              <v-checkbox
                class="ml-2"
                v-model="item.premium_semi_anual"
                label="Semestral(6 meses)"
              ></v-checkbox>
            </v-col>
            <v-col cols="6" sm="3">
              <v-checkbox
                class="ml-2"
                v-model="item.premium_quarterly"
                label="Trimestral(3 meses)"
              ></v-checkbox>
            </v-col>
            <v-col cols="6" sm="3">
              <v-checkbox
                v-model="item.premium_monthly"
                label="Mensual"
              ></v-checkbox>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="6" sm="3">
              <v-text-field
                v-model="item.premium_face_amount"
                label="Valor nominal"
              ></v-text-field>
            </v-col>
            <v-col cols="6" sm="3">
              <v-text-field
                v-model="item.premium_name"
                label="Premiun"
              ></v-text-field>
            </v-col>
            <v-col cols="6" sm="3">
              <DatePicker
                label="Fecha"
                :date-param.sync="item.premium_date"
                :max="new Date().toISOString().substr(0, 10)"
              />
            </v-col>
            <v-col cols="6" sm="3">
              <v-text-field
                v-model="item.premium_rate_calss"
                label="Clase de tarifa"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <div class="headline">Información bancaria</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="6" lg="3">
              <v-text-field
                v-model="item.bank_name"
                label="Nombre del banco"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" lg="3">
              <v-text-field
                v-model="item.bank_routing"
                label="Ruta bancaria"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" lg="3">
              <v-text-field
                v-model="item.bank_account"
                label="Cuenta bancaria"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" lg="3">
              <v-text-field
                v-model="item.bank_phone"
                label="Teléfono del banco"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="8">
              <v-textarea
                v-model="item.comment"
                label="Comentario"
                outlined
              ></v-textarea>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <FileUpload
                :files.sync="item.files"
                :file-type="true"
                :multiple="true"
                :files-input="item.files"
                v-on:remove="setRemovedFiles"
              />
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" class="d-md-flex justify-md-end text-center">
              <v-btn
                @click="item = null"
                rounded
                color="primary"
                class="mr-2"
                dark
                >Cancelar</v-btn
              >
              <v-btn
                @click="save"
                rounded
                color="primary"
                dark
                :loading="loading"
                >Guardar</v-btn
              >
            </v-col>
          </v-row>
        </v-form>
      </v-col>
    </v-row>
  </v-container>
</template>


<script>
import { fieldRequire } from "../../../util";
import { isNumber } from "../../../util";

import DatePicker from "../../util/DatePicker";

import { mapActions } from "vuex";
import { mapFields } from "vuex-map-fields";

import ListMixin from "../../mixins/ListTableMixin";

import axios from "axios";

import EventBus from "../../../event-bus";

import FileUpload from "../../util/FileUpload";

import InsuranceMixin from "./InsuranceMixin";


export default {
  mixins: [ListMixin, InsuranceMixin],
  components: {
    DatePicker,
    FileUpload,
  },
  data: () => ({
    fieldRequire: fieldRequire,
    other: null,
    beneficiaries: [],
    beneficiariesNumber: null,
    beneficiaries2: [],
    beneficiariesNumber2: null,
    family: {
      father: {},
      mother: {},
      brothers: [],
      sisters: [],
    },
    brothers: 0,
    sisters: 0,

    files: [],

    headers: [
      { text: "Creado", value: "created_at" },
      { text: "Cliente", value: "name" },
      { text: "Email", value: "email" },
      { text: "Teléfono", value: "phone" },
      { text: "Miembros", value: "members" },
      { text: "Adjuntos", value: "files" },
      { text: "PDF", value: "pdf" },
      { text: "", value: "action" },
    ],
  }),

  methods: {
    edit(id) {
      this.getItem(`insurance/live/${id}`);
    },
    getItems() {
      this.getItemsAction("insurance/live");
    },
    remove(id) {
      if (window.confirm("Seguro desea eliminar este elemento."))
        this.getRemoveAction(`insurance/live/${id}`).then((response) => {
          if (response.data == 1) {
            EventBus.$emit("alert", {
              text: "Se ha eliminado correctamente",
              color: "success",
            });
            this.getItems();
          }
        });
    },
    save() {
      if (!this.$refs.form.validate()) return;

      this.item.removedFiles = this.removedFiles;

      this.loading = true;

      this.item.family = this.family;

      this.item.beneficiaries = this.beneficiaries;
      this.item.beneficiaries2 = this.beneficiaries2;

      let item = Object.assign({}, this.item);
      delete item.files;

      let fd = new FormData();
      if (this.item.files) fd = this.objectToFormData({files: this.item.files});
      fd.append("data", JSON.stringify(item));

      axios({
        method: "post",
        url: "/api/insurance/live",
        data: fd,
        headers: { "Content-Type": "multipart/form-data" },
      })
        .then((response) => {
          return;
          this.item = null;
          EventBus.$emit("alert", {
            text: response.data,
            color: "success",
          });
        })
        .catch((e) => {
          EventBus.$emit("alert", {
            text: e.response.data,
            color: "error",
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
  watch: {
    brothers(val) {
      if (val > 0) {
        let brothers = [];
        for (let i = 0; i < val; i++) brothers.push({});
        this.$set(this.family, "brothers", brothers);
      } else this.$set(this.family, "brothers", []);
    },
    sisters(val) {
      if (val > 0) {
        let sisters = [];
        for (let i = 0; i < val; i++) sisters.push({});
        this.$set(this.family, "sisters", sisters);
      } else this.$set(this.family, "sisters", []);
    },
    beneficiariesNumber(val) {
      this.beneficiaries = [];
      if (val) {
        for (let i = 0; i < val; i++)
          this.beneficiaries.push({
            name: "",
            dob: "",
            relationship: "",
            percentage: "",
            type: 1,
          });
      }
    },
    beneficiariesNumber2(val) {
      this.beneficiaries2 = [];
      if (val) {
        for (let i = 0; i < val; i++)
          this.beneficiaries2.push({
            name: "",
            dob: "",
            relationship: "",
            percentage: "",
            type: 1,
          });
      }
    },
  },
  created() {
    this.item = {
      client: {},
    };
  },

  destroyed() {
    this.item = null;
  },
};
</script>
