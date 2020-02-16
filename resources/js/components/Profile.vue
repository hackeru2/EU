<template>
  <el-row :gutter="20">
    <el-col :span="16" style="text-align:left">
      <el-form
        :model="ruleForm"
        :rules="rules"
        ref="ruleForm"
        label-width="120px"
        class="demo-ruleForm"
      >
        <el-form-item label="Activity name" prop="name">
          <el-input v-model="ruleForm.name"></el-input>
        </el-form-item>
        <el-form-item label="Activity name" prop="name">
          <!-- <Carousel @cities="updateTopics" /> -->
          <Topics :topicActive="topicActive" />
        </el-form-item>
        <el-form-item>
          <Flags />
        </el-form-item>
        <el-form-item label="Activity zone" prop="region">
          <el-select v-model="ruleForm.region" placeholder="Activity zone">
            <el-option label="Zone one" value="shanghai"></el-option>
            <el-option label="Zone two" value="beijing"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="Activity time" required>
          <el-col :span="11">
            <el-form-item prop="date1">
              <el-date-picker
                type="date"
                placeholder="Pick a date"
                v-model="ruleForm.date1"
                style="width: 100%;"
              ></el-date-picker>
            </el-form-item>
          </el-col>
          <el-col class="line" :span="2">-</el-col>
          <el-col :span="11">
            <el-form-item prop="date2">
              <el-time-picker
                placeholder="Pick a time"
                v-model="ruleForm.date2"
                style="width: 100%;"
              ></el-time-picker>
            </el-form-item>
          </el-col>
        </el-form-item>
        <el-form-item label="Instant delivery" prop="delivery">
          <el-switch v-model="ruleForm.delivery"></el-switch>
        </el-form-item>
        <el-form-item label="Activity type" prop="type">
          <el-checkbox-group v-model="ruleForm.type">
            <el-checkbox label="Online activities" name="type"></el-checkbox>
            <el-checkbox label="Promotion activities" name="type"></el-checkbox>
            <el-checkbox label="Offline activities" name="type"></el-checkbox>
            <el-checkbox label="Simple brand exposure" name="type"></el-checkbox>
          </el-checkbox-group>
        </el-form-item>
        <el-form-item label="Resources" prop="resource">
          <el-radio-group v-model="ruleForm.resource">
            <el-radio label="Sponsorship"></el-radio>
            <el-radio label="Venue"></el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="Description" prop="desc">
          <el-input type="textarea" v-model="ruleForm.desc"></el-input>
        </el-form-item>
        <el-form-item>
          <span>Organization Expertise:</span>
          <el-button @click="drawer = true" type="primary" style="margin-left: 16px;"></el-button>
          <Oranization @close="drawer=false" :drawer="drawer" />
        </el-form-item>
        <el-form-item label="State" required>
          <el-autocomplete
            style="width:100%"
            v-model="country"
            :fetch-suggestions="querySearch"
            placeholder="Please Input"
            @select="handleSelect"
          ></el-autocomplete>
        </el-form-item>
        <el-form-item>
          <el-autocomplete
            style="width:100%"
            v-model="SME"
            :fetch-suggestions="querySMESearch"
            placeholder="SME"
            @select="handleSelect"
          ></el-autocomplete>
        </el-form-item>
        <el-form-item>
          {{meKeywords.map(a=>a.description)}}
          <el-button type="primary" @click="submitForm('ruleForm')">Create</el-button>
          <el-button @click="resetForm('ruleForm')">Reset</el-button>
        </el-form-item>
      </el-form>
    </el-col>
    <el-col :span="8">
      <Tree @isTopicActive="(bool) => topicActive = bool" />
    </el-col>
  </el-row>
</template>

<script>
import countries from "../../json/countries.json";
import Flags from "./Flags";
import Tree from "./Tree";
import Topics from "./Topics";
import smeJson from "../../json/sme.json";
import { mapGetters, mapActions, mapState, mapMutations } from "vuex";
import Oranization from "./Organization";
import Carousel from "./Carousel";
export default {
  components: { Oranization, Carousel, Flags, Topics },
  data() {
    return {
      topicActive: "",
      ruleForm: {
        name: "",
        region: "",
        date1: "",
        date2: "",
        delivery: false,
        type: [],
        resource: "",
        desc: ""
      },
      rules: {
        name: [
          {
            required: true,
            message: "Please input Activity name",
            trigger: "blur"
          },
          {
            min: 3,
            max: 5,
            message: "Length should be 3 to 5",
            trigger: "blur"
          }
        ],
        region: [
          {
            required: true,
            message: "Please select Activity zone",
            trigger: "change"
          }
        ],
        date1: [
          {
            type: "date",
            required: true,
            message: "Please pick a date",
            trigger: "change"
          }
        ],
        date2: [
          {
            type: "date",
            required: true,
            message: "Please pick a time",
            trigger: "change"
          }
        ],
        type: [
          {
            type: "array",
            required: true,
            message: "Please select at least one activity type",
            trigger: "change"
          }
        ],
        resource: [
          {
            required: true,
            message: "Please select activity resource",
            trigger: "change"
          }
        ],
        desc: [
          {
            required: true,
            message: "Please input a description",
            trigger: "blur"
          }
        ]
      },
      drawer: false,
      SME: "",
      country: "",
      // t_opics: [],
      smeLinks: [],
      links: [],
      state1: "",
      flags: [],
      cities: []
    };
  },
  computed: {
    ...mapState(["me", "updateFlags", "topics", "meKeywords"]),
    ...mapGetters(["authUser"])
  },
  methods: {
    // updateTopics(e) {
    //   console.log(e);
    //   this.topics = e;
    // },
    // updateFlags(e) {
    //   console.log(e);
    //   this.flags = e;
    // },
    async submitForm(formName) {
      let topicsActive = this.topics.filter(t => t.active);
      console.log(topicsActive);

      // .keywords.map(k => k.ccm2_Id.join(","))
      // return console.log(_.flattenDeep(topicsActive));
      let topics = topicsActive.map(t => t.name).join(",");
      if (topics.length == 1) topics = topics[0];
      let { data: userPofile } = await axios.put(`user/${this.authUser.id}`, {
        topics,
        flags: this.updateFlags,
        // keywords: Object.values(
        //   _.flattenDeep(topicsActive.map(t => t.keywords))
        // )
        keywords: this.meKeywords.map(k => k.ccm2_Id).join(","),
        country: this.country,
        description: this.ruleForm.desc
      });
      console.log({ userPofile });
      this.setMe(userPofile);
      this.$refs[formName].validate(valid => {
        if (valid) {
          alert("submit!");
        } else {
          console.log("form has errors. still submitting");
          //console.log({ state1 });
          return false;
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
    ...mapActions(["saveProfileAct", "meAct"]),
    ...mapMutations(["setMe"]),

    saveProfile() {
      this.saveProfileAct();
    },
    createFilter(queryString) {
      console.log(queryString);
      return link => {
        return (
          link.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0
        );
      };
    },
    handleSelect(item) {
      console.log(item);
    },
    loadAll() {
      return countries;
    },
    loadAllSME() {
      return smeJson;
    },
    querySMESearch(queryString, cb) {
      console.log({ queryString });
      var links = this.smeLinks;
      var results = queryString
        ? links.filter(this.createFilter(queryString))
        : links;
      // call callback function to return suggestions
      cb(results);
    },
    querySearch(queryString, cb) {
      console.log({ queryString });
      var links = this.links;
      var results = queryString
        ? links.filter(this.createFilter(queryString))
        : links;
      // call callback function to return suggestions
      cb(results);
    }
  },

  mounted() {
    console.log("Component mounted.");
    this.links = this.loadAll();
    this.smeLinks = this.loadAllSME();
  }
};
</script>
 <style  >
.el-collapse-item__header {
  padding-left: 20px;
}

.el-tree-node__label {
  /* color: blue; */
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  /* max-width: 502px; */
}
</style>