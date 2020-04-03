<template>
  <div class="container login-container">
    <div>
      <div class="login-form-1">
        <div class="note">
          <p>Veuillez vous identifier</p>
        </div>
        <form>
          <div class="form-group">
            <input
              type="text"
              class="form-control"
              placeholder="Your Email *"
              value=""
            />
          </div>
          <div class="form-group">
            <input
              type="password"
              class="form-control"
              placeholder="Your Password *"
              value=""
            />
          </div>
          <div class="form-group">
            <input type="submit" class="btnSubmit" value="Login" />
          </div>
          <div class="form-group">
            <router-link to="/register">Register</router-link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'login',
  data() {
    return {
      msg: 'Please login or register'
    }
  }
}
</script>

<style>
.note {
  text-align: center;
  height: 80px;
  background: -webkit-linear-gradient(left, #0072ff, #8811c5);
  color: #fff;
  font-weight: bold;
  line-height: 80px;
}
.login-container {
  margin-top: 2%;
  margin-bottom: 2%;
}
.login-form-1 {
  box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 9px 26px 0 rgba(0, 0, 0, 0.19);
  height: 350px;
}
.login-form-1 h3 {
  text-align: center;
  color: #333;
}
.login-container form {
  padding: 5%;
}
.btnSubmit {
  width: 50%;
  border-radius: 1rem;
  padding: 1.5%;
  border: none;
  cursor: pointer;
}
.login-form-1 .btnSubmit {
  font-weight: 600;
  color: #fff;
  background-color: #0062cc;
}
.login-form-1 .register {
  color: #0062cc;
  font-weight: 600;
  text-decoration: none;
}
.form-control {
  border-radius: 1.5rem;
}
.form-content {
  padding: 5%;
  border: 1px solid #ced4da;
  margin-bottom: 2%;
}
</style>
