<template>
  <el-card>
    <div>Programme</div>
    <el-button disabled @click="insertAll">INSERT ALL</el-button>
  </el-card>
</template>
<script>
import pdt from "../../json/programme.json";
import { mapActions, mapState, mapMutations, mapGetters } from "vuex";

export default {
  name: "Programme",
  props: [],
  data() {
    return {
      bigArray: []
    };
  },
  created() {
    //console.log({ pdt });
  },
  async mounted() {
    await this.bigArrayFlat(Object.values(pdt)[0]);
    console.log(this.bigArray);
  },
  computed: {},
  methods: {
    insertAll() {
      return alert("disabled!");
      axios.post("programmes", this.bigArray);
    },
    bigArrayFlat(arr) {
      arr.forEach(item => {
        // console.log(item);
        this.bigArray.push({
          id: item.id,
          description: item.description,
          ccm2Level: item.ccm2Level,
          code: item.code,
          parent: item.parent ? item.parent : null,
          children_ids: item.children
            ? item.children.map(c => c.id).join(",")
            : []
        });
        if (item.children && item.children.length)
          this.bigArrayFlat(item.children);
      });
    }
  }
};
</script>
<style >
</style>