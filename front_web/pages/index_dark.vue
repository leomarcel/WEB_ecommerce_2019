<template>
  <div id="index">
    <div class="justforfun"></div>
    <div class="body">
      <div class="header">
        <div class="left-navbar">
          <a href="./">
            <img class="logo" :src="require('../assets/logo_dark.jpg')" />
            <h1 class="title">Amazon space-cooking</h1>
          </a>
        </div>
        <div class="right-navbar">
          <div class="links">
            <a href="./register" class="button--green">S'inscrire</a>
            <a href="./login" class="button--grey">Se connecter</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="objet" v-for="data in Json_dark" v-bind:key="data.id">
          <div class="card" style="width: 18rem;">
            <img class="card-img" alt="Card image cap" v-bind:src="data.image.default" />
            <div class="card-body">
              <h5 class="card-title" style="float:left; margin-right: 10px">{{ data.title }}</h5>
              <h2 class="card-title price" style="float:left; margin-right: 10px">{{ data.price.value }} €</h2>
              <a href="#" class="btn btn-dark">Voir l'article</a>
            </div>
          </div>
        </div>
      </div>
      <a href="./" class="btn btn-light dark_mode">White mode</a>
    </div>
  </div>
</template>

<script>
const axios = require('axios')

export default {
  asyncData({ params, error }) {
    return axios
      .get(`http://127.0.0.1:8000/dark-api/:username/sale`)
      .then(res => {
        return { Json_dark: res.data.sales_item }
      })
      .catch(e => {
        error({ statusCode: 404, message: 'URL non valide' })
      })
  }
}
</script>

<style>
body {
  margin: 50px;
  background-color: rgb(23, 24, 25);
}
.justforfun {
  width: 100%;
  height: 20px;
}
.header {
  width: 100%;
  height: 200px;
  vertical-align: middle;
}
.card-title {
  float: left;
}
.card {
  margin: 20px;
  padding: 5px;
}
.price {
  float: right;
}
.card-img {
  height: 150px;
}
.logo {
  float: left;
  width: 150px;
  margin-right: 10px;
}
.dark_mode {
  position: fixed;
  right: 10px;
  bottom: 10px;
}
.title {
  font-family: 'Quicksand', 'Source Sans Pro', -apple-system, BlinkMacSystemFont,
    'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  font-weight: 300;
  font-size: 100px;
  color: white;
  letter-spacing: 1px;
  float: left;
}
.right-navbar {
  float: right;
}
.search-bar {
  float: left;
  margin-right: 25px;
}
.links {
  padding-top: 15px;
}
</style>
