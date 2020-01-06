<template>
  <v-row justify="center">
    <v-btn color="primary" dark @click.stop="dialog = true">Open Dialog</v-btn>

    <v-dialog v-model="dialog" max-width="800">
      <v-card>
        <v-card-title class="headline">Use Google's location service?</v-card-title>

        <v-card-text>
          <v-form ref="form" lazy-validation>
            <v-text-field label="Name" required></v-text-field>

            <v-text-field label="E-mail" required></v-text-field>
            <v-text-field label="Name" required></v-text-field>

            <v-text-field label="E-mail" required></v-text-field>
            <v-text-field label="Name" required></v-text-field>

            <v-text-field label="E-mail" required></v-text-field>
          </v-form>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn color="green darken-1" text @click="dialog = false">Disagree</v-btn>

          <v-btn color="green darken-1" text @click="dialog = false">Agree</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
export default {
  data: () => ({
    dialog: false
  })
};
</script>