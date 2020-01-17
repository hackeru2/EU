<template>
  <el-drawer
    size="50%"
    :show-close="false"
    title="I am the title"
    :visible.sync="drawer"
    direction="rtl"
    :before-close="handleClose"
  >
    <el-row :gutter="20" style="padding:10px">
      <el-col v-for="(col, colTitle, index) in list" :key="index" :span="8">
        <el-collapse v-model="activeNames[colTitle]" @change="handleChange">
          <el-collapse-item
            v-for="(experties , title , index) in col"
            :key="index"
            :title="title"
            :name="index"
          >
            <div style="margin: 20px">
              <el-checkbox-group v-model="obj[title]" size="mini">
                <el-checkbox v-for="(expertie, index) in experties" :key="index" :label="expertie"></el-checkbox>
              </el-checkbox-group>
            </div>
          </el-collapse-item>
        </el-collapse>
      </el-col>
    </el-row>
    <div style="position:absolute;top:15px;right:30px">
      <!-- <i class="el-icon-check" type="primary" @click="$refs.drawer.closeDrawer()" :loading="true"></i> -->
      <el-button-group>
        <el-button type="primary" @click="saveOrganizationAct(obj)" icon="el-icon-check"></el-button>
        <el-button type="primary" @click="$refs.drawer.closeDrawer()" icon="el-icon-close"></el-button>
      </el-button-group>
    </div>
  </el-drawer>
</template>

<script>
import list from "../../json/organization_expertise.json";
import { mapActions, mapMutations, mapGetters } from "vuex";
// saveOrganization(){
//           this.saveOrganizationAct();
export default {
  props: ["drawer"],
  data() {
    let obj = {};
    let keys = [
      ...Object.keys(list.column1),
      ...Object.keys(list.column2),
      ...Object.keys(list.column3)
    ];
    keys.forEach(element => {
      obj[element] = [];
    });
    // });
    return {
      obj,
      checked1: true,
      checked2: false,
      checked3: false,
      checked4: true,
      checkboxGroup1: [],
      checkboxGroup2: [],
      list: {},
      activeNames: {
        column1: [],
        column2: [],
        column3: []
      },
      direction: "ttb"
    };
  },
  computed: mapGetters(["getExperties"]),
  mounted() {
    this.list = list;
    let _this = this;
  },
  watch: {
    drawer(nv) {
      if (!nv) return;
      for (let exp in this.getExperties) {
        setTimeout(() => {
          //console.log(this.$refs[exp][0].$el), 1500;
        });
        this.obj[exp] = this.getExperties[exp];
        //console.log({ OE: Object.entries(this.list.column1) });
        for (let num = 1; num <= 3; num++) {
          let columnActive = Object.entries(this.list["column" + num]);
          columnActive.forEach((a, index) => {
            if (this.obj[a[0]].length)
              this.activeNames["column" + num].push(index);
          });
        }
      }
    }
  },
  methods: {
    ...mapActions(["saveOrganizationAct"]),
    handleChange(val) {
      console.log(val);
    },
    handleClose(done) {
      console.log("close");
      this.$emit("close");
      done();
      // this.$confirm('Are you sure you want to close this?')
      //   .then(_ => {
      //     done();
      //   })
      //   .catch(_ => {});
    }
  }
};
</script>
<style  >
*:focus {
  outline: none;
}
.el-avatar,
.el-drawer {
  -webkit-box-sizing: border-box;
  overflow: AUTO;
  overflow-x: hidden;
  overflow-y: auto;
}
</style>
    
</style>