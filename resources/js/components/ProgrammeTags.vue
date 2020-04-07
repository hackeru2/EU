<template>
  <el-row>
    <el-card
      :class="cardProgrammeClass"
      body-style="width:400px;border-right:10px solid gray;padding:30px;overflow:auto;max-height:1000px"
    >
      <div slot="header">
        <h5>{{cardOfProgrammes.name}}</h5>
        <el-button type="text" class="close_btn" @click="cardOfProgrammes.show = false">X</el-button>
      </div>
      <!-- <el-tag class="d-flex flex-wrap bd-highlight mb-3"> -->
      <el-tag
        v-for="(item, index)  in cardOfProgrammes.values"
        :type="mainProgDesc.includes(item) ? 'warning' : 'info'"
        :key="index"
      >{{item}}</el-tag>
      <!-- </div> -->
    </el-card>
    <!-- {{groupedTags}} -->
    <el-col :span="4" :sm="6" :xs="6">
      <el-aside
        width="100%"
        class="my-aside"
        style="background-color: rgb(238, 241, 246); max-height:800px"
      >
        <el-card class="gradinent-list">
          <h3>{{ listMainName | capitalize}}</h3>
          <div>
            <el-input
              v-model="searchMain"
              size="mini"
              style="width : 100%"
              placeholder="Search list"
              prefix-icon="el-icon-search"
            />
          </div>
        </el-card>
        <draggable
          :list="listMainValues"
          class="list-group"
          draggable=".item"
          group="a"
          :move="onMove"
          @end="end"
        >
          <!-- @start="onStart"
          @choose="choose"-->
          <div
            @click="onClickTag(element)"
            v-show="elIncludesSearchMain(element.name)"
            class="list-group-item item w3-theme-dark"
            :class="`w3-theme-${(15 + main_i) % 15 }`"
            v-for="(element , main_i) in listMainValues"
            :key="element.id"
          >{{ element.name }}</div>

          <div
            slot="footer"
            class="btn-group list-group-item"
            role="group"
            aria-label="Basic example"
          >
            <!-- <button class="btn btn-secondary" @click="add2">Add</button>
            <button class="btn btn-secondary" @click="replace2">Replace</button>-->
          </div>
        </draggable>
      </el-aside>
    </el-col>
    <el-col :span="20" :sm="18" :xs="18">
      <el-container style=" max-height:800px">
        <el-main>
          <!-- renameTag {{renameTag}} |||| groupedTags ---- {{groupedTags}} -->
          <div style="position:sticky;top:0;z-index:1000;backgroundColor:#ff00003b;height:50px">
            <el-input
              v-model="search"
              size="mini"
              class="search-values"
              placeholder="Type to search"
              prefix-icon="el-icon-search"
            />
            <button class="btn btn-secondary float-left m-2" @click="addNewHeader">+ Add</button>
            <button class="btn btn-secondary float-left m-2" @click="open">Reset all</button>

            <!-- <div style="color:blue">{{findListNTag}}</div> -->
            grouped tags : {{groupedTags}} | {{groupedTagsListName}}
            <span
              style="color:green"
            >{{tagChildren}}</span>
          </div>

          <el-table @cell-mouse-enter="onRowClick" highlight-current-row :data="mainListFilter">
            <!--@click.native="changeData"  -->
            <!-- <el-table-column prop="date" label="Date" width="140"></el-table-column> -->
            <el-table-column prop="name" label="Name" width="120" align="center">
              <template slot-scope="scope">
                <div class="sm-and-up">{{scope.row.name}}</div>
                <div class="hidden-sm-and-up">
                  <el-button
                    size="mini"
                    @click="handleEdit(scope.row.name ,scope.$index )"
                  >Rename {{scope.row.name}}</el-button>
                  <el-button
                    size="mini"
                    :type="scope.row.show ? 'primary' : 'danger'"
                    style="margin:10px"
                    @click="scope.row.show = !scope.row.show"
                  >{{scope.row.show ? 'Hide' : 'Show' }}</el-button>
                  <el-button
                    :disabled="groupedTags.values.length <2 || groupedTags.listName!=scope.row.name"
                    size="mini"
                    @click="handleUnify"
                  >Unify {{groupedTags.length}}</el-button>
                  <el-button v-for="(item, index) in tagChildren" :key="index">{{item}}</el-button>
                </div>
              </template>
            </el-table-column>
            <el-table-column class-name="values-col" prop="values" label="Values" align="center">
              <template slot-scope="scope">
                <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">Edit</el-button>
                <el-button
                  size="mini"
                  type="danger"
                  @click="handleDelete(scope.$index, scope.row)"
                >Delete</el-button>
              </template>

              <template slot-scope="scope" v-if="scope.row.show">
                <!-- disabled : findListNTag -->
                <draggable
                  @remove="onRemove"
                  :options="{disabled : isDisabled}"
                  id="first"
                  :move="onMove"
                  :data-source="scope.row.name"
                  :list="scope.row.values"
                  class="list-group"
                  draggable=".item"
                  group="a"
                >
                  <div
                    @click="onClickTag(element)"
                    @mouseleave="renameTag=''"
                    @mouseover="onMouseoverTag(element) "
                    :id="element.name"
                    class="list-group-item item"
                    style="margin:4px;border:1px solid aqua"
                    v-for="(element , e_i) in uniqBy(scope.row.values)"
                    :class="` w3-theme-${(15 + e_i+7) % 15 }`"
                    :key="element.id"
                  >
                    <el-checkbox
                      :disabled="groupedTags.listName != '' && groupedTags.listName!=scope.row.name"
                      @change="onChangeCB"
                      v-model="element.checked"
                      style="float:left"
                    ></el-checkbox>
                    {{ element.name }}
                    <div style="right: 0px;top:5px;position:absolute;">
                      <transition-group name="el-fade-in-linear">
                        <button
                          v-if="renameTag==element.name"
                          :key="1"
                          class="btn btn-secondary mr-2"
                          style="width:5vw;font-size:10px;padding: 0 0 3px 3px"
                          @click="onRenameTag(scope.$index , e_i)"
                        >Rename</button>
                        <button
                          :key="2"
                          v-if="renameTag==element.name && element.origin_name"
                          class="btn btn-secondary"
                          style="width:5vw;font-size:10px;padding: 0 0 3px 3px"
                          @click="onExtractTag(scope.$index , e_i)"
                        >EXTRACT</button>
                      </transition-group>
                      <br />

                      <!-- @click="add(scope.row)" -->
                    </div>
                  </div>

                  <div
                    v-if="!scope.row.values.length"
                    slot="footer"
                    class="btn-group list-group-item"
                    role="group"
                    aria-label="Basic example"
                  >
                    <!-- <button class="btn btn-secondary" @click="add(scope.row)">Add</button>
                    <button class="btn btn-secondary" @click="replace(scope.row)">Replace</button>-->
                    <el-card>Drag to create</el-card>
                  </div>
                </draggable>
              </template>
            </el-table-column>
            <el-table-column label="Actions" class-name="hidden-xs-only" align="center">
              <template slot="header">
                <!-- <el-button size="mini" @click="handleEdit( )">Edit</el-button> -->
              </template>
              <template slot-scope="scope">
                <div class="hidden-xs-only">
                  <el-button-group>
                    <template v-if="groupedTagsListName == scope.row.name">
                      <el-button
                        size="mini"
                        type="success"
                        v-for="(item, index) in tagChildren"
                        :key="index"
                      >{{item}}</el-button>
                    </template>
                    <el-button
                      size="mini"
                      @click="handleEdit(scope.row.name ,scope.$index )"
                    >Rename {{scope.row.name}}</el-button>
                    <el-button
                      size="mini"
                      :type="scope.row.show ? 'primary' : 'danger'"
                      @click="scope.row.show = !scope.row.show"
                    >{{scope.row.show ? 'Hide' : 'Show' }}</el-button>
                    <el-button
                      :disabled="groupedTags.values.length <2 || groupedTags.listName!=scope.row.name"
                      size="mini"
                      @click="handleUnify"
                    >Unify {{groupedTags.length}}</el-button>

                    <!-- <transition name="el-zoom-in-top">
                      <el-select
                        @destroyPopper="onDestroyPopper"
                        size="mini"
                        v-if="showSelect(scope.row.name)"
                        v-model="selectModel"
                        placeholder="Select"
                        id="input__inner"
                      >
                        <el-option
                          :disabled="item.name == groupedTagsListName || item.name == 'listMain'"
                          v-for="(item , i) in options"
                          :key="i"
                          :label="item.name"
                          :value="item.name"
                        ></el-option>
                      </el-select>
                    </transition>
                    <transition name="el-zoom-in-top">
                      <el-button
                        v-if="showMoveTagsButton(scope.row.name)"
                        size="mini"
                        @click="moveTags"
                      >Move tag/s</el-button>
                    </transition>-->
                  </el-button-group>
                </div>
              </template>
            </el-table-column>
          </el-table>
        </el-main>
      </el-container>

      <el-dialog :title="form.title" :visible.sync="dialogFormVisible">
        <el-form :model="form" @submit.native.prevent>
          <el-form-item label label-width="0" v-if="placeHolder=='extract'">
            <h3>EXTRACT {{form.name}}</h3>
          </el-form-item>
          <el-form-item label label-width="0" v-if="placeHolder=='move tags'">
            <h3>Move tags to {{selectModel}} list.</h3>
          </el-form-item>
          <el-form-item v-if="placeHolder" label="Tags" label-width="200">
            <el-badge
              v-for="(item, gtvi) in dialogBoxes"
              :key="gtvi"
              is-dot
              class="item"
              style="margin-right:5px"
            >
              <el-button class="share-button" plain icon="el-icon-share" type="primary">{{item}}</el-button>
            </el-badge>
          </el-form-item>

          <el-form-item
            label
            label-width="0"
            v-if="placeHolder!='extract' && placeHolder!='move tags' "
          >
            <el-input v-model="form.name" :placeholder="placeHolder" autocomplete="off"></el-input>
          </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">Cancel</el-button>
          <el-button
            type="primary"
            @click="onConfirmDialog"
          >{{placeHolder == 'extract' ? "Extract": 'Confirm'}}</el-button>
        </span>
      </el-dialog>
      <el-card
        style="z-index:100;position:fixed;bottom:10px;right:10px;padding:0;background-color:#ffffff6e"
        :style="savePosition.style"
      >
        <div>
          <i
            style="cursor:pointer"
            class="el-icon-caret-top"
            @click="savePosition.style = 'bottom:420px;right:10px'"
          ></i>
        </div>
        <el-button @click="onSave">SAVE</el-button>
        <div>
          <i
            style="cursor:pointer"
            class="el-icon-caret-bottom"
            @click="savePosition.style = 'bottom:10px;right:10px'"
          ></i>
        </div>
      </el-card>
    </el-col>
  </el-row>
</template>

<script>
import draggable from "vuedraggable";
import { mapActions, mapGetters, mapState, mapMutations } from "vuex";

export default {
  name: "two-list-headerslots",
  display: "Two list header slot",
  order: 14,
  computed: {
    mainProgDesc() {
      try {
        return this.mainProgrammes.map(a => a.description);
      } catch (error) {}
    },
    cardProgrammeClass() {
      return this.cardOfProgrammes.show
        ? "card-of-Programmes card-of-programmes-show"
        : "card-of-programmes";
    },
    bigJsonProgramme() {
      try {
        return _.mapValues(
          _.groupBy(
            this.bigJson.filter(
              c =>
                c.status.abbreviation != "Closed" &&
                c.status.description != "Closed"
            ),
            "tags"
          ),
          function(o) {
            return o[0].programmeDivision.map(a => a.description);
          }
        );
      } catch (error) {
        throw error;
      }
    },
    bigJsonProgrammeKeys() {
      try {
        return Object.keys(this.bigJsonProgramme);
      } catch (error) {}
    },
    subjectHeadersFlat() {
      return this.subjects.map(a => a.headers).flat();
    },
    ...mapState(["subjects", "bigJson", "mainProgrammes"]),
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
          .filter(l => this.hasSubject(l.name))
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
    this.callProgram();
    this.getBigJsonAct();
    await this.getSubjects();
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
      cardOfProgrammes: { show: false, values: [], name: "" },
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
  // mounted() {
  //   let gebi = document.getElementById("input__inner");
  //   console.log(gebi);
  //   console.log(($("#input__inner")[0].style.height = "38px"));
  // },
  methods: {
    async onClickTag({ name, origin_name }) {
      this.cardOfProgrammes.show = !this.cardOfProgrammes.show;
      this.cardOfProgrammes.name = name;
      if (!this.cardOfProgrammes.show) {
        return setTimeout(() => {
          this.onClickTag({ name, origin_name });
        }, 1000);
      }
      console.log({ name, origin_name, D_BOXES: this.dialogBoxes });
      let p_array = this.bigJsonProgrammeKeys.filter(
        tagsString =>
          tagsString.includes(name) ||
          tagsString.includes(origin_name) ||
          _.intersection(this.dialogBoxes, tagsString.split(","))
      );
      console.log(p_array[0]);
      console.log(this.bigJsonProgramme);
      p_array = Object.values(_.pick(this.bigJsonProgramme, p_array));
      p_array = p_array.flat();
      this.cardOfProgrammes.values = _.uniq(p_array);
      return console.log(p_array);
    },
    hasSubject(listName) {
      if (
        this.$route.name == "ProgrammeTagsNew" &&
        !this.subjects
          .map(a => a.headers)
          .flat()
          .includes(listName)
      )
        return true;
      else if (
        this.$route.name == "ProgrammeTags" &&
        this.subjectHeadersFlat.includes(listName)
      )
        return true;
    },
    onDestroyPopper() {
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
      let LISTS = Object.values(this.lists)
        .map(a => {
          return a.values.map(b => {
            b.header = a.name;
            delete b["checked"];
            delete b["id"];
            b.origin_name = b.origin_name ? b.origin_name : "";
            return b;
          });
        })
        .filter(a => a.length);
      console.log(LISTS);

      this.insertTags(LISTS.flat());
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
      "callProgram",
      "getSubjects",
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
.list-group-item.item {
  transition: all 0.2s;
}
.list-group-item.item:hover {
  font-weight: bold;

  font-size: 15px;
  text-shadow: 0.5px 0.5px #40ff90;

  /* background: rgba(
    76,
    175,
    80,
    0.3
  ) !important;   */
}
.card-of-programmes {
  z-index: 10000 !important;
  position: fixed;
  bottom: 0;
  right: 0;
  overflow: auto;
  right: 400px;
  z-index: 100;
  margin-right: -800px;
}
.card-of-programmes-show {
  bottom: 0;
  right: 0;
  position: fixed;
  z-index: 10000 !important;
}
.close_btn {
  position: absolute;
  right: 15px;
  top: 8px;
}
</style>