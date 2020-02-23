<template>
  <div>
    <Banner :title="infos.title" />
    <el-container v-loading="loading">
      <el-main>
        <CallTable />
        <el-card
          v-for="(info,  title , index) in infos"
          :body-style="{textAlign:'left'}"
          :key="index"
          style="margin-top:5px"
        >
          <div slot="header" class="clearfix">
            <h3>{{title}} {{typeof info}}</h3>
          </div>
          <div v-if="typeof info == 'object'">
            <div v-for="(infoItem, info_i) in info" :key="info_i">
              <b>{{info_i}}</b>
              .
              <div v-html="setHtml(infoItem)"></div>
            </div>
          </div>
          <div
            v-else-if="
          title !='supportInfo' && title !='sepTemplate'  &&  title !='description' && title != 'conditions' "
          >{{ info}}</div>
          <div v-else>
            <span v-html="info"></span>
          </div>
        </el-card>
      </el-main>
    </el-container>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import Banner from "./calls/Banner";
import CallTable from "./calls/CallTable";
export default {
  components: { CallTable, Banner },
  data() {
    return {
      loading: false,
      infos: []
    };
  },
  async created() {
    let id = this.$route;

    if (!this.topicDetails[this.$route.params.id]) {
      this.loading = true;
      await this.getTopicDetails(this.$route.params.id);
      this.infos = this.topicDetails[this.$route.params.id];
      console.log(this.infos);
      this.loading = false;
    } else this.infos = this.topicDetails[this.$route.params.id];
    //console.log(this.topicDetails);
  },
  mounted() {
    console.log("mounted");
    console.log(this.topicDetails[this.$route.params.id]);
    console.log("mounted-end");
  },
  methods: {
    ...mapActions(["getTopicDetails"]),
    setHtml(datas) {
      let htmlArr = "<div>";
      if (typeof datas == "object") {
        for (let data in datas) {
          htmlArr = "<span style='color:blue'>" + data + " : </span>";
          htmlArr += `${this.setHtml(datas[data])}</div>`;
        }
        htmlArr += `</div>`;
        return htmlArr;
      } else return datas;
    }
  },
  computed: {
    ...mapState(["topicDetails"])
  }
};
</script>

<style>
.single-page-header .single-page-header-inner {
  display: flex;
}
</style>