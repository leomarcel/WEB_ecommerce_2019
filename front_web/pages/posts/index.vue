<template>
  <div>
    <div class="justforfun"></div>
    <div class="body">
      <div class="header">
        <div class="left-navbar">
          <a href="/">
            <img class="logo" :src="require('../../assets/logo.png')" />
            <h1 class="title">Amazon cooking</h1>
          </a>
        </div>

        <div class="right-navbar">
          <div class="links">
            <a href="./register" class="button--green">S'inscrire</a>
            <a href="./login" class="button--grey">Se connecter</a>
          </div>
        </div>
      </div>
      <h2>Pas de produit sélectionner</h2>
      <a href="./index_dark" class="btn btn-dark dark_mode">Dark mode</a>
    </div>
  </div>
</template>

<script>
const axios = require('axios')
const $router = require('vue-router')
export default {
}
</script>

<style>
.body {
  margin: 50px;
}
.justforfun {
  background-color: #35495e;
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
  margin: auto;
  width: 50%;
}
.price {
  float: right;
}
.card-img {
  height: 150px;
}
.logo {
  float: left;
  width: 200px;
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
  color: #35495e;
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
img {
  max-width: 100%;
}
.preview {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  -ms-flex-direction: column;
  flex-direction: column;
}
@media screen and (max-width: 996px) {
  .preview {
    margin-bottom: 20px;
  }
}

.preview-pic {
  -webkit-box-flex: 1;
  -webkit-flex-grow: 1;
  -ms-flex-positive: 1;
  flex-grow: 1;
}

.preview-thumbnail.nav-tabs {
  border: none;
  margin-top: 15px;
}
.preview-thumbnail.nav-tabs li {
  width: 18%;
  margin-right: 2.5%;
}
.preview-thumbnail.nav-tabs li img {
  max-width: 100%;
  display: block;
}
.preview-thumbnail.nav-tabs li a {
  padding: 0;
  margin: 0;
}
.preview-thumbnail.nav-tabs li:last-of-type {
  margin-right: 0;
}

.tab-content {
  overflow: hidden;
}
.tab-content img {
  width: 100%;
  -webkit-animation-name: opacity;
  animation-name: opacity;
  -webkit-animation-duration: 0.3s;
  animation-duration: 0.3s;
}

.card {
  margin-top: 50px;
  background: #eee;
  padding: 3em;
  line-height: 1.5em;
}

@media screen and (min-width: 997px) {
  .wrapper {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
  }
}

.details {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  -ms-flex-direction: column;
  flex-direction: column;
}

.colors {
  -webkit-box-flex: 1;
  -webkit-flex-grow: 1;
  -ms-flex-positive: 1;
  flex-grow: 1;
}

.product-title,
.price,
.sizes,
.colors {
  text-transform: UPPERCASE;
  font-weight: bold;
}

.checked,
.price span {
  color: #ff9f1a;
}

.product-title,
.rating,
.product-description,
.price,
.vote,
.sizes {
  margin-bottom: 15px;
}

.product-title {
  margin-top: 0;
}

.size {
  margin-right: 10px;
}
.size:first-of-type {
  margin-left: 40px;
}

.color {
  display: inline-block;
  vertical-align: middle;
  margin-right: 10px;
  height: 2em;
  width: 2em;
  border-radius: 2px;
}
.color:first-of-type {
  margin-left: 20px;
}

.add-to-cart,
.like {
  background: #ff9f1a;
  padding: 1.2em 1.5em;
  border: none;
  text-transform: UPPERCASE;
  font-weight: bold;
  color: #fff;
  -webkit-transition: background 0.3s ease;
  transition: background 0.3s ease;
}
.add-to-cart:hover,
.like:hover {
  background: #b36800;
  color: #fff;
}

.not-available {
  text-align: center;
  line-height: 2em;
}
.not-available:before {
  font-family: fontawesome;
  content: '\f00d';
  color: #fff;
}

.orange {
  background: #ff9f1a;
}

.green {
  background: #85ad00;
}

.blue {
  background: #0076ad;
}

.tooltip-inner {
  padding: 1.3em;
}

@-webkit-keyframes opacity {
  0% {
    opacity: 0;
    -webkit-transform: scale(3);
    transform: scale(3);
  }
  100% {
    opacity: 1;
    -webkit-transform: scale(1);
    transform: scale(1);
  }
}

@keyframes opacity {
  0% {
    opacity: 0;
    -webkit-transform: scale(3);
    transform: scale(3);
  }
  100% {
    opacity: 1;
    -webkit-transform: scale(1);
    transform: scale(1);
  }
}
</style>