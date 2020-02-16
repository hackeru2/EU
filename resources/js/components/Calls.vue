<template>
  <el-row :gutter="20" v-load="load">
    <h2>Calls Page</h2>
    {{meKW}}
    <el-col :span="8" v-for="(o, index) in calls" :key="index" :id="o.identifier.toLowerCase()">
      <!-- :offset="index > 0 ? 2 : 0" -->

      <el-card :body-style="{ padding: '5px' }" :header="o.identifier.toLowerCase()">
        <div slot="header" class="clearfix">
          <el-button style="float: right; padding: 3px 0" type="text">Operation button</el-button>
          <!-- <div style="width:80%;margin: 0 auto"> -->
          <span>{{o.title}}</span>
          <span style="color:orange">{{o.identifier.toLowerCase()}}</span>

          <!-- </div> -->
        </div>
        <img
          src="https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png"
          class="image"
        />
        <div style="padding: 14px;">
          <div>
            <!-- budgetOverviewJSONItem : -->
            <!-- <el-tag v-for="(tag,i_tag ) in o.tags" :key="i_tag">{{tag}}</el-tag> -->
            <el-tag
              v-for="(kw,i_kw ) in o.keywords"
              :effect="meKW.includes(kw) ? 'dark' : 'light'"
              :key="i_kw"
            >{{kw}}</el-tag>
          </div>
          <span>Yummy hamburger</span>
          <div class="bottom clearfix">
            <!-- <time class="time">{{ currentDate }}</time> -->
            <span>
              <template>
                <el-alert class="score-style" :title="`score : ${o.score}`" type="success"></el-alert>
                <Budget
                  v-if="topicDetails"
                  :budget="topicDetails"
                  :id="o.identifier.toLowerCase()"
                />
              </template>
            </span>

            <el-button type="outline" class="button" style="background-color: #d4dcff;">
              <router-link :to="`calls/${o.identifier.toLowerCase()}`">Show</router-link>
            </el-button>
          </div>
        </div>
      </el-card>
    </el-col>
  </el-row>
</template>

<script>
import { mapActions, mapGetters, mapState, mapMutations } from "vuex";
import Budget from "./Budget";

export default {
  components: { Budget },
  data() {
    return {
      meKW: [],
      intersectionOptions: {
        root: null,
        rootMargin: "0px 0px 0px 0px",
        thresholds: [0]
      },
      load: false,
      callTitles: [],
      keywords: [],
      tags: [],
      flagsLength: [],
      keywordsLength: [],
      indetifiers: [],
      budgets: {},

      currentDate: new Date(),
      calls: [],
      flags: []
    };
  },
  async created() {
    if (localStorage.calls && 1 == 2)
      return (this.calls = JSON.parse(localStorage.getItem("calls")));
    else {
      this.meKW = await JSON.parse(
        localStorage.getItem("authUser")
      ).keywords.map(a => a.description);

      this.getBigJson(this.meKW);
    }
  },

  mounted() {
    this.$nextTick(() => {
      this.calls = Object.values(this.calls).map(c => {
        let i = c.identifier.toLowerCase();
        return c;
      });
    });
  },
  updated() {
    this.setGroupedKeyWords(this.kWordsGrouped);
  },
  computed: {
    ...mapGetters(["authUser"]),
    flagsLengthFilterd() {
      return this.flagsLength.filter(fl => fl.flagsLength);
    },
    meFlags() {
      try {
        return this.me.flags.map(f => f.abbreviation);
      } catch (e) {}
    },
    callTitleUnique() {
      return this.callTitles.filter(
        (value, index, self) => self.indexOf(value) === index
      );
    },

    // uniqueKeywords() {
    //   try {
    //     return [].concat
    //       .apply([], this.keywords)
    //       .filter(a => a)
    //       .map(a => a.split(","))
    //       .flat()
    //       .map(a => a.trim());
    //   } catch ($e) {
    //     throw $e;
    //   }
    // },
    uniqueflags2() {
      return this.uniqueflags.filter(
        (value, index, self) => self.indexOf(value) === index
      );
    },
    uniqueflags() {
      try {
        return [].concat
          .apply([], this.flags)
          .filter(a => a)
          .map(a => a.split(","))
          .flat()
          .map(a => a.trim());
      } catch ($e) {
        throw $e;
      }
    },
    // kWordsGrouped() {
    //   try {
    //     return _.groupBy(
    //       this.uniqueKeywords.map(keyWords => {
    //         return { title: keyWords.split(" ")[0], keyWords };
    //       }),
    //       "title"
    //     );
    //   } catch ($e) {
    //     throw $e;
    //   }
    // },
    // KeywordsGroupedLength() {
    //   try {
    //     return Object.keys(this.kWordsGrouped).length;
    //   } catch (error) {}
    // },
    topicDetailsValues() {
      try {
        return this.topicDetails;
      } catch (error) {}
    },
    ...mapState(["topicDetails", "me", "meKeywords"])
  },

  methods: {
    getTD(t_details) {
      console.log(t_details);
    },
    ...mapMutations(["setGroupedKeyWords"]),
    ...mapActions(["getTopicDetails"]),

    async getBigJson(meKW) {
      this.load = true;
      try {
        let { data: bigJson } = await axios.get("big-json");

        this.load = false;
        //console.log(bigJson);
        bigJson = bigJson
          .filter(c => {
            this.flags.push(c.flags);
            // this.callTitles.push(c.callTitle);
            return (
              c.status.abbreviation != "Closed" &&
              c.status.description != "Closed"
            );
          })
          .map(c => {
            console.log({ c });
            this.indetifiers.push(c.identifier.toLowerCase());
            c.score = 0;
            this.keywords.push(c.keywords);

            this.tags.push(c.tags); //
            //if (c.tags) if (c.tags.includes("integration")) c.score += 5;
            //let intersection = _.intersection(c.flags, this.meFlags);
            let intersection = _.intersection(c.keywords, meKW);
            console.log({ c_keywords: c.keywords, meKW });
            // if (intersection)
            //   this.flagsLength.push({
            //     flags: c.flags,
            //     flagsLength: intersection.length
            //   });
            if (intersection)
              this.keywords.push({
                keywords: c.flags,
                keywordsLength: intersection.length
              });
            try {
              c.score = intersection.length * 5;
            } catch (error) {
              c.score = 0;
            }
            return c;
          });
        // bigJson = bigJson.filter(function(a) {
        //   return a.score;
        // });
        console.log({ indetifiers: this.indetifiers });

        localStorage.setItem("calls", JSON.stringify(bigJson));
        this.calls = _.orderBy(bigJson, "score", "desc");
      } catch (error) {
        window.location.reload();
      }
    }
  }
};
</script>

<style>
.time {
  font-size: 13px;
  color: #999;
}

.bottom {
  margin-top: 13px;
  line-height: 12px;
}

.button {
  padding: 0;
  float: right;
}

.image {
  width: 100%;
  max-height: 200px;
  display: block;
}

.clearfix:before,
.clearfix:after {
  display: table;
  content: "";
}

.clearfix:after {
  clear: both;
}
.el-header,
.el-footer {
  background-color: #b3c0d1;
  color: #333;
  text-align: center;
  line-height: 60px;
}

.el-aside {
  background-color: #d3dce6;
  color: #333;
  text-align: center;
  line-height: 200px;
}

.el-main {
  background-color: #e9eef3;
  color: #333;
  text-align: center;
  margin: 20px;
  /* line-height: 160px; */
}

body > .el-container {
  margin-bottom: 40px;
}

.el-container:nth-child(5) .el-aside,
.el-container:nth-child(6) .el-aside {
  line-height: 260px;
}

.el-container:nth-child(7) .el-aside {
  line-height: 320px;
}
.score-style {
  width: 120px;
  float: left;
}
</style>
