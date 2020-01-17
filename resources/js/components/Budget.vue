<template>
  <el-button>BUDGET COMP - {{budg}}</el-button>
</template>

<script>
import { mapGetters, mapState, mapActions } from "vuex";
export default {
  name: "Budget",
  props: ["budget", "id"],
  data() {
    return { budg: "" };
  },
  async created() {
    console.log("created");
    let myID = await this.getTopicDetails(this.id);
    console.log(myID);
    this.budg = myID.ccm2Id;
  },
  //   updated: function() {
  //     this.$nextTick(function() {
  //       console.log("123");
  //     });
  //   },
  computed: {
    ...mapGetters(["getBudg"]),
    ...mapState(["topicDetails"])
  },
  watch: {
    comp_budget(ov, nv) {
      console.log({ ov, nv });
    }
  },
  methods: {
    ...mapActions(["getTopicDetails"])
  }
  //   mounted() {
  //     this.$nextTick(() => {
  //       console.log("nextTicked");
  //     });
  //     setTimeout(() => {
  //       console.log("wait4");
  //       this.budg = this.topicDetails[this.id].ccm2Id;
  //       console.log(this.topicDetails[this.id].ccm2Id);
  //     }, 2000);
  //   }
};
</script>

<style>
</style>