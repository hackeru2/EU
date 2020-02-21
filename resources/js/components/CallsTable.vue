<template>
  <el-row :gutter="20" v-load="load">
    <el-table :data="mCalls" style="width: 100%" border>
      <el-table-column prop="identifier" label="header" width="90" style="font-size:6px">
        <template slot-scope="header">asdas</template>
      </el-table-column>
      <el-table-column prop="identifier" label="Identifier" width="90" style="font-size:6px"></el-table-column>
      <el-table-column prop="tags" label="tags">
        <template slot-scope="scope" v-if="scope.row.tags">
          <el-badge
            style="white-space:normal !important;height:inherit;margin:10px"
            v-for="(item, i ) in scope.row.tags"
            :key="i"
            :type="typeTags[(i  % 5)]"
            :value="i "
          >
            <el-button style="font-size:10px" size="mini">{{item}}</el-button>
          </el-badge>
        </template>
      </el-table-column>
      <el-table-column prop="keywords" label="Keywords">
        <template slot-scope="scope">
          <el-tag
            v-for="(item , tag_i) in scope.row.keywords"
            :key="tag_i"
            :type="typeTags[(tag_i % 5)  ]"
            style="white-space:normal !important;height:inherit"
            effect="light"
          >{{ item }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="programmeDivision" label="programmeDivision">
        <template slot-scope="scope">
          <el-tag
            v-for="(item , tag_i) in mapPD(scope.row.programmeDivision)"
            :key="tag_i"
            :type="typeTags[(tag_i % 5)  ]"
            style="white-space:normal !important;height:inherit"
            effect="light"
          >{{ item }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="url" label="Links">
        <template slot-scope="scope">{{callLinksWP(scope.row.workProgrammepart)}}</template>
      </el-table-column>
    </el-table>
  </el-row>
</template>

<script>
import { mapActions, mapGetters, mapState, mapMutations } from "vuex";
import Budget from "./Budget";

export default {
  components: { Budget },
  data() {
    return {
      typeTags: ["primary", "success", "info", "warning", "danger"],
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
    if (localStorage.calls)
      this.setMCalls(JSON.parse(localStorage.getItem("calls")));
    //return (this.calls = JSON.parse(localStorage.getItem("calls")));
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
    // topicDetailsValues() {
    //   try {
    //     return this.topicDetails;
    //   } catch (error) {}
    // },
    ...mapState(["topicDetails", "me", "meKeywords", "mCalls"])
  },

  methods: {
    arraySpanMethod({ row, column, rowIndex, columnIndex }) {
      if (rowIndex % 2 === 0) {
        if (columnIndex === 0) {
          return [1, 6];
        } else if (columnIndex === 1) {
          return [0, 0];
        }
      }
    },
    callLinksWP(call) {
      try {
        return call.wp_website;
      } catch (error) {}
    },
    mapPD(pd) {
      if (!pd) return [];
      return pd.map(pd => pd.description);
    },
    // getTD(t_details) {
    //   console.log(t_details);
    // },
    ...mapMutations(["setGroupedKeyWords", "setMCalls"]),
    //...mapActions(["getTopicDetails"]),

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
            //console.log({ c_keywords: c.keywords, meKW });
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
        //console.log({ indetifiers: this.indetifiers });

        localStorage.setItem("calls", JSON.stringify(bigJson));
        this.calls = _.orderBy(bigJson, "score", "desc");
      } catch (error) {
        //window.location.reload();
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
