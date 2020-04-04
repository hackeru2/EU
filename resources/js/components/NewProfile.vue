<template>
  <el-row style="min-height:85vh" v-load="loading">
    <el-button
      style="position:fixed;right:5px;bottom:5px;z-index:100"
      type="info"
      icon="el-icon-info"
      circle
      @click="infoBtn = !infoBtn"
    />
    <el-card
      :style="infoCardStyle"
      style="position:fixed;right:5px;bottom:0;padding-top:20px;max-height:70vh;overflow-y:auto"
    >
      <div slot="header">all tags with origin names</div>
      <div v-for="(item, index) in selectedExtract" :key="index">
        <b>header :</b>
        {{item.header}} ,
        <b>name :</b>
        {{item.name}} ,
        <b class="text-info" v-if="item.origin_name">origin name :</b>
        {{item.origin_name}}
      </div>
    </el-card>
    <el-row class="mt-4">
      <div style="margin-top: 15px;"></div>
      <el-col :span="10" :offset="6">
        <el-input v-model="query" placeholder="search" class="inline-input">
          <template slot="append">
            <el-button icon="el-icon-search" />
            <el-button
              @click="onSave"
              style="margin-left:16px;background-color:DodgerBlue;color:white"
              type="primary"
              icon="el-icon-s-promotion"
            />
          </template>
        </el-input>
      </el-col>
    </el-row>
    <div class="flex-container m-4">
      <div v-for="(item, index) in mainListFilter" :key="index">
        <div>
          <h3 style="text-shadow: 1px 1px white;">{{item.name}}</h3>
          <hr class="hr" />
        </div>
        <transition-group
          name="staggered-fade"
          tag="ul"
          v-bind:css="false"
          v-on:before-enter="beforeEnter"
          v-on:enter="enter"
          v-on:leave="leave"
        >
          <li
            v-for="value in unique(item.values)"
            :class="{liSelected : selectedObj[value.name]}"
            class="liClass"
            @click="selected(value)"
            :key="value.id"
          >
            <transition
              name="custom-classes-transition"
              enter-active-class="animated flip"
              leave-active-class="animated rotateOut"
            >
              <i
                class="el-icon-circle-check text-success font-weight-bold"
                v-if="selectedObj[value.name]"
              />
            </transition>
            {{value.name}}
          </li>
        </transition-group>
      </div>
    </div>
  </el-row>
</template>

<script>
import draggable from "vuedraggable";
import { mapActions, mapGetters, mapState, mapMutations } from "vuex";

let id = 1;
export default {
  name: "NEWPROFILE",
  display: "Two list header slot",
  order: 14,
  computed: {
    ...mapState(["meTags"]),
    infoCardStyle() {
      return this.infoBtn ? "margin-right:0 " : "margin-right:-1200px";
    },
    infoBtnStyle() {
      return this.infoBtn ? "" : "position:fixed;right:0;top:80%";
    },
    allTagsGrouped() {
      return _.groupBy(this.allTags, "name");
    },
    selectedExtract() {
      try {
        let arr = [];
        let sr = [...this.selectedArr];
        sr.forEach(element => {
          arr.push(...this.allTagsGrouped[element.name]);
        });
        return arr;
      } catch (e) {
        return [];
      }
    },
    selectedObj() {
      let sr = [...this.selectedArr];

      return _.groupBy(sr, "name");
    },
    options() {
      try {
        return this.lists.map(a => {
          {
            let name = a.name;
            let value = a.name;
            return {
              name,
              value
            };
          }
        });
      } catch (e) {}
    },
    findListNTag() {
      try {
        if (this.findList(this.groupedTags.listName).values.length)
          return this.findList(this.groupedTags.listName).values.find(
            v => v.name == this.renameTag
          ).origin_name;
      } catch (e) {
        //console.log(e);
      }
    },
    tagChildren() {
      try {
        return this.findList(this.groupedTags.listName)
          .values.filter(v => v.name == this.renameTag && v.origin_name != null)
          .map(v => v.origin_name);
      } catch (e) {}
    },
    dialogBoxes() {
      if (
        this.form.title == "Extract Tag origin names" &&
        this.dialogFormVisible
      )
        return this.findList(this.groupedTags.listName)
          .values.filter(v => v.name == this.form.name)
          .map(v => v.origin_name);
      else return this.groupedTags.values;
    },
    listMainValuesFilter() {
      return this.listMainValues.filter(
        data =>
          !this.searchMain ||
          data.name.toLowerCase().includes(this.searchMain.toLowerCase())
      );
    },
    mainListFilter() {
      try {
        return this.lists
          .filter(l => l.name != this.listMainName)
          .filter(data =>
            data.name.toLowerCase().includes(this.search.toLowerCase())
          );
      } catch (e) {
        return this.lists;
      }
    },
    placeHolder() {
      switch (true) {
        case this.form.title ==
          "this action will move the following tags. approve?":
          return "move tags";
          break;
        case this.form.title == "Handle Unify":
          return "Choose new name for selected tags";
          break;
        case this.form.title == "Extract Tag origin names":
          return "extract";
        default:
          return "";
      }
      // return this.form.title == "Handle Unify"
      //   ? "Choose new name for selected tags"
      //   : "";
    },
    // listMainValues() {
    //   return this.lists.find(l => l.name == "listMain").values; //,
    // },

    //  e => e.name
    //);
    listMainName() {
      try {
        return this.lists.find(l => l.name == "listMain").name;
      } catch (e) {}
    },
    groupedTagsListName() {
      try {
        return this.groupedTags.listName;
      } catch (e) {}
    }
  },
  watch: {
    groupedTagsListName(nv, ov) {
      if (ov && this.lists) {
        let vals = this.findList(ov).values;
        if (vals && vals.length)
          vals = vals.map(v => {
            v.checked = false;
            return v;
          });
      }
      return (this.groupedTags.values = []);
    }
  },
  async created() {
    let allTags = "";
    let DBTAGS = await this.Tags();
    console.log({ DBTAGS });

    if (DBTAGS.length) {
      this.allTags = DBTAGS;

      this.lists = Object.values(DBTAGS);
      this.lists = Object.entries(_.groupBy(DBTAGS, "header")).map(entry => {
        return {
          name: entry[0],
          show: true,
          values: entry[1]
        };
      });
      this.listMainValues = this.lists.find(l => l.name == "listMain").values; //,
      console.log(this.listMainValues);

      return console.log("this.lists");
    }
    this.ResetAll();
  },
  components: {
    draggable
  },
  data() {
    return {
      loading: true,
      infoBtn: false,
      show: "",
      query: "",
      selectedArr: [],
      search: "",
      isDisabled: false,
      selectModel: "",
      listMainValues: [],
      futureIndex: "",
      movingIndex: "",
      listDragg: "",
      savePosition: { style: "" },
      groupedTags: { listName: "", values: [] },
      renameTag: "",
      allTags: [],
      form: {
        old_name: "",
        name: "",
        title: "Rename header"
      },
      formLabelWidth: "200",
      dialogFormVisible: false,
      search: "",
      searchMain: "",
      lists: [
        { name: "list1", values: [], show: true },
        { name: "list2", values: [], show: true },
        {
          name: "listMain",
          values: [{ name: "loading data...", id: 0 }]
        }
      ]
    };
  },
  mounted() {
    setTimeout(() => {
      this.selectedArr = this.meTags.filter(t => t.header != "listMain");
      this.loading = false;
    }, 1000);
    // let gebi = document.getElementById("input__inner");
    // console.log(gebi);
    // console.log(($("#input__inner")[0].style.height = "38px"));
  },
  methods: {
    selected(value) {
      console.log({ value, OBJ: this.selectedObj[value.name] });
      if (!this.selectedObj[value.name]) this.selectedArr.push(value);
      else
        this.selectedArr = this.selectedArr.filter(
          a => a.name != value.name || a.header != value.header
        );

      console.log(this.selectedArr);
    },
    liHover(e) {
      console.log(e.target);
      console.log(e.target.classList);
      e.target.style = "color:white";
    },
    liLeave(e) {
      console.log(e.target);
      console.log(e.target.style);
      e.target.style = "color:gray";
    },
    unique(arr) {
      let _this = this;
      return _.uniqBy(arr, function(e) {
        return e.name;
      }).filter(function(item) {
        return (
          item.name.toLowerCase().indexOf(_this.query.toLowerCase()) !== -1
        );
      });
    },
    beforeEnter: function(el) {
      el.style.opacity = 0;
      el.style.height = 0;
    },
    enter: function(el, done) {
      var delay = el.dataset.index * 150;
      setTimeout(function() {
        Velocity(el, { opacity: 1, height: "1.6em" }, { complete: done });
      }, delay);
    },
    leave: function(el, done) {
      var delay = el.dataset.index * 150;
      setTimeout(function() {
        Velocity(el, { opacity: 0, height: 0 }, { complete: done });
      }, delay);
    },
    ...mapActions(["meAct"]),
    handleOpen(key, keyPath) {
      console.log(key, keyPath);
    },
    handleClose(key, keyPath) {
      console.log(key, keyPath);
    },
    onDestroyPopper() {
      alert("345");
      this.selectModel = "";
    },
    moveTags() {
      this.dialogFormVisible = true;
      this.form.title = "this action will move the following tags. approve?";
    },
    showSelect(name) {
      try {
        return (
          name == this.groupedTags.listName && this.groupedTags.values.length
        );
      } catch (e) {}
    },
    showMoveTagsButton(name) {
      return this.showSelect(name) && this.selectModel;
    },
    // isDisa bled(e) {
    //   console.log({ e });
    //   return true;
    // },
    onRemove() {},
    // onRemove(e) {
    //   console.log({ e });
    //   console.log({ from: e.srcElement.dataset.source });
    //   let toName = e.to.dataset.source;
    //   let toList = this.findList(e.to.dataset.source);
    //   console.log({ toName, toList });
    //   console.log({ e1: e.target.children[0].id });
    //   let transferedEl = this.findList(e.to.dataset.source).values.find(
    //     v => v.name == e.target.children[0].id
    //   );
    //   console.log({
    //     transferedEl
    //   });

    //   let fromList = this.findList(e.srcElement.dataset.source);
    //   let fromListDraggedValues = fromList.values.filter(
    //     v => v.name == e.target.children[0].id
    //   );
    //   console.log({ fromListDraggedValues });
    //   toList.values = [...toList.values, ...fromListDraggedValues];
    //   // console.log({
    //   //   ORIGINS: fromList.values.filter(v => v.name == e.target.children[0].id)
    //   // });
    //   fromList.values = fromList.values.filter(
    //     v => v.name != e.target.children[0].id
    //   );
    // },
    findList(name) {
      return this.lists.find(l => l.name == name) || [];
    },
    open() {
      this.$confirm(
        "This will permanently Reset  the lists. Continue?",
        "Warning",
        {
          confirmButtonText: "OK",
          cancelButtonText: "Cancel",
          type: "warning"
        }
      )
        .then(() => {
          this.resetAll();
          this.$message({
            type: "success",
            message: "Delete completed"
          });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "Delete canceled"
          });
        });
    },
    async resetAll() {
      this.lists = [
        { name: "list1", values: [], show: true },
        { name: "list2", values: [], show: true },
        {
          name: "listMain",
          values: []
        }
      ];
      let allTags = "";
      console.log("start");
      allTags = await this.tagsUnique();
      console.log(allTags);
      this.allTags = allTags;
      this.lists.find(l => l.name == "listMain").values = allTags;
      this.listMainValues = this.lists.find(l => l.name == "listMain").values; //,
      console.log(this.lists);
      console.log("end");
    },
    log(e) {
      console.log({ e });
    },
    elIncludesSearchMain(name) {
      if (!this.searchMain) return true;

      return name
        .trim()
        .toLowerCase()
        .includes(this.searchMain.trim().toLowerCase());
    },
    onMove(e) {
      // if (e.draggedContext.element.origin_name);
      // this.isDisabled = true;
      console.log(e.draggedContext);
    },
    onStart(CustomEvent) {
      console.log("onStart", CustomEvent.item.textContent);
    },
    choose(CustomEvent) {
      console.log("choose", CustomEvent.item.textContent);
    },
    end(CustomEvent) {
      console.log("end", CustomEvent.item.textContent, CustomEvent);
    },
    unchoose(CustomEvent) {
      console.log("unchoose", CustomEvent.item.textContent);
    },
    handleDragEnd() {
      this.$message("dragEnd");
      console.log(this.listDragg);
      let list = this.lists.find(l => l.name == this.listDragg).values;

      this.futureItem = list[this.futureIndex];
      this.movingItem = list[this.movingIndex];
      const _items = Object.assign([], list);
      _items[this.futureIndex] = this.movingItem;
      _items[this.movingIndex] = this.futureItem;
      console.log({ _items });
      list = _items;
    },
    handleMove(e) {
      console.log(e.draggedContext);
      this.listDragg = e.draggedContext.element.header;
      const { index, futureIndex } = e.draggedContext;
      // console.log({ index, futureIndex });
      this.movingIndex = index;
      this.futureIndex = futureIndex;
      return false; // disable sort
    },
    addNewHeader() {
      this.form.title = "Add new header";
      this.dialogFormVisible = true;
    },
    onSave() {
      this.inserUserProfileTags(this.selectedExtract);
      this.$notify({
        title: "List Saved!",
        message: "to the DB",
        type: "success"
      });
    },
    // preventDuplicates(arr, el, i) {
    //   let myArr = [...arr];
    //   arr.map( n => n.name == el.name)
    //   if()
    //},
    uniqBy(scope_row_values) {
      let newArr = [...scope_row_values];
      return _.uniqBy(newArr, e => e.name);
    },
    onRowClick(payload) {
      if (payload.name) this.groupedTags.listName = payload.name;
      // if (
      //   this.groupedTags.listName &&
      //   this.groupedTags.listName != payload.name
      // ) {
      //   this.lists
      //     .find(l => l.name == this.groupedTags.listName)
      //     .values.map(e => {
      //       e.checked = false;
      //       return e;
      //     });
      //   this.groupedTags.values = [];
      // }
      // // this.groupedTags.listName = payload.name;
      // return console.log({ payload });
    },
    handleUnify() {
      this.form.title = "Handle Unify";
      this.dialogFormVisible = true;
      console.log(this.groupedTags.values);
    },
    onChangeCB(isCheck, event) {
      //console.log(isCheck);

      let index = this.groupedTags.values.findIndex(a => a == this.renameTag);
      console.log({ index, isCheck });
      if (isCheck && index == -1) {
        console.log("push");
        this.groupedTags.values.push(this.renameTag);
      } else if (!isCheck) {
        console.log("delete");

        this.groupedTags.values.splice(index, 1);
        //if (!this.groupedTags.values.length) this.groupedTags.listName = "";
      }
    },
    extractingTags() {
      let otherValues = this.findList(this.groupedTags.listName).values.filter(
        v => v.name != this.form.name
      );
      console.log({ otherValues });
      let extractedValues = this.findList(this.groupedTags.listName)
        .values.filter(v => v.name == this.form.name)
        .map(t => {
          t.name = t.origin_name;

          //alert(t.origin.name);
          t.origin_name = "";
          return t;
        });
      this.findList(this.groupedTags.listName).values = [
        ...otherValues,
        ...extractedValues
      ];
    },
    movingTags() {
      let toList = this.findList(this.selectModel);
      let fromList = this.findList(this.groupedTagsListName);
      console.log({ toListValues: toList.values });
      console.log({ fromListValues: fromList.values });
      let transfers = fromList.values.filter(
        v => v.checked || this.dialogBoxes.includes(v.name)
      );
      transfers.forEach(transfer => {
        transfer.checked = false;
        toList.values.push(transfer);
      });
      fromList.values = _.difference(fromList.values, transfers);
      this.groupedTags.listName = "";
      this.groupedTags.values = [];
      this.selectModel = "";
      this.$alert(
        "tags transfered from " + fromList.name + " to " + toList.name
      );
    },
    onConfirmDialog() {
      this.dialogFormVisible = false;
      if (this.placeHolder == "move tags") {
        return this.movingTags();
      }
      if (this.form.title == "Extract Tag origin names") {
        return this.extractingTags();
      }
      if (this.form.title == "Add new header") {
        return this.lists.push({
          name: this.form.name,
          values: [],
          show: true
        });
      }
      if (this.form.title == "Rename header")
        this.lists.find(
          n => n.name == this.form.old_name
        ).name = this.form.name;
      else if (this.form.title == "Rename Tag name") {
        let value = this.lists[this.form.listsIndex].values[this.form.tagIndex];
        if (!value.origin_name) value.origin_name = value.name;
        value.name = this.form.name;
      } else if (this.form.title == "Handle Unify") {
        //console.log(this.form);
        let chosenList = this.lists.find(
          l => l.name == this.groupedTags.listName
        );

        chosenList.values.forEach((element, i) => {
          if (this.groupedTags.values.includes(element.name)) {
            if (!element.origin_name) element.origin_name = element.name;
            element.name = this.form.name;
            element.checked = false;
            //element.name = this.form.name;
          }
        });
        this.groupedTags = { listName: "", values: [] };
        this.renameTag = "";
        console.log(
          Object.values(this.lists)
            .map(a => a.values)
            .flat()
        );
      }
    },
    onMouseoverTag(a) {
      console.log({ a });
      //return console.log(a);
      this.renameTag = a.name;
      //return console.log(this.lists[a]);
    },
    onExtractTag(listsIndex, tagIndex) {
      //this.renameTag = this.lists[a].values[b].name;
      console.log(this.renameTag);
      this.form.listsIndex = listsIndex;
      this.form.tagIndex = tagIndex;
      console.log({ form: this.form });
      this.form.name = this.renameTag;
      this.dialogFormVisible = true;
      // setTimeout(() => {
      //   this.renameTag = "";
      // }, 1000);

      this.form.title = "Extract Tag origin names";
    },
    onRenameTag(listsIndex, tagIndex) {
      //this.renameTag = this.lists[a].values[b].name;
      console.log(this.renameTag);
      this.form.listsIndex = listsIndex;
      this.form.tagIndex = tagIndex;
      console.log({ form: this.form });
      this.form.name = this.renameTag;
      setTimeout(() => {
        this.renameTag = "";
      }, 1000);
      this.dialogFormVisible = true;
      this.form.title = "Rename Tag name";
    },
    handleEdit(name, index) {
      this.form.name = name;
      this.form.old_name = name;
      this.form.title = "Rename header";
      this.form.index = index;
      this.dialogFormVisible = true;
    },
    changeData(e) {
      console.log(e.target.innerText);
      this.groupedTags.listName = e.target.innerText;
    },
    ...mapActions([
      "inserUserProfileTags",
      "getBigJsonAct",
      "getTags",
      "tagsUnique",
      "insertTags",
      "Tags"
    ]),
    add: function(item) {
      //      this.list.push({ name: "Juan " + id, id: id++ });
      item.values.push({ name: "Juan " + id, id: id++ });
    },
    replace: function(item) {
      //this.list = [{ name: "Edgard", id: id++ }];
      item.values = [{ name: "Edgard", id: id++ }];
    },
    add2: function(item) {
      //this.list = [{ name: "Edgard", id: id++ }];
      item.values.push({ name: "Juan " + id, id: id++ });
    },
    replace2: function(item) {
      //this.list2 = [{ name: "Edgard", id: id++ }];
      item.values = [{ name: "Edgard", id: id++ }];
    }
  }
};
</script>
<style scoped>
@import url(../../css/screen.css);
.el-aside {
  background-color: #d3dce6;
  color: #333;
  text-align: center;
  line-height: 10px;
}
.item {
  cursor: -webkit-grab;
}

.w3-theme {
  color: #fff !important;
  background-color: #3f51b5 !important;
}
.w3-theme-light {
  color: #000 !important;
  background-color: #e8eaf6 !important;
}
.w3-theme-dark {
  color: #fff !important;
  background-color: #1a237e !important;
}

.w3-theme-15 {
  color: #000 !important;
  background-color: #e8eaf6 !important;
}
.w3-theme-14 {
  color: #000 !important;
  background-color: #c5cae9 !important;
}
.w3-theme-13 {
  color: #000 !important;
  background-color: #9fa8da !important;
}
.w3-theme-12 {
  color: #fff !important;
  background-color: #7986cb !important;
}
.w3-theme-11 {
  color: #fff !important;
  background-color: #5c6bc0 !important;
}
.w3-theme-10 {
  color: #fff !important;
  background-color: #3949ab !important;
}
.w3-theme-9 {
  color: #fff !important;
  background-color: #303f9f !important;
}
.w3-theme-8 {
  color: #fff !important;
  background-color: #283593 !important;
}
.w3-theme-7 {
  color: #fff !important;
  background-color: #1a237e !important;
}
.w3-theme-0 {
  color: #000 !important;
  background-color: #ffe8eb !important;
}
.w3-theme-1 {
  color: #000 !important;
  background-color: #ffb3be !important;
}
.w3-theme-2 {
  color: #fff !important;
  background-color: #ff667d !important;
}
.w3-theme-3 {
  color: #fff !important;
  background-color: #ff1a3c !important;
}
.w3-theme-4 {
  color: #fff !important;
  background-color: #cc001f !important;
}
.w3-theme-5 {
  color: #fff !important;
  background-color: #730011 !important;
}
.w3-theme-6 {
  color: #fff !important;
  background-color: #66000f !important;
}

.w3-theme-action {
  color: #fff !important;
  background-color: #311b92 !important;
}
.w3-text-theme {
  color: #1a237e !important;
}
.w3-text-theme {
  color: #bc70a4 !important;
}
.w3-border-theme {
  border-color: #bc70a4 !important;
}

.w3-hover-theme:hover {
  color: #fff !important;
  background-color: #bc70a4 !important;
}
.w3-hover-text-theme:hover {
  color: #bc70a4 !important;
}
.w3-hover-border-theme:hover {
  border-color: #bc70a4 !important;
}
.gradinent-list {
  position: sticky;
  top: 0;
  z-index: 123;
  background: rgb(204, 0, 31);
  background: linear-gradient(
    90deg,
    rgba(204, 0, 31, 1) 0%,
    rgba(255, 255, 255, 1) 50%,
    rgba(26, 35, 126, 1) 100%
  );
}
.el-table .cell {
  word-break: keep-all !important;
}
.flex-container {
  border-radius: 40px;
  display: flex;
  flex-wrap: wrap;

  background-color: DodgerBlue;
}

.flex-container > div {
  border-radius: 20px;
  box-shadow: 5px 5px 2px #aaaaaa;
  background-color: #f1f1f1;
  overflow: hidden;
  max-width: 100%;
  margin: 10px;
  text-align: center;
  padding: 10px;
  padding-right: 20px;
  font-size: 1rem;
}
.flex-container > div:hover {
  background-color: #f1f1f1;
  background-color: #d2d8ffc5;
  -webkit-transition: background-color 2000ms linear;
  -ms-transition: background-color 2000ms linear;
  transition: background-color 2000ms linear;
}
.hr {
  width: 150% !important;
  margin-top: 0 !important;
  margin-bottom: 1rem;
  border: 0;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
  margin-left: -13px;
}
.liClass {
  cursor: pointer;
}

.liClass:hover {
  color: white;
}
.liClass:active,
.liSelected {
  font-weight: 900;
  margin: 0px -5px 0px -5px;
}
ul {
  list-style-type: none;
}
</style>