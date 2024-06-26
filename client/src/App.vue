<template>
  <div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
      <div class="col-md-3 mb-2 mb-md-0">
      إنجاز
      </div>

      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li><a href="#" class="nav-link px-2 link-secondary"> <router-link to="/">الصفحة الرئسية</router-link> </a></li>
      </ul>

      <div class="col-md-3 text-end">
        <button type="button" class="btn btn-outline-primary me-2">    <router-link to="/login">تسجيل دخول</router-link></button>
        <button type="button" class="btn btn-dark">    <router-link class="nav-link px-2 link-secondary" to="/Sighnup">تسجيل </router-link></button>
      </div>
    </header>
    <router-view/>
  </div>
  
</template>

