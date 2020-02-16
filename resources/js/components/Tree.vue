<template>
  <el-card>
    <div v-if="treeName" slot="header" class="clearfix" style="text-align:left">
      <span>{{treeName}}</span>
      <el-button
        style="text-align:eft;float: right; padding: 3px 0"
        type="text"
        @click="sck"
      >Operation button</el-button>
    </div>

    <div style="max-width:550px">
      <el-input placeholder="Filter keyword" v-model="filterText"></el-input>

      <el-tree
        node-key="id"
        :default-expand-all="false"
        :expand-on-click-node="false"
        show-checkbox
        highlight-current
        class="filter-tree"
        :data="tree"
        :props="defaultProps"
        :filter-node-method="filterNode"
        ref="tree"
        @check-change="handleCheckChange"
      ></el-tree>
    </div>
  </el-card>
</template>

<script>
import { mapGetters, mapActions, mapState, mapMutations } from "vuex";
export default {
  watch: {
    treeNameCmp(nv) {
      //console.log(nv);

      this.$emit("isTopicActive", "");
      setTimeout(() => {
        this.setCheckedNodes();
      }, 500);
    },
    filterText(val) {
      this.$refs.tree.filter(val);
    }
  },

  methods: {
    sck() {
      console.log("31049876");
      this.$refs.tree.setCheckedKeys(["31049876"]);
    },
    setCheckedNodes() {
      console.log("after 500");
      let kw = this.topics.find(a => a.name == this.treeName).keywords;

      console.log({ kw123123: kw });
      this.$refs.tree.setCheckedNodes([...kw, ...this.meKeywords]);
    },
    ...mapMutations([
      "setTopicKeywords",
      "deleteFromMeKyWords",
      "pushMeKyWords"
    ]),
    handleCheckChange(data, checked, indeterminate) {
      console.log(data, checked, indeterminate);
      let nodes = this.$refs.tree.getCheckedNodes();
      if (!checked) this.deleteFromMeKyWords(data);
      else this.pushMeKyWords(data);
      this.$emit("isTopicActive", Boolean(nodes.length));
      this.setTopicKeywords(nodes);
    },
    filterNode(value, data) {
      if (!value) return true;
      try {
        return data.label.indexOf(value) !== -1;
      } catch (e) {
        console.error({ value, data, e });
      }
    }
  },

  data() {
    return {
      filterText: "",
      data: [
        {
          id: 1,
          label: "Level one 1",
          children: [
            {
              id: 4,
              label: "Level two 1-1",
              children: [
                {
                  id: 9,
                  label: "Level three 1-1-1"
                },
                {
                  id: 10,
                  label: "Level three 1-1-2"
                }
              ]
            }
          ]
        },
        {
          id: 2,
          label: "Level one 2",
          children: [
            {
              id: 5,
              label: "Level two 2-1"
            },
            {
              id: 6,
              label: "Level two 2-2"
            }
          ]
        },
        {
          id: 3,
          label: "Level one 3",
          children: [
            {
              id: 7,
              label: "Level two 3-1"
            },
            {
              id: 8,
              label: "Level two 3-2"
            }
          ]
        }
      ],
      defaultProps: {
        children: "children",
        label: "name",
        id: "ccm2_Id"
      }
    };
  },
  computed: {
    treeNameCmp() {
      return this.treeName;
    },
    ...mapState(["tree", "treeName", "topics", "meKeywords"])
  }
};
</script>