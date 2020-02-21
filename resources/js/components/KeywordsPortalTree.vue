<template>
  <div>
    KPT
    <div>
      <!-- <el-button @click="insertAll">INSERT ALL</el-button> -->
      <div class="h-container">
        <a class="h-btn h-btn-1" @click="findKeywords">Development</a>
        <a class="h-btn h-btn-2">Hover me</a>
        <a class="h-btn h-btn-3">Hover me</a>
        <a class="h-btn h-btn-4">Hover me</a>
        <a class="h-btn h-btn-5">Hover me</a>
      </div>
    </div>
  </div>
</template>

<script>
//import { mapMutations } from "vuex";
import oldkpt from "../../json/portal_keyword_tree.json";
import kpt from "../../json/new_portal_keyword_tree.json";
import { mapActions, mapState, mapMutations, mapGetters } from "vuex";
export default {
  name: "KeywordsPortalTree",
  data() {
    return {
      bigArray: []
    };
  },
  computed: {
    ...mapState(["keywords"])
  },
  created() {
    console.log({ kpt });
    this.keywordsAct();
  },
  async mounted() {
    await this.bigArrayFlat(kpt);
    console.log(kpt);
  },
  methods: {
    ...mapActions(["keywordsAct"]),
    findKeywords(e) {
      let text = e.target.text.toLowerCase().slice(0, 7);
      //console.log(text);
      console.log(text);
      console.log(
        this.keywords.filter(k => {
          let name = k.description.toLowerCase();
          return name.includes(text);
        })
      );
    },
    log(e) {
      console.log(e);
    },
    insertAll() {
      axios.post("keywords", this.bigArray);
    },
    bigArrayFlat(arr) {
      arr.forEach(item => {
        // console.log(item);
        this.bigArray.push({
          ccm2_Id: item.ccm2_Id,
          description: item.description,
          name: item.name ? item.name : null,
          parent: item.parent ? item.parent : null,
          children_ids: item.children.map(c => c.ccm2_Id).join(",")
        });
        if (item.children.length) this.bigArrayFlat(item.children);
      });
    },
    ...mapMutations(["setUpdateFlags"])
  }
};
</script>
<style  scoped>
.h-container {
  display: flex;
  justify-content: center;
  align-items: center;
  align-content: center;
  flex-wrap: wrap;
  width: 80vw;
  margin: 0 auto;
  min-height: 100vh;
}
.h-btn {
  cursor: pointer;
  flex: 1 1 auto;
  margin: 10px;
  padding: 30px;
  text-align: center;
  text-transform: uppercase;
  transition: 0.5s;
  background-size: 200% auto;
  color: white;
  /* text-shadow: 0px 0px 10px rgba(0,0,0,0.2);*/
  box-shadow: 0 0 20px #eee;
  border-radius: 10px;
}

/* Demo Stuff End -> */

/* <- Magic Stuff Start */

.h-btn:hover {
  background-position: right center; /* change the direction of the change here */
}

.h-btn-1 {
  background-image: linear-gradient(
    to right,
    #f6d365 0%,
    #fda085 51%,
    #f6d365 100%
  );
}

.h-btn-2 {
  background-image: linear-gradient(
    to right,
    #fbc2eb 0%,
    #a6c1ee 51%,
    #fbc2eb 100%
  );
}

.h-btn-3 {
  background-image: linear-gradient(
    to right,
    #84fab0 0%,
    #8fd3f4 51%,
    #84fab0 100%
  );
}

.h-btn-4 {
  background-image: linear-gradient(
    to right,
    #a1c4fd 0%,
    #c2e9fb 51%,
    #a1c4fd 100%
  );
}

.h-btn-5 {
  background-image: linear-gradient(
    to right,
    #ffecd2 0%,
    #fcb69f 51%,
    #ffecd2 100%
  );
}

/* Magic Stuff End -> */
</style>