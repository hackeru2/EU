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
          <el-divider direction="vertical"></el-divider>
          <!-- <router-link to="/programme-tags">Programme tags</router-link> -->
          <!-- <el-divider direction="vertical"></el-divider> -->
          <router-link to="/new-profile">New Profile</router-link>
          <el-divider direction="vertical"></el-divider>
          <el-dropdown @command="SubjectDropdownChange">
            <span class="el-dropdown-link">
              <router-link to="/programme-tags">Programme</router-link>
              <i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown" :disabled="$route.name=='Subjects'">
              <router-link to="/programme-tags">
                <el-dropdown-item command="Security">Security</el-dropdown-item>
              </router-link>
              <router-link to="/programme-tags-new">
                <el-dropdown-item command="New1">New 1</el-dropdown-item>
              </router-link>
              <el-dropdown-item disabled>New 2</el-dropdown-item>
              <el-dropdown-item disabled>New 3</el-dropdown-item>
              <router-link to="/subjects">
                <el-dropdown-item divided command="Edit">Edit</el-dropdown-item>
              </router-link>
            </el-dropdown-menu>
          </el-dropdown>
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
        <transition name="slide-fade" mode="out-in">
          <router-view :programmToggle="programmToggle"></router-view>
        </transition>
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
import { mapState, mapMutations, mapGetters, mapActions } from "vuex";
import { Slide } from "vue-burger-menu"; // import the CSS transitions you wish to use, in this case we are using `Slide`

export default {
  props: ["me"],
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
  computed: {
    elContainerStyle() {
      if (this.$route.name == "ProgrammeTags") {
        return "overflow-y:hidden";
      } else {
        return "height:95vh";
      }
    },
    ...mapState(["meKeywords"]),
    ...mapGetters(["authUser"])
  },
  methods: {
    ...mapMutations(["setSubjectTitle"]),
    SubjectDropdownChange(subjectHeader) {
      if (subjectHeader == "Edit") return this.$message("Edit Subjects");
      this.$message("Change subject to  " + subjectHeader);
      this.setSubjectTitle(subjectHeader);
    },
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

.el-dropdown-link {
  font-size: 12px !important;
  cursor: pointer;
  color: #3490dc;
}
.el-dropdown-link:hover {
  font-size: 12px !important;
  cursor: pointer;
  color: #409eff;
}
.el-icon-arrow-down {
  font-size: 12px;
}
.fade-enter-active {
  transition: opacity 0.25s ease;
}

.fade-leave {
}

.fade-leave-active {
  transition: opacity 0.25s ease;
  opacity: 0;
}
.slide-fade-enter-active {
  transition: all 0.3s ease-in-out;
}
.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateX(-100px);
  opacity: 0;
}
</style>