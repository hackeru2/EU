<template>
  <div class="hero-image" :style="{ backgroundImage: 'url(' + image + ')' }">
    <div class="hero-text">
      <h1 style="font-size:50px">{{title}}</h1>
      <p>Topic details:</p>
      <button>Hire me</button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["title"],
  data() {
    return {
      image: "http://" + window.location.host + "/images/call.jpg"
    };
  }
};
</script>

<style>
.hero-image {
  background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5));
  /* url("photographer.jpg"); */
  height: 500px;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  position: relative;
}

.hero-text {
  text-align: center;
  position: absolute;
  top: 50%;
  left: 50%;
  font-weight: 1000;
  text-shadow: 2px 2px rgba(29, 107, 101, 0.623);

  transform: translate(-50%, -50%);
  color: white;
}

.hero-text button {
  border: none;
  outline: 0;
  display: inline-block;
  padding: 10px 25px;
  color: black;
  background-color: #ddd;
  text-align: center;
  cursor: pointer;
}

.hero-text button:hover {
  background-color: #555;
  color: white;
}
</style>