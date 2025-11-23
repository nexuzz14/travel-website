<script setup></script>

<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'App'
};
</script>

<style>


body {
  font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #0053fa;
}

#app {
  min-height: 100vh;
}
</style>

