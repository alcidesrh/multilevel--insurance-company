<template>
  <v-container fluid>
    <v-row v-if="!item">
      <v-col class="d-flex">
        <a v-if="items.length"
          target="_blank"
          :href="'/api/insurance/obama-care' + getQuery()"
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
      <v-col cols="12" md="6" lg="3">
        <v-select
          clearable
          v-model="filters.state"
          :items="[
            'Alabama',
            'Alaska',
            'American Samoa',
            'Arizona',
            'Arkansas',
            'California',
            'Colorado',
            'Connecticut',
            'Delaware',
            'District of Columbia',
            'Federated States of Micronesia',
            'Florida',
            'Georgia',
            'Guam',
            'Hawaii',
            'Idaho',
            'Illinois',
            'Indiana',
            'Iowa',
            'Kansas',
            'Kentucky',
            'Louisiana',
            'Maine',
            'Marshall Islands',
            'Maryland',
            'Massachusetts',
            'Michigan',
            'Minnesota',
            'Mississippi',
            'Missouri',
            'Montana',
            'Nebraska',
            'Nevada',
            'New Hampshire',
            'New Jersey',
            'New Mexico',
            'New York',
            'North Carolina',
            'North Dakota',
            'Northern Mariana Islands',
            'Ohio',
            'Oklahoma',
            'Oregon',
            'Palau',
            'Pennsylvania',
            'Puerto Rico',
            'Rhode Island',
            'South Carolina',
            'South Dakota',
            'Tennessee',
            'Texas',
            'Utah',
            'Vermont',
            'Virgin Island',
            'Virginia',
            'Washington',
            'West Virginia',
            'Wisconsin',
            'Wyoming',
          ]"
          label="Estado"
        ></v-select>
      </v-col>
      <v-col cols="12" md="6" lg="3">
        <v-select
          clearable
          v-model="filters.company"
          :items="[
            'Bright Health',
            'Oscar',
            'Ambetter',
            'Molina',
            'Avmed',
            'Cigna',
            'Florida Blue',
            'Friday',
            'Blue Cross',
          ]"
          label="Compañía"
        ></v-select>
      </v-col>
      <v-col cols="12" md="6" lg="2">
        <v-select
          clearable
          v-model="filters.plan"
          :items="['Bronce', 'Plata', 'Oro', 'Platino']"
          label="Tipo de Plan"
        ></v-select>
      </v-col>

      <v-col cols="12" md="12" lg="6">
        <UserSelectMultiple
          :usersSelected.sync="agentsSelected"
          :users="agents"
          label="Agentes"
        />
      </v-col>
      <v-col cols="12" md="6" lg="3">
        <DatePicker
          label="Desde"
          :date-param.sync="filters.from"
          :max="new Date().toISOString().substr(0, 10)"
        />
      </v-col>
      <v-col cols="12" md="6" lg="3">
        <DatePicker
          :min="filters.from"
          :max="new Date().toISOString().substr(0, 10)"
          label="Hasta"
          :date-param.sync="filters.to"
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
              itemsPerPageOptions: [2,3,4,10, 15, 30, 100, -1],
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
                  :href="'/insurance/pdf/1/' + item.id + '/show'"
                  style="text-decoration: none"
                >
                  <v-icon class="cursor-pointer" color="teal">mdi-eye</v-icon>
                </a>
                <a
                  target="_blank"
                  :href="'/insurance/pdf/1/' + item.id"
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
      <v-col cols="12">
        <v-alert v-if="error" dense outlined type="error">
          Faltan algunos datos requerido. Por favor revise los marcado en rojo.
          <strong>type</strong> of error
        </v-alert>
      </v-col>
      <v-col cols="12">
        <v-form ref="form" lazy-validation>
          <v-row>
            <v-col>
              <v-alert dense type="info" class="font-weight-medium" v-if="type">
                Importante esta forma debe ser llenada solo para
                <strong>NUEVOS CLIENTES</strong>. Si usted necesita
                <strong>RENOVAR</strong> diríjase a la forma de
                <a
                  @click="$router.push({ name: 'insurance_obama_renew' })"
                  class="font-weight-bold"
                  style="color: white"
                  >RENOVACIÓN <v-icon size="20">mdi-open-in-new</v-icon></a
                >
              </v-alert>
              <v-alert dense type="info" class="font-weight-medium" v-else>
                Importante esta forma debe ser llenada solo para
                <strong>RENOVACIONES DE CLIENTES EXISTENTES</strong>. Si usted
                necesita someter un <strong>NUEVO CLIENTE</strong> diríjase a la
                forma de
                <a
                  @click="$router.push({ name: 'insurance_obama_new' })"
                  class="font-weight-bold"
                  style="color: white"
                  >NUEVO CLIENTE <v-icon size="20">mdi-open-in-new</v-icon></a
                >
              </v-alert>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <div class="headline">Poliza</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="4" md="3">
              <v-select
                v-model="item.company"
                :items="[
                  'Bright Health',
                  'Oscar',
                  'Ambetter',
                  'Molina',
                  'Avmed',
                  'Cigna',
                  'Florida Blue',
                  'Friday',
                  'Blue Cross',
                ]"
                label="Compañía"
              ></v-select>
            </v-col>
            <v-col cols="12" sm="4" md="3">
              <v-select
                v-model="item.plan"
                :items="['Bronce', 'Plata', 'Oro', 'Platino']"
                label="Tipo de Plan"
              ></v-select>
            </v-col>
            <v-col cols="12" sm="12" md="6">
              <v-text-field
                v-model="item.plan_id"
                label="Plan ID"
                placeholder=""
              ></v-text-field>
              <v-alert dense outlined type="info">
                <span style="font-size: 12px"
                  >Por favor escribir el plan completo como aparece en el
                  <strong>Mercado de Salud</strong></span
                >
              </v-alert>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="6" lg="3">
              <DatePicker
                label="Fecha de efectividad"
                :date-param.sync="item.date"
                :min="new Date().toISOString().substr(0, 10)"
              />
            </v-col>
            <v-col cols="12" sm="6" lg="3">
              <v-text-field
                v-model="item.amount"
                label="Monto a Pagar"
                prepend-inner-icon="mdi-currency-usd"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" lg="3">
              <v-text-field
                v-model="item.members"
                label="Miembros a recibir cobertura"
                prepend-inner-icon="mdi-pound"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" lg="3">
              <v-text-field
                v-model="item.income"
                label="Ingreso Anual (Familiar)"
                prepend-inner-icon="mdi-currency-usd"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12">
              <div class="headline">Información de cliente primario</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12">
              <v-radio-group
                v-model="item.apply"
                row
                label="Aplicará para cobertura"
                :rules="fieldRequire"
                required
              >
                <v-radio label="Si" value="si"></v-radio>
                <v-radio label="No" value="no"></v-radio>
              </v-radio-group>
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
            <v-col cols="12" sm="6">
              <v-text-field
                v-model="item.client.phone"
                label="Teléfono"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6">
              <v-text-field
                v-model="item.client.email"
                label="Email"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="6">
              <v-radio-group v-model="item.client.sex" row label="Sexo">
                <v-radio label="Masculino" value="Masculino"></v-radio>
                <v-radio label="Femenino" value="Femenino"></v-radio>
              </v-radio-group>
            </v-col>
            <v-col cols="12" sm="6">
              <DatePicker
                label="Fecha de Nacimiento"
                birthday="true"
                :date-param.sync="item.client.birthday"
                :max="
                  new Date(new Date().setFullYear(new Date().getFullYear()))
                    .toISOString()
                    .substr(0, 10)
                "
              />
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="6">
              <v-text-field
                v-model="item.client.ssn"
                label="SSN"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6">
              <v-autocomplete
                v-model="item.client.country"
                :items="countries"
                label="Nacionalidad"
                item-value="id"
                item-text="name"
              ></v-autocomplete>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="6">
              <v-text-field
                v-model="item.client.address"
                label="Dirección"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="2">
              <v-text-field
                v-model="item.client.apt"
                label="Apt"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="4">
              <v-select
                v-model="item.client.state"
                :items="[
                  'Alabama',
                  'Alaska',
                  'American Samoa',
                  'Arizona',
                  'Arkansas',
                  'California',
                  'Colorado',
                  'Connecticut',
                  'Delaware',
                  'District of Columbia',
                  'Federated States of Micronesia',
                  'Florida',
                  'Georgia',
                  'Guam',
                  'Hawaii',
                  'Idaho',
                  'Illinois',
                  'Indiana',
                  'Iowa',
                  'Kansas',
                  'Kentucky',
                  'Louisiana',
                  'Maine',
                  'Marshall Islands',
                  'Maryland',
                  'Massachusetts',
                  'Michigan',
                  'Minnesota',
                  'Mississippi',
                  'Missouri',
                  'Montana',
                  'Nebraska',
                  'Nevada',
                  'New Hampshire',
                  'New Jersey',
                  'New Mexico',
                  'New York',
                  'North Carolina',
                  'North Dakota',
                  'Northern Mariana Islands',
                  'Ohio',
                  'Oklahoma',
                  'Oregon',
                  'Palau',
                  'Pennsylvania',
                  'Puerto Rico',
                  'Rhode Island',
                  'South Carolina',
                  'South Dakota',
                  'Tennessee',
                  'Texas',
                  'Utah',
                  'Vermont',
                  'Virgin Island',
                  'Virginia',
                  'Washington',
                  'West Virginia',
                  'Wisconsin',
                  'Wyoming',
                ]"
                label="Estado"
              ></v-select>
            </v-col>
            <v-col cols="12" sm="4">
              <v-text-field
                v-model="item.client.city"
                label="Ciudad"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="4">
              <v-text-field
                v-model="item.client.zipcode"
                label="Zipcode"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="4">
              <v-radio-group label="Status Legal" v-model="item.client.status">
                <v-radio
                  label="Permiso de Trabajo"
                  value="Permiso de Trabajo"
                ></v-radio>
                <v-radio label="Residencia" value="Residencia"></v-radio>
                <v-radio label="Ciudadanía" value="Ciudadanía"></v-radio>
                <v-radio label="Parol" value="Parol"></v-radio>
                <v-text-field
                  v-if="item.client.status == 'Parol'"
                  v-model="item.client.parol_number"
                  label="Númeor del Parol"
                  prepend-inner-icon="mdi-pound"
                ></v-text-field>
              </v-radio-group>
              <p>
                <strong>Nota:</strong> Para asegurar un proceso más rápido y
                sencillo por favor pedir al cliente los documentos a usar,
                escanearlos o tirarle fotos y chequear doble los números y
                letras.
              </p>
            </v-col>
            <v-col cols="12" sm="8">
              <v-row v-if="item.client.status == 'Ciudadanía'">
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="item.client.citizenship_number"
                    label="De Ciudadanía"
                    prepend-inner-icon="mdi-pound"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row v-else-if="item.client.status">
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="item.client.alien"
                    label="Alien"
                    prepend-inner-icon="mdi-pound"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="item.client.card"
                    label="Card"
                    prepend-inner-icon="mdi-pound"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row
                v-if="
                  item.client.status &&
                  item.client.status != 'Ciudadanía' &&
                  item.client.status != 'Residencia'
                "
              >
                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="item.client.category"
                    label="Categoría"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <DatePicker
                    label="Expiración"
                    :date-param.sync="item.client.expiration"
                    :min="new Date().toISOString().substr(0, 10)"
                  />
                </v-col>
              </v-row>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="6" md="6">
              <v-text-field
                v-model="item.client.employer"
                label="Empleador"
                :rules="fieldRequire"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="3">
              <v-text-field
                v-model="item.client.employer_phone"
                label="Telf del Empleador"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" lg="5">
              <v-radio-group
                label="Forma de Pago"
                :row="$vuetify.breakpoint.smAndUp"
                v-model="item.client.earn_type"
                :rules="fieldRequire"
                required
              >
                <v-radio label="W2" value="W2"></v-radio>
                <v-radio label="1099" value="1099"></v-radio>
              </v-radio-group>
            </v-col>
            <v-col cols="12" sm="6" md="3">
              <v-text-field
                v-model="item.client.income"
                label="Income Anual"
                :rules="fieldRequire"
                required
                prepend-inner-icon="mdi-currency-usd"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="4">
              <v-select
                v-model="item.client.persons_taxes"
                :items="[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]"
                label="Cuantas Personas en Taxes"
              ></v-select>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <v-radio-group
                label="Status Marital"
                :row="$vuetify.breakpoint.smAndUp"
                v-model="item.client.status_marital"
                :rules="fieldRequire"
                required
              >
                <v-radio label="Soltero" value="Soltero"></v-radio>
                <v-radio label="Casado" value="Casado"></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>

          <div v-if="item.client.status_marital == 'Casado'">
            <v-row>
              <v-col cols="12">
                <div class="headline">Información del cónyugue</div>
                <v-divider width="500" class="mt-2"></v-divider>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12">
                <v-radio-group
                  v-model="item.spouse_apply"
                  row
                  label="Aplicará para cobertura"
                  :rules="fieldRequire"
                  required
                >
                  <v-radio label="Si" value="si"></v-radio>
                  <v-radio label="No" value="no"></v-radio>
                </v-radio-group>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="item.spouse_name"
                  label="Nombre"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="item.spouse_last_name"
                  label="Apellido"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="item.spouse_phone"
                  label="Teléfono"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <DatePicker
                  label="Fecha de Nacimiento"
                  birthday="true"
                  :date-param.sync="item.spouse_birthday"
                  :max="
                    new Date(new Date().setFullYear(new Date().getFullYear()))
                      .toISOString()
                      .substr(0, 10)
                  "
                />
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="item.spouse_social_number"
                  label="SSN"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="item.spouse_employer"
                  label="Empleador"
                  :rules="fieldRequire"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="6" lg="4">
                <v-radio-group
                  label="Forma de Pago"
                  :row="$vuetify.breakpoint.smAndUp"
                  v-model="item.spouse_earn_type"
                  :rules="fieldRequire"
                  required
                >
                  <v-radio label="W2" value="W2"></v-radio>
                  <v-radio label="1099" value="1099"></v-radio>
                </v-radio-group>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-text-field
                  v-model="item.spouse_anual_income"
                  label="Income Anual"
                  :rules="fieldRequire"
                  required
                  prepend-inner-icon="mdi-currency-usd"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="4">
                <v-radio-group
                  label="Status Legal"
                  v-model="item.spouse_status"
                >
                  <v-radio
                    label="Permiso de Trabajo"
                    value="Permiso de Trabajo"
                  ></v-radio>
                  <v-radio label="Residencia" value="Residencia"></v-radio>
                  <v-radio label="Ciudadanía" value="Ciudadanía"></v-radio>
                  <v-radio label="Parol" value="Parol"></v-radio>
                  <v-text-field
                    v-if="item.spouse_status == 'Parol'"
                    v-model="item.spouse_parol_number"
                    label="Númeor del Parol"
                    prepend-inner-icon="mdi-pound"
                  ></v-text-field>
                </v-radio-group>
                <p>
                  <strong>Nota:</strong> Para asegurar un proceso más rápido y
                  sencillo por favor pedir al cliente los documentos a usar,
                  escanearlos o tirarle fotos y chequear doble los números y
                  letras.
                </p>
              </v-col>
              <v-col cols="12" sm="8">
                <v-row v-if="item.spouse_status == 'Ciudadanía'">
                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model="item.spouse_citizenship_number"
                      label="De Ciudadanía"
                      prepend-inner-icon="mdi-pound"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row v-else-if="item.spouse_status">
                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model="item.spouse_alien"
                      label="Alien"
                      prepend-inner-icon="mdi-pound"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model="item.spouse_card"
                      label="Card"
                      prepend-inner-icon="mdi-pound"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row
                  v-if="
                    item.spouse_status &&
                    item.spouse_status != 'Ciudadanía' &&
                    item.spouse_status != 'Residencia'
                  "
                >
                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model="item.spouse_category"
                      label="Categoría"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6">
                    <DatePicker
                      label="Expiración"
                      :date-param.sync="item.spouse_expiration"
                      :min="new Date().toISOString().substr(0, 10)"
                    />
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
          </div>

          <v-row>
            <v-col cols="12">
              <div class="headline">Dependientes</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="6" md="3">
              <v-select
                v-model="item.client.dependents"
                :items="[0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]"
                label="Seleccionar"
                :rules="fieldRequire"
                required
                prepend-i
                @change="setDependents"
              ></v-select>
            </v-col>
          </v-row>

          <div v-if="dependents.length">
            <div v-for="(item, index) in dependents" :key="index">
              <v-row>
                <v-col cols="12">
                  <div class="headline">
                    Dependiente {{ index + 1 }} información
                  </div>
                  <v-divider width="500" class="mt-2"></v-divider>
                </v-col>
              </v-row>

              <v-row>
                <v-col cols="12">
                  <v-radio-group
                    v-model="item.dependent_apply"
                    row
                    label="Aplicará para cobertura"
                    :rules="fieldRequire"
                    required
                  >
                    <v-radio label="Si" value="si"></v-radio>
                    <v-radio label="No" value="no"></v-radio>
                  </v-radio-group>
                </v-col>
              </v-row>

              <v-row>
                <v-col cols="12" sm="6">
                  <v-text-field
                    v-model="item.dependent_name"
                    label="Nombre"
                    :rules="fieldRequire"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" sm="6">
                  <v-text-field
                    v-model="item.dependent_last_name"
                    label="Apellido"
                    :rules="fieldRequire"
                    required
                  ></v-text-field>
                </v-col>
              </v-row>

              <v-row>
                <v-col cols="12" sm="6">
                  <v-radio-group
                    v-model="item.dependent_gender"
                    row
                    label="Sexo"
                  >
                    <v-radio label="Masculino" value="Masculino"></v-radio>
                    <v-radio label="Femenino" value="Femenino"></v-radio>
                  </v-radio-group>
                </v-col>
                <v-col cols="12" sm="6">
                  <DatePicker
                    label="Fecha de Nacimiento"
                    birthday="true"
                    :date-param.sync="item.dependent_birthday"
                    :max="
                      new Date(new Date().setFullYear(new Date().getFullYear()))
                        .toISOString()
                        .substr(0, 10)
                    "
                  />
                </v-col>
              </v-row>

              <v-row>
                <v-col cols="12" sm="6">
                  <v-text-field
                    v-model="item.dependent_social_number"
                    label="SSN"
                    :rules="fieldRequire"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" sm="6">
                  <v-text-field
                    v-model="item.dependent_relation"
                    label="Relación: Madre o Padre, Hijo (a), Otro"
                    :rules="fieldRequire"
                    required
                  ></v-text-field>
                </v-col>
              </v-row>

              <v-row>
                <v-col cols="12" sm="6" lg="4">
                  <v-radio-group
                    label="Forma de Pago"
                    :row="$vuetify.breakpoint.smAndUp"
                    v-model="item.earn_type"
                    :rules="fieldRequire"
                    required
                  >
                    <v-radio label="W2" value="W2"></v-radio>
                    <v-radio label="1099" value="1099"></v-radio>
                  </v-radio-group>
                </v-col>
              </v-row>

              <v-row>
                <v-col cols="12" sm="4">
                  <v-radio-group
                    label="Status Legal"
                    v-model="item.dependent_status"
                  >
                    <v-radio
                      label="Permiso de Trabajo"
                      value="Permiso de Trabajo"
                    ></v-radio>
                    <v-radio label="Residencia" value="Residencia"></v-radio>
                    <v-radio label="Ciudadanía" value="Ciudadanía"></v-radio>
                    <v-radio label="Parol" value="Parol"></v-radio>
                    <v-text-field
                      v-if="item.dependent_status == 'Parol'"
                      v-model="item.dependent_parol_number"
                      label="Númeor del Parol"
                      prepend-inner-icon="mdi-pound"
                    ></v-text-field>
                  </v-radio-group>
                  <p>
                    <strong>Nota:</strong> Para asegurar un proceso más rápido y
                    sencillo por favor pedir al cliente los documentos a usar,
                    escanearlos o tirarle fotos y chequear doble los números y
                    letras.
                  </p>
                </v-col>
                <v-col cols="12" sm="8">
                  <v-row v-if="item.dependent_status == 'Ciudadanía'">
                    <v-col cols="12" sm="6">
                      <v-text-field
                        v-model="item.dependent_citizenship_number"
                        label="De Ciudadanía"
                        prepend-inner-icon="mdi-pound"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row v-else-if="item.dependent_status">
                    <v-col cols="12" sm="6">
                      <v-text-field
                        v-model="item.dependent_alien"
                        label="Alien"
                        prepend-inner-icon="mdi-pound"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6">
                      <v-text-field
                        v-model="item.dependent_card"
                        label="Card"
                        prepend-inner-icon="mdi-pound"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row
                    v-if="
                      item.dependent_status &&
                      item.dependent_status != 'Ciudadanía' &&
                      item.dependent_status != 'Residencia'
                    "
                  >
                    <v-col cols="12" sm="6">
                      <v-text-field
                        v-model="item.dependent_category"
                        label="Categoría"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6">
                      <DatePicker
                        label="Expiración"
                        :date-param.sync="item.dependent_expiration"
                        :min="new Date().toISOString().substr(0, 10)"
                      />
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
            </div>
          </div>

          <v-row>
            <v-col cols="12">
              <div class="headline">Información de pago</div>
              <v-divider width="500" class="mt-2"></v-divider>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="6" md="3">
              <v-select
                v-model="item.client.payment_information"
                :items="[
                  '$0 Dollar a Pagar',
                  'Tarjeta de Débito',
                  'Cuenta Bancaria',
                ]"
                label="Seleccionar"
              ></v-select>
            </v-col>
          </v-row>

          <v-row v-if="item.client.payment_information == 'Tarjeta de Débito'">
            <v-col cols="12" md="10">
              <v-row>
                <v-col cols="12" sm="6">
                  <v-select
                    v-model="item.card_type"
                    :items="['Visa', 'Mastercard', 'American Express']"
                    label="Tipo de Tarjeta"
                  ></v-select>
                </v-col>
                <v-col cols="12" sm="6">
                  <v-text-field
                    v-model="item.card_name"
                    label="Nombre en Tarjeta"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="4">
                  <v-row>
                    <v-col>
                      <v-text-field
                        v-model="item.card_number"
                        label="Número de Tarjeta"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-col>
                <v-col cols="12" sm="8">
                  <v-row>
                    <v-col cols="12" sm="4">
                      <v-text-field
                        v-model="item.expiration_month"
                        label="Mes Expiración"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="4">
                      <v-text-field
                        v-model="item.expiration_year"
                        label="Año Expiración"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="4">
                      <v-text-field
                        v-model="item.ccv"
                        label="CCV"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
            </v-col>
          </v-row>

          <v-row v-if="item.client.payment_information == 'Cuenta Bancaria'">
            <v-col cols="12" md="10">
              <v-row>
                <v-col cols="12" sm="6">
                  <v-text-field
                    v-model="item.bank_name"
                    label="Nombre del Banco"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" sm="6">
                  <v-text-field
                    v-model="item.account_name"
                    label="Nombre en la Cuenta"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" sm="6">
                  <v-text-field
                    v-model="item.route_number"
                    label="# Ruta"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" sm="6">
                  <v-text-field
                    v-model="item.account_number"
                    label="# Cuenta"
                  ></v-text-field>
                </v-col>
              </v-row>
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
            <v-col cols="12" sm="6">
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
import { fieldRequire, emailRequire } from "../../../util";
import { isNumber } from "../../../util";

import DatePicker from "../../util/DatePicker";

import ListMixin from "../../mixins/ListTableMixin";

import axios from "axios";

import EventBus from "../../../event-bus";

import FileUpload from "../../util/FileUpload";

import InsuranceMixin from "./InsuranceMixin";


export default {
  mixins: [ListMixin, InsuranceMixin],
  components: {
    DatePicker,
    FileUpload
  },
  data: () => ({
    emailRequire: emailRequire,
    fieldRequire: fieldRequire,    
    countries: [],
    
    dependents: [],
    error: false,

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
  computed: {
    type() {
      return this.$route.meta.type;
    },
  },
  methods: {
    edit(id) {
      this.getItem(`insurance/obama-care/${id}`).then(() => {
        let client = this.item.client;
        this.dependents = client.dependents_list || [];
        delete client.dependents_list;

        if (this.item.client.country)
          this.item.client.country = parseInt(this.item.client.country);

        if (this.item.client.persons_taxes)
          this.item.client.persons_taxes = parseInt(
            this.item.client.persons_taxes
          );
      });
    },
    remove(id) {
      if (window.confirm("Seguro desea eliminar este elemento."))
        this.getRemoveAction(`insurance/obama-care/${id}`).then((response) => {
          if (response.data == 1) {
            EventBus.$emit("alert", {
              text: "Se ha eliminado correctamente",
              color: "success",
            });
            this.getItems();
          }
        });
    },
    setDependents() {
      this.dependents = [];
      for (let i = 0; i < this.item.client.dependents; i++) {
        this.dependents.push({});
      }
    },
    getItems() {
      this.getItemsAction({
        endpoint: "insurance/obama-care",
        query: {
          type: this.type,
        },
      });
    },
    save() {
      if (!this.$refs.form.validate()) {
        this.error = true;
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0;
        return;
      }
      this.loading = true;

      if (this.type == "new") this.item.insurance = "Nuevo seguro";
      else if (this.type == "renew") this.item.insurance = "Renovación";
      else this.item.insurance = "Cambio de vida";

      this.item.dependents = JSON.stringify(this.dependents);
      this.item.removedFiles = this.removedFiles;

      axios({
        method: "post",
        url: "/api/insurance/obama-care",
        data: this.objectToFormData(this.item),
        headers: { "Content-Type": "multipart/form-data" },
      })
        .then((response) => {
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
    getQuery(){
      return `?page=${this.page}&perPage=${this.perPage}&type=${this.type}&pdf=1&` + this.objectToGetParameters()
    },
  },
  destroyed() {
    this.item = null;
  },
};
</script>
