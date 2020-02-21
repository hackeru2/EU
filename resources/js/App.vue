<template>
  <div>
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
      <span>{{authUser.name}}</span>
    </el-header>
    <el-container>
      <el-main>
        <el-row>
          <el-col class="hidden-md-and-down" :xs="8" :sm="3" :md="6" :lg="5" :xl="4">
            <el-aside style="width:100%;background-color: rgb(238, 241, 246)">
              <el-menu :default-openeds="['1', '3']">
                <el-submenu index="1">
                  <template slot="title">
                    <i class="el-icon-message"></i>Navigator One
                  </template>
                  <el-menu-item-group>
                    <template slot="title">All keywords</template>

                    <transition
                      name="el-zoom-in-top"
                      v-for="(item, index) in meKeywords"
                      :key="index"
                      :index="`1-${index}`"
                    >
                      <el-menu-item>{{item.name}}</el-menu-item>
                    </transition>

                    <!-- <el-menu-item index="1-2">Option 2</el-menu-item> -->
                  </el-menu-item-group>
                  <!-- <el-menu-item-group title="Group 2">
              <el-menu-item index="1-3">Option 3</el-menu-item>
                  </el-menu-item-group>-->
                  <el-submenu index="1-4">
                    <template slot="title">Option4</template>
                    <el-menu-item index="1-4-1">Option 4-1</el-menu-item>
                  </el-submenu>
                </el-submenu>
                <el-submenu index="2">
                  <template slot="title">
                    <i class="el-icon-menu"></i>Navigator Two
                  </template>
                  <el-menu-item-group>
                    <template slot="title">Group 1</template>
                    <el-menu-item index="2-1">Option 1</el-menu-item>
                    <el-menu-item index="2-2">Option 2</el-menu-item>
                  </el-menu-item-group>
                  <el-menu-item-group title="Group 2">
                    <el-menu-item index="2-3">Option 3</el-menu-item>
                  </el-menu-item-group>
                  <el-submenu index="2-4">
                    <template slot="title">Option 4</template>
                    <el-menu-item index="2-4-1">Option 4-1</el-menu-item>
                  </el-submenu>
                </el-submenu>
                <el-submenu index="3">
                  <template slot="title">
                    <i class="el-icon-setting"></i>Navigator Three
                  </template>
                  <el-menu-item-group>
                    <template slot="title">Group 1</template>
                    <el-menu-item index="3-1">Option 1</el-menu-item>
                    <el-menu-item index="3-2">Option 2</el-menu-item>
                  </el-menu-item-group>
                  <el-menu-item-group title="Group 2">
                    <el-menu-item index="3-3">Option 3</el-menu-item>
                  </el-menu-item-group>
                  <el-submenu index="3-4">
                    <template slot="title">Option 4</template>
                    <el-menu-item index="3-4-1">Option 4-1</el-menu-item>
                  </el-submenu>
                </el-submenu>
              </el-menu>
            </el-aside>
          </el-col>
          <el-col :xs="24" :sm="21" :md="18" :lg="19" :xl="20">
            <router-view></router-view>
          </el-col>
        </el-row>
      </el-main>
    </el-container>
  </div>
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
  methods: { ...mapActions(["meAct"]) }
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
</style>