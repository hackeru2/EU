<template>
  <div>
    <div>
      <router-link to="calls">Calls</router-link>
    </div>
    <h1>Keywords</h1>
    <el-card v-for="(item,title ,index) in groupedKeyWords" :key="index">
      <h3>{{index}}.{{ title}}</h3>
      <!-- <div>{{item}}</div> -->
    </el-card>
  </div>
</template>

<script>
import { mapActions, mapState, mapMutations } from "vuex";
export default {
  data() {
    return {};
  },
  computed: {
    ...mapState(["groupedKeyWords"])
  },
  methods: {}
};
</script>

<style>
</style>