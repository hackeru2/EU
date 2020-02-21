<template>
  <el-button
    :icon="!link ? 'el-icon-loading' : ''"
    v-observe-visibility="visibilityChanged"
  >{{link}}</el-button>
</template>

<script>
import { mapGetters, mapState, mapActions } from "vuex";
export default {
  name: "Link",
  props: ["identifier"],
  data() {
    return { link: "" };
  },
  // async created() {
  //   console.log("created");
  //   let myID = await this.getTopicDetails(this.id);
  //   console.log(myID);
  //   this.budg = myID.ccm2Id;
  // },
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
    async visibilityChanged(e) {
      if (!e) return console.log(e);
      let myID = await this.getTopicDetails(this.id);
      this.budg = myID.ccm2Id;
      //this.budg = this.topicDetails[this.id].ccm2Id;
      //console.log(this.topicDetails[this.id].ccm2Id);
    },
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