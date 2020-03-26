<template>
  <el-row :gutter="20" v-load="load">
    <!-- {{callsFilterSearch}} -->
    <el-collapse
      v-model="activeNames"
      @change="handleChangeCollapse"
      style="position:sticky;top:0;z-index:100"
    >
      <el-collapse-item
        title="Programmes"
        name="1"
        style="max-height:400px;overflow:auto;position:sticky;top:0"
      >
        <el-card style="margin-bottom: 60px !important;">
          <el-checkbox-group v-model="programmesSelected" @change="getBigJson(meKW)">
            <!-- @change="log" -->
            <!-- :checked="true" -->
            <el-checkbox-button
              class="white-space-n"
              v-for="(item, index) in mainProgrammes"
              :label="item.description"
              :key="index"
            ></el-checkbox-button>
            <!--  -->
          </el-checkbox-group>
        </el-card>
        <div class="my-pagination card">
          <span class="demonstration">All combined</span>
          <el-pagination
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
            :current-page.sync="currentPage"
            :page-sizes="[25, 50 ,100, 200, 300, 400]"
            :page-size="25"
            layout="total, sizes, prev, pager, next, jumper"
            :total="total"
          ></el-pagination>
        </div>
      </el-collapse-item>
      <el-collapse-item title="Tags" name="2">
        <el-checkbox-group v-model="tagList">
          <el-checkbox :checked="true" v-for="(item, index) in tagsFlat" :key="index" :label="item"></el-checkbox>
        </el-checkbox-group>
      </el-collapse-item>
    </el-collapse>

    <el-table :data="callsFilterSearchChunck[currentPage-1]" style="width: 100%" border>
      <el-table-column prop="title" label="CALL TABLE" style="width: 100%">
        <template slot="header" slot-scope="scope">
          <el-input v-model="search" size="mini" placeholder="Type to search" />
        </template>

        <template slot-scope="scope">
          <div
            style="color:white;padding:5px;white-space:normal;text-align:center;backgroundColor:#9a9ae9"
          >{{scope.row.title}}</div>

          <div style="align-text:center">
            <el-table :data="[scope.row]" style="width :inherit" border>
              <el-table-column prop="identifier" label="Identifier" width="90"></el-table-column>
              <el-table-column prop="tags" label="tags">
                <template slot-scope="scope" v-if="scope.row.tags">
                  <transition name="el-zoom-in-top">
                    <el-alert
                      style="position:absolute;top:inherit;z-index:10000;margin-top:-20px"
                      v-if="showTagAdd == 'show-'+callID(scope) && success"
                      title="New tag added !"
                      type="success"
                      :closable="false"
                    ></el-alert>
                  </transition>
                  <el-badge
                    style="white-space:normal !important;height:inherit;margin:10px"
                    v-for="(item, i ) in scope.row.tags"
                    :key="i"
                    :type="typeTags[(i  % 5)]"
                    :value="i "
                    @click.native="toggleTag"
                  >
                    <el-button style="font-size:10px" size="mini">{{item}}</el-button>
                  </el-badge>
                  <el-badge
                    style="white-space:normal !important;height:inherit;margin:10px"
                    v-for="(item, i ) in newTags[callID(scope)]"
                    :key="i+123"
                    :type="typeTags[((scope.row.tags.length+i)  % 5)]"
                    :value="scope.row.tags.length+i"
                    @click.native="toggleTag"
                  >
                    <el-button
                      class="purple-button"
                      plain
                      style="font-size:10px"
                      size="mini"
                    >{{item}}</el-button>
                  </el-badge>
                  <transition name="slide-fade" mode="out-in">
                    <el-badge
                      style="margin:10px"
                      v-if="showTagAdd != 'show-'+callID(scope)"
                      value="+"
                      class="item"
                      @click.native="showTagAdd = 'show-'+callID(scope)"
                    >
                      <el-button @click="focusInput(callID(scope))" type="primary" size="mini">ADD</el-button>
                    </el-badge>
                    <el-input
                      style="max-width:30%;margin:10px"
                      placeholder="add Tag"
                      v-model="newTag"
                      :ref="callID(scope)+'-input'"
                      @blur="saveNewTag(callID(scope))"
                      @keyup.enter.native="saveNewTag(callID(scope))"
                      size="mini"
                      v-else
                    ></el-input>
                  </transition>
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
                    :class="{programmeIncluded : programmesSelected.includes(item)}"
                    :type="typeTags[(tag_i % 5)  ]"
                    style="white-space:normal !important;height:inherit"
                    effect="light"
                  >{{ item }}</el-tag>
                </template>
              </el-table-column>
              <el-table-column prop="url" label="Links">
                <template slot-scope="scope">
                  <el-button
                    type="primary"
                    plain
                    style="width:100%"
                    @click="goto('../calls/'+callID(scope).toLowerCase())"
                  >
                    SHOW
                    <!-- <router-link :to="`calls/${callID(scope).toLowerCase()}`">Show</router-link> -->
                  </el-button>
                  {{callLinksWP(scope.row.workProgrammepart)}}
                  <br />
                  <a
                    :href="callLinksPDF(scope.row.workProgrammepart)"
                  >{{callLinksPDF(scope.row.workProgrammepart)}}</a>
                </template>
              </el-table-column>
            </el-table>
          </div>
        </template>
      </el-table-column>
    </el-table>
  </el-row>
</template>

<script>
import { mapActions, mapGetters, mapState, mapMutations } from "vuex";
import Budget from "./Budget";

export default {
  props: ["programmToggle"],
  components: { Budget },
  data() {
    return {
      success: false,
      newTags: {},
      showTagAdd: true,
      newTag: "",
      search: "",
      activeNames: [], // ["1"]
      tagList: [],
      excluded: {},
      programmesSelected: [
        // "JRC direct actions",
        // "Excellent Science",
        // "Science with and for Society"
      ],
      callsChunck: [],
      currentPage: 0,
      currentPage1: 5,
      currentPage2: 5,
      currentPage3: 5,
      currentPage4: 4,
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
      calls: []
      //flags: []
    };
  },
  watch: {
    programmToggle(o, n) {
      this.getBigJson(this.meKW);
    }
  },
  async created() {
    await this.callProgram();
    this.load = true;
    //if (localStorage.calls && 1 == 2)
    //this.setMCalls(JSON.parse(localStorage.getItem("calls")));
    //return (this.calls = JSON.parse(localStorage.getItem("calls")));
    //else {
    this.meKW = await JSON.parse(localStorage.getItem("authUser")).keywords.map(
      a => a.description
    );

    this.getBigJson(this.meKW);
    //}
  },
  destroyed() {},
  mounted() {
    // this.$nextTick(() => {
    //   this.calls = Object.values(this.calls).map(c => {
    //     let i = c.identifier.toLowerCase();
    //     return c;
    //   });
    // });
  },
  updated() {
    this.setGroupedKeyWords(this.kWordsGrouped);
  },
  computed: {
    callsFilterSearchSelected() {
      if (!this.programmesSelected.length) return this.callsFilterSearch;
      return this.callsFilterSearch.filter(
        call =>
          _.intersection(
            this.programmesSelected,
            call.programmeDivision.map(pd => pd.description)
          ).length
      );
    },
    programmeDivisionArr() {
      try {
        return this.calls.map(c =>
          c.programmeDivision.map(pd => pd.description)
        ); //programmDivisionArray
      } catch (e) {
        return e;
      }
    },
    total() {
      return _.flatten(this.callsFilterSearchSelected).length;
    },
    callsFilterSearchChunck() {
      try {
        return _.chunk(this.callsFilterSearchSelected, 20);
      } catch (e) {
        return e;
      }
    },
    callsFilterSearch() {
      if (!this.calls || !this.calls.length) return [];
      let calls = this.calls.filter(a => {
        let pd = a.programmeDivision
          .map(a => {
            //console.log(a.description);
            return a.description;
          })
          .toString()
          .toLowerCase();
        return pd.includes(this.search.toLowerCase());
      });
      return calls;
      //calls = calls.filter(a =>
      // a.toLowerCase().includes(this.search.toLowerCase())
      //);
      //return _.chunk(calls, 20);
    },

    tagsFlat() {
      return Object.keys(_.invert({ ...this.tags.flat() }));
    },
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
    ...mapState([
      "topicDetails",
      "me",
      "meKeywords",
      "mCalls",
      "mainProgrammes"
    ])
  },

  methods: {
    callID(scope) {
      return scope.row.identifier.toLowerCase();
    },
    saveNewTag(identifier) {
      if (this.newTag) {
        if (!this.newTags[identifier]) this.newTags[identifier] = [this.newTag];
        else this.newTags[identifier].push(this.newTag);
        this.success = true;
      }
      console.log(this.newTags);
      setTimeout(() => {
        this.success = false;
        this.showTagAdd = false;
      }, 1000);
      this.saveTagAct({ identifier, newTag: this.newTag });
    },
    focusInput(identifier) {
      setTimeout(() => {
        console.log(this.$refs[identifier + "-input"].$refs.input.focus());
      }, 500);
    },
    toggleTag(tag) {
      tag = tag.target.textContent;
      if (this.tagList.includes(tag))
        this.tagList = this.tagList.filter(t => t != tag);
      else this.tagList.push(tag);
      return this.getBigJson(this.meKW);
    },
    handleChangeCollapse(val) {
      console.log(val);
    },
    log(isCheck, event) {
      this.excluded[event.target.value] = {
        active: isCheck ? true : false,
        name: event.target.value
      };
    },
    async visibilityChanged(e) {
      //if (!e) return console.log(e);
      console.log("in visibility change");
      let myID = await this.getTopicDetails(this.id);
      //this.budg = myID.ccm2Id;
      //this.budg = this.topicDetails[this.id].ccm2Id;
      //console.log(this.topicDetails[this.id].ccm2Id);
    },
    goto(url) {
      //alert(url);
      return this.$router.push({ path: url, query: { plan: "private" } });
    },
    handleSizeChange(val) {
      console.log(`${val} items per page`);
    },
    handleCurrentChange(val) {
      this.currentPage = val;
      console.log(`current page: ${val}`);
    },
    visibilityChanged(E) {
      console.log(E);
    },

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
    callLinksPDF(call) {
      try {
        return call.wp_document;
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
    ...mapActions(["getTopicDetails", "callProgram", "saveTagAct"]),

    async getBigJson(meKW) {
      //   this.load = true;
      try {
        let { data: bigJson } = await axios.get("big-json");

        this.load = false;
        //console.log(bigJson);
        bigJson = bigJson
          .filter(c => {
            //this.flags.push(c.flags);
            // this.callTitles.push(c.callTitle);

            //***************Toggle Button***********//

            // let pda = c.programmeDivision.map(pd => pd.description); //programmDivisionArray
            // let mpd = this.mainProgrammes.map(mp => mp.description); // mainProgrammesDescription
            // let iUSP = _.intersection(pda, this.programmesSelected); //intersection-user-selected-programme
            // //console.log({ programmesSelected });
            // let intersectionToggle = this.programmToggle
            //   ? ["hasIntersection"]
            //   : _.intersection(pda, mpd);

            //END***************Toggle Button***********//

            //***************Tags Intersection***********//
            let intersectionTag = _.intersection(c.tags, this.tagList);
            if (!this.tagList.length)
              intersectionTag = ["Perform only if not empty"];

            //END***************Tags Intersection***********//
            // console.log({ intersectionTag });
            return (
              c.status.abbreviation != "Closed" &&
              c.status.description != "Closed"
              // intersectionToggle.length &&
              // iUSP.length &&
              // intersectionTag.length
            );
          })
          .map(c => {
            this.indetifiers.push(c.identifier.toLowerCase());
            c.score = 0;
            this.keywords.push(c.keywords);

            this.tags.push(c.tags); //

            let intersection = _.intersection(c.keywords, meKW);

            if (intersection)
              this.keywords.push({
                keywords: c.flags,
                keywordsLength: intersection.length
              });
            return c;
          });
        // bigJson = bigJson.filter(function(a) {
        //   return a.score;
        // });
        //console.log({ indetifiers: this.indetifiers });

        localStorage.setItem("calls", JSON.stringify(bigJson));
        // this.calls = bigJson;
        this.calls = _.orderBy(bigJson, "score", "desc");
        // this.callsChunck = _.chunk(this.calls, 20);
        //  this.setMCalls(this.calls);
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
.white-space-n > span {
  white-space: normal !important;
  max-width: 500px;
  line-height: 20px;
  min-height: 100px;
}
.el-collapse-item__content {
  background-color: #b3b3b32a;
}
.slide-fade-enter-active {
  transition: all 0.1s;
}
.slide-fade-leave-active {
  transition: all 0.1s ease;
}
.slide-fade-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateX(5px);
  opacity: 0;
}
.slide-fade-enter
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateX(-10px);
  opacity: 0;
}
button.purple-button {
  background-color: #9a9ae980;
  color: #1818ff;
  border-color: #1818ff;
}
button.purple-button:hover {
  background-color: #1818ff !important;
  color: white !important;
  border: 1px solid;
  border-color: #ddddffe5 !important;
}
.el-table__empty-block {
  display: none;
}
.my-pagination {
  background-color: #f4f4f4;
  position: fixed;
  width: -webkit-fill-available;
  /* margin: 0 0px 0 15px; */
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
  padding: 6px;
  top: 384px !important;
  z-index: 100;
}
.programmeIncluded {
  font-weight: 1000;

  outline-style: outset;
}
</style>
