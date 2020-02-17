<template>
  <div>
    KPT
    <div v-loading="loading">
      <!-- <el-button @click="insertAll">INSERT ALL</el-button> -->

      <div class="h-container" v-for="(items, index) in topcisC5" :key="index">
        <a
          :class="'h-btn h-btn-'+(i+1)+' '+ (item.active ? 'active-class' : '') "
          v-for="(item, i) in items"
          :key="i"
          @click="findKeywords"
        >{{item.name}}</a>
        <!-- <a class="h-btn h-btn-1" @click="findKeywords">{{item}}</a>
        <a class="h-btn h-btn-2" @click="findKeywords">{{item.name}}</a>
        <a class="h-btn h-btn-3">{{item.name}}</a>
        <a class="h-btn h-btn-4">{{item.name}}</a>
        <a class="h-btn h-btn-5">{{item.name}}</a>-->
      </div>
    </div>
  </div>
</template>

<script>
//import { mapMutations } from "vuex";
//import kpt from "../../json/portal_keyword_tree.json";
import { mapActions, mapState, mapMutations, mapGetters } from "vuex";
export default {
  name: "Topics",
  props: ["topicActive"],
  data() {
    return {
      loading: false,
      bigArray: []
      // topics: [
      //   { name: "Development", active: false },
      //   { name: "Services", active: false },
      //   { name: "Security", active: false }, //Borders and security
      //   { name: "Budget", active: false },
      //   { name: "Business and industry", active: false },
      //   { name: "Climate action", active: false },
      //   { name: "Competition", active: false },
      //   { name: "Consumers", active: false },
      //   { name: "Culture and media", active: false },
      //   // { name: "Customs", active: false },
      //   { name: "Digital economy and society", active: false },
      //   { name: "Economy, finance and the euro", active: false },
      //   { name: "Education and training", active: false },
      //   { name: "Employment and social affairs", active: false },
      //   { name: "Energy", active: false },
      //   { name: "Environment", active: false },
      //   { name: "Food safety", active: false },
      //   { name: "Foreign affairs and security policy", active: false },
      //   { name: "Fraud prevention", active: false },
      //   { name: "Home affairs", active: false },
      //   { name: "Humanitarian aid and civil protection", active: false },
      //   { name: "Institutional affairs", active: false },
      //   { name: "International cooperation and development", active: false },
      //   { name: "Justice and fundamental rights", active: false },
      //   { name: "Maritime affairs and fisheries", active: false },
      //   { name: "Migration and asylum", active: false },
      //   { name: "Public health", active: false },
      //   { name: "Regional policy", active: false },
      //   { name: "Research and innovation", active: false },
      //   { name: "Single market", active: false },
      //   { name: "Society", active: false },
      //   { name: "Sport", active: false },
      //   { name: "Statistics", active: false },
      //   { name: "Taxation", active: false },
      //   { name: "Trade", active: false },
      //   { name: "Transport", active: false },
      //   { name: "Youth", active: false }
      // ]
    };
  },
  watch: {
    topicActive(newVal, oldVal) {
      return this.setTopicActive(newVal);
      // console.log(
      //   (this.topics.find(a => a.name == this.treeName).active = newVal)
      // );
      // alert(newVal);
    }
  },
  computed: {
    ...mapGetters(["authUser"]),
    topcisC5() {
      try {
        return _.chunk(this.topics, 5);
      } catch (e) {
        console.error(e);
      }
    },
    ...mapState(["keywords", "treeName", "topics"])
  },
  async created() {
    //console.log({ kpt });
    this.loading = true;
    await this.keywordsAct();
    this.loading = false;
  },
  //async mounted() {
  //await this.bigArrayFlat(kpt);
  // console.log(kpt);
  //  setTimeout(() => {
  //  this.authUserTopics();
  // }, 500);
  //},
  methods: {
    authUserTopics() {
      console.log(
        this.authUser.profile.topics.split(",").forEach(element => {})
      );
    },
    ...mapActions(["keywordsAct"]),

    async findKeywords(e) {
      let text = e.target.text;
      if (this.treeName == text) return console.log("already active");
      this.setTreeName(text);
      text = text
        .trim()
        .replace("and", "")
        .split(" ")[0]
        .toLowerCase()
        .slice(0, 7);
      //console.log(text);
      console.log(text);

      let tree = await this.filterKeywords(text);
      // if (tree.length < 1)
      //   tree = await this.filterKeywords(
      //     text.slice(7, 14)
      //   );
      this.setTree(tree);
    },
    filterKeywords(text) {
      return this.keywords
        .filter(k => {
          let name = k.name
            ? k.name.toLowerCase()
            : k.description.toLowerCase();
          return name.includes(text);
        })
        .map(t => {
          t.id = t.ccm2_Id;
          if (t.children_ids) {
            t.children = t.children_ids.split(",").map(ccm2_Id => {
              let kw = this.keywords.find(a => a.ccm2_Id == ccm2_Id);
              let name = kw.name;
              return {
                ccm2_Id,
                id: Number(ccm2_Id),
                name,
                description: kw.description,
                label: name,
                children: []
              };
            });
          } else t.children = null;
          return t;
        });
    },
    log(e) {
      console.log(e);
    },
    insertAll() {
      //axios.post("keywords", this.bigArray);
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
    ...mapMutations([
      "setUpdateFlags",
      "setTree",
      "setTreeName",
      "setTopicActive"
    ])
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
  width: 50vw;
  max-width: 100%;
  margin: 0 auto;
  /* min-height: 100vh; */
}
.h-btn {
  font-size: 0.9em;
  cursor: pointer;
  flex: 1 1 auto;
  margin: 10px;
  padding: 10px;
  text-align: center;
  text-transform: uppercase;
  transition: 0.5s;
  background-size: 200% auto;
  color: white;

  /* text-shadow: 0px 0px 10px rgba(0,0,0,0.2);*/
  box-shadow: 0 0 20px rgb(168, 168, 168);

  border-radius: 10px;
  outline: #a6c1ee;
  outline-width: 2px;
}

/* Demo Stuff End -> */

/* <- Magic Stuff Start */

.h-btn:hover {
  border: 1px solid rgb(130, 184, 184);
  text-shadow: #c2e9fb;
  color: white;
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
.h-btn:active {
  transform: translateY(1px);
  filter: saturate(150%);
  color: white;
  font-weight: 200;
}
.active-class {
  border: 7px solid #ffffff78;
  font-weight: 1000;
  box-shadow: 0 0 20px rgb(109, 109, 109);
}
/* Magic Stuff End -> */
</style>