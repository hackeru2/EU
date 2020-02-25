<template>
  <el-container>
    <el-aside width="200px" class="hidden-md-and-down" style="background-color: rgb(238, 241, 246)">
      <el-menu
        default-active="2"
        class="el-menu-vertical-demo"
        @open="handleOpen"
        @close="handleClose"
        :collapse="true"
      >
        <el-submenu index="1">
          <template slot="title">
            <i class="el-icon-location"></i>
            <span slot="title">Navigator One</span>
          </template>
          <el-menu-item-group>
            <span slot="title">Group One</span>
            <el-menu-item index="1-1">item one</el-menu-item>
            <el-menu-item index="1-2">item two</el-menu-item>
          </el-menu-item-group>
          <el-menu-item-group title="Group Two">
            <el-menu-item index="1-3">item three</el-menu-item>
          </el-menu-item-group>
          <el-submenu index="1-4">
            <span slot="title">item four</span>
            <el-menu-item index="1-4-1">item one</el-menu-item>
          </el-submenu>
        </el-submenu>
        <el-menu-item index="2">
          <i class="el-icon-menu"></i>
          <span slot="title">Navigator Two</span>
        </el-menu-item>
        <el-menu-item index="3" disabled>
          <i class="el-icon-document"></i>
          <span slot="title">Navigator Three</span>
        </el-menu-item>
        <el-menu-item index="4">
          <i class="el-icon-setting"></i>
          <span slot="title">Navigator Four</span>
        </el-menu-item>
      </el-menu>
    </el-aside>
    <el-container>
      <el-header style="text-align: right; font-size: 12px">
        <!-- <span class="title">{{ fit }}</span> -->

        <img
          style="background-color:gray;height:60px;padding:2px;margin-right:20px;float:left"
          :src="url"
        />

        <div style="float:left">
          <router-link to="/keywords">keywords</router-link>
          <el-divider direction="vertical"></el-divider>

          <router-link to="/calls">Calls</router-link>
          <el-divider direction="vertical"></el-divider>
          <router-link to="/calls-table">Table</router-link>
          <el-divider direction="vertical"></el-divider>
          <router-link to="/profile">Profile</router-link>
        </div>
        <el-dropdown>
          <i class="el-icon-setting" style="margin-right: 15px"></i>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item>View</el-dropdown-item>
            <el-dropdown-item>Add</el-dropdown-item>
            <el-dropdown-item>Delete</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
        <span>{{authUser.name}} {{isCollapse}}</span>
      </el-header>

      <el-button style="left:38px;position:absolute" @click="isCollapse = !isCollapse">C</el-button>
      <el-main>
        <router-view></router-view>
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

export default {
  data() {
    const item = {
      date: "2016-05-02",
      name: "Tom",
      address: "No. 189, Grove St, Los Angeles"
    };
    return {
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
  created() {},
  computed: {
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
  width: 300px;
  min-height: 400px;
}
</style>