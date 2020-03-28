<template>
  <el-card class="m-4">
    <h1 class="m-5">Subjects</h1>
    <el-button-group style="margin-bottom: 20px;">
      <el-button size="small" @click="addTab(editableTabsValue)">add tab</el-button>
      <el-button v-if="!renameTab" size="small" @click="renameTab=true">rename tab</el-button>
      <el-input
        style="display:inline-block;width:100px"
        v-else
        @blur="renameTab=false"
        size="small"
        placeholder="rename tab"
      ></el-input>
    </el-button-group>
    <el-tabs v-model="editableTabsValue" type="card" closable @tab-remove="removeTab">
      <el-tab-pane
        v-for="(item, index) in editableTabs"
        :key="item.name"
        :label="item.title"
        :name="item.name"
      >
        <ul>
          <li v-for="(header, i_header) in item.headers" :key="i_header">{{header}}</li>
        </ul>
      </el-tab-pane>
    </el-tabs>
  </el-card>
</template>
<script>
import { mapActions, mapGetters, mapState, mapMutations } from "vuex";
export default {
  data() {
    return {
      renameTab: false,
      editableTabsValue: "2",
      editableTabs: [
        {
          title: "Security",
          name: "1",
          content: "security"
        },
        {
          title: "New 1",
          name: "2",
          content: "Tab 2 content"
        }
      ],
      tabIndex: 2
    };
  },

  async created() {
    this.editableTabs = await this.getSubjects();
  },
  methods: {
    ...mapActions(["getSubjects"]),
    addTab(targetName) {
      let newTabName = ++this.tabIndex + "";
      this.editableTabs.push({
        title: "New " + (this.tabIndex - 1),
        name: newTabName,
        content: "New Tab content"
      });
      this.editableTabsValue = newTabName;
    },
    removeTab(targetName) {
      let tabs = this.editableTabs;
      let activeName = this.editableTabsValue;
      if (activeName === targetName) {
        tabs.forEach((tab, index) => {
          if (tab.name === targetName) {
            let nextTab = tabs[index + 1] || tabs[index - 1];
            if (nextTab) {
              activeName = nextTab.name;
            }
          }
        });
      }

      this.editableTabsValue = activeName;
      this.editableTabs = tabs.filter(tab => tab.name !== targetName);
    }
  }
};
</script>