<template>
  <div>
    <v-card
      class="mx-auto"
      max-width="80%"
      style="margin-top: -64px;"
      max-height="100%"
      height="100%"
    >
      <v-toolbar flat class="black"></v-toolbar>

      <v-card-text style="background-color:black;">
        <div class="text-center notfound">
          <div class="red--text notfound-header">&nbsp;404</div>
          <h3 class="red--text notfound-title">PAGE IS NOT FOUND</h3>

          <button class="notfound-button" @click="$router.push('/')">BACK HOME</button>
        </div>
      </v-card-text>
    </v-card>
  </div>
</template>
<style>
.notfound {
  padding-top: 200px;
  padding-bottom: 250px;
}

.notfound-header {
  font-family: "Lobster", cursive;
  color: rgb(206, 223, 219);
  letter-spacing: 0.6em;
  font-size: 50px;

  padding: 10px;
}
.notfound-title {
  font-family: "Lobster", cursive;
  color: rgb(206, 223, 219);
  letter-spacing: 0.1em;
  font-size: 20px;

  padding: 10px;
}

.notfound-button {
  /* margin-left: 30%;
  margin-right: 30%; */
  margin-top: 50px;
  font-family: "Lobster", cursive;
  color: #00bfa5;
  letter-spacing: 0.1em;
  font-size: 20px;
  border: 2px solid #00bfa5;
  border-radius: 90px;
  padding: 10px;
  cursor: pointer;
}
.notfound-button:hover {
  background-color: #00bfa5;
  border: none;
  color: black;
  padding: 12px;
}
.page-header {
  letter-spacing: 0.1em;
}
</style>
