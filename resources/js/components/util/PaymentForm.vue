<template>
  <div id="form-container">
    <v-row>
      <v-col cols="12" :md="dialog ? 12 : 5">
        <div id="sq-card-number"></div>
      </v-col>
      <v-col cols="12" :md="dialog ? 3 : 2">
        <div class="third" id="sq-cvv"></div>
      </v-col>
      <v-col cols="12" :md="dialog ? 6 : 3">
        <div class="third" id="sq-expiration-date"></div>
      </v-col> 
      <v-col cols="12" :md="dialog ? 3 : 2">
        <div class="third" id="sq-postal-code"></div>
      </v-col>     
    </v-row>
    <v-progress-linear  v-show="loadingForm"
      indeterminate
    ></v-progress-linear>
    
  </div>
</template>
<script>
import EventBus from "../../event-bus";
export default {
  props: ["nonce", "loading", "dialog"],
  data() {
    return {
      paymentForm: null,
      loadingForm: false,
    };
  },
  methods: {
    async onGetCardNonce(event) {
      await this.paymentForm.requestCardNonce();
    },
  },
  mounted() {

    const $this = this;
    const paymentForm = new SqPaymentForm({

      applicationId: 'sq0idp-o14s9Ua7LYz1EkUbOMP88g',// PROD
      // applicationId: 'sandbox-sq0idb-G89NnVyBJfNzMENcSwK1LQ',// TEST
      
      inputClass: "sq-input",
      autoBuild: false,
      // Customize the CSS for SqPaymentForm iframe elements
      inputStyles: [
        {
          fontSize: "16px",
          lineHeight: "24px",
          padding: "16px",
          placeholderColor: "#a0a0a0",
          backgroundColor: "transparent",
        },
      ],
      // Initialize the credit card placeholders
      cardNumber: {
        elementId: "sq-card-number",
        placeholder: "Número de la tarjeta",
      },
      cvv: {
        elementId: "sq-cvv",
        placeholder: "CVV",
      },
      expirationDate: {
        elementId: "sq-expiration-date",
        placeholder: "MM/YY Fecha expiración",
      },
      postalCode: {
        elementId: "sq-postal-code",
        placeholder: "Zip Code",
      },
      // SqPaymentForm callback functions
      callbacks: {
        /*
         * callback function: cardNonceResponseReceived
         * Triggered when: SqPaymentForm completes a card nonce request
         */
        cardNonceResponseReceived: function (errors, nonce, cardData) {

          $this.$emit("update:loading", false);
          
          if (errors) {
            // Log errors from nonce generation to the browser developer console.
            console.error("Encountered errors:");
            errors.forEach(function (error) {
              EventBus.$emit("alert", {
                text: error.message,
                color: "warning",
              });
              console.error("  " + error.message);
            });
            // alert(
            //   "Encountered errors, check browser developer console for more details"
            // );
            return;
          }
          $this.$emit("update:nonce", nonce);
          
          // alert(`The generated nonce is:\n${nonce}`);
          //TODO: Replace alert with code in step 2.1
        },
        paymentFormLoaded: function () {
          $this.loadingForm = false;
          // paymentForm.setPostalCode("123");
        },
      },
    });

    this.paymentForm = paymentForm;
    if(this.paymentForm){
      this.loadingForm = true;
      this.paymentForm.build();
    }
  },
};
</script>

<style scoped>
iframe {
  border: 1px solid #949494;
}
</style>
