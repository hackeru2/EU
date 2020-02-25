<template>
  <div class="logo-header">
    <div id="logo">
      <img :src="url" alt="Logo" />
      <span>Vue.js</span>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      url:
        "https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg"
    };
  }
};
</script>
<style scoped>
img {
  border: none;
}
span {
  color: #303133;
  font-size: 20px;
}
#logo img {
  margin-right: 5px;
  width: 40px;
  height: 40px;
}
</style>