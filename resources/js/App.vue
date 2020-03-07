<template>
  <el-container :style="elContainerStyle">
    <Slide class="hidden-md-and-up">
      <router-link to="/keywords">keywords</router-link>
      <router-link to="/calls">Calls</router-link>
      <router-link to="/calls-table">Table</router-link>
      <router-link to="/profile">Profile</router-link>
    </Slide>

    <el-container>
      <el-header style="text-align: right; font-size: 12px">
        <!-- <span class="title">{{ fit }}</span> -->

        <img
          style="background-color:gray;height:60px;padding:2px;margin-right:20px;margin-left:40px;float:left"
          :src="url"
        />

        <div style="float:left" class="hidden-sm-and-down">
          <router-link to="/keywords">keywords</router-link>
          <el-divider direction="vertical"></el-divider>

          <router-link to="/calls">Calls</router-link>
          <el-divider direction="vertical"></el-divider>
          <router-link to="/calls-table">Table</router-link>
          <el-divider direction="vertical"></el-divider>
          <router-link to="/profile">Profile</router-link>
        </div>
        <el-dropdown style="cursor:pointer">
          <i class="el-icon-setting" style="margin-right: 15px"></i>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item>
              <el-tooltip
                :content="'Show calls with obsolete programms: ' + programmToggle"
                placement="top"
              >
                <el-switch
                  v-model="programmToggle"
                  active-color="#13ce66"
                  inactive-color="#ff4949"
                  :active-value="true"
                  :inactive-value="false"
                ></el-switch>
              </el-tooltip>
            </el-dropdown-item>
            <el-dropdown-item>Add</el-dropdown-item>
            <el-dropdown-item>Delete</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
        <span>{{authUser.name}}</span>
        <!-- {{isCollapse}} -->
      </el-header>
      <el-main>
        <router-view :programmToggle="programmToggle"></router-view>
      </el-main>
    </el-container>
  </el-container>
</template>

<style>
.el-header {
  background-color: #b3c0d1;
  color: #333;
  line-height: 60px;
}

.el-aside {
  color: #333;
}
</style>

<script>
import { mapState, mapGetters, mapActions } from "vuex";
import { Slide } from "vue-burger-menu"; // import the CSS transitions you wish to use, in this case we are using `Slide`

export default {
  data() {
    const item = {
      date: "2016-05-02",
      name: "Tom",
      address: "No. 189, Grove St, Los Angeles"
    };
    return {
      programmToggle: false,
      isCollapse: false,
      tableData: Array(20).fill(item),
      fits: ["fill"],
      url:
        "https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg"
    };
  },
  beforeCreate() {
    this.$store.dispatch("meAct");
  },
  components: { Slide },
  created() {},
  computed: {
    elContainerStyle() {
      if (this.$route.name == "ProgrammeTags") {
        return "overflow-y:hidden";
      } else {
        return "height:90vh";
      }
    },
    ...mapState(["meKeywords"]),
    ...mapGetters(["authUser"])
  },
  methods: {
    ...mapActions(["meAct"]),
    handleOpen(key, keyPath) {
      console.log(key, keyPath);
    },
    handleClose(key, keyPath) {
      console.log(key, keyPath);
    }
  }
};
</script>
<style>
.el-main {
  margin: 0;
  padding: 0 10px 0 10px;
}
.el-avatar {
  width: 88px !important;
}
.el-menu-item-group__title {
  padding: 0 !important;
}

.el-menu-item.is-active:hover {
  text-shadow: 1px 1px rgb(19, 18, 18);
  color: white;
  font-weight: 700;
  height: 50px;
}
.el-menu-vertical-demo:not(.el-menu--collapse) {
  width: 200px;
  min-height: 400px;
}
.bm-burger-button {
  position: absolute;
  width: 36px;
  height: 30px;
  left: 12px;
  top: 16px;
  cursor: pointer;
}
</style>