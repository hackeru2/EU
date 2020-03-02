<template>
  <div class="row">
    <!-- {{groupedTags}} -->
    <el-container style=" border: 1px solid #eee">
      <el-aside width="200px" style="background-color: rgb(238, 241, 246)">
        <h3>{{ listMainName | capitalize}}</h3>
        <div class="list-group-item item">
          <el-input
            v-model="searchMain"
            size="mini"
            style="width:150px"
            placeholder="Type to search"
            prefix-icon="el-icon-search"
          />
        </div>
        <draggable :list="listMainValues" class="list-group" draggable=".item" group="a">
          <div
            class="list-group-item item w3-theme-dark"
            :class="` w3-theme-${(15 + main_i) % 15 }`"
            v-for="(element , main_i) in listMainValuesFilter"
            :key="element.id"
          >
            {{ element.name }}
            <div></div>
          </div>

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

      <el-container>
        <el-main>
          <!-- renameTag {{renameTag}} |||| groupedTags ---- {{groupedTags}} -->
          <button class="btn btn-secondary float-left mb-2" @click="addNewHeader">+ Add</button>
          <el-table @row-click="onRowClick" highlight-current-row :data="mainListFilter">
            <!--@click.native="changeData"  -->
            <!-- <el-table-column prop="date" label="Date" width="140"></el-table-column> -->
            <el-table-column prop="name" label="Name" width="120" align="center"></el-table-column>
            <el-table-column prop="values" label="Values" align="center">
              <template slot-scope="scope">
                <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">Edit</el-button>
                <el-button
                  size="mini"
                  type="danger"
                  @click="handleDelete(scope.$index, scope.row)"
                >Delete</el-button>
              </template>

              <template slot-scope="scope" v-if="scope.row.show">
                <draggable
                  id="first"
                  data-source="juju"
                  :list="scope.row.values"
                  class="list-group"
                  draggable=".item"
                  group="a"
                >
                  <div
                    @mouseleave="renameTag=''"
                    @mouseover="onMouseoverTag(element) "
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
                      <transition name="el-fade-in-linear">
                        <button
                          v-if="renameTag==element.name"
                          class="btn btn-secondary"
                          style="width:5vw;font-size:10px;padding: 0 0 3px 3px"
                          @click="onRenameTag(scope.$index , e_i)"
                        >Rename</button>
                      </transition>
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
            <el-table-column label="Actions" width="220" align="center">
              <template slot="header">
                <!-- <el-button size="mini" @click="handleEdit( )">Edit</el-button> -->

                <el-input
                  v-model="search"
                  size="mini"
                  style="width:200px;float:right"
                  placeholder="Type to search"
                  prefix-icon="el-icon-search"
                />
              </template>
              <template slot-scope="scope">
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
              </template>
            </el-table-column>
          </el-table>
        </el-main>
      </el-container>
    </el-container>

    <el-dialog :title="form.title" :visible.sync="dialogFormVisible">
      <el-form :model="form">
        <el-form-item v-if="placeHolder" label="Tags" label-width="200">
          <el-badge
            v-for="(item, gtvi) in groupedTags.values"
            :key="gtvi"
            is-dot
            class="item"
            style="margin-right:5px"
          >
            <el-button class="share-button" plain icon="el-icon-share" type="primary">{{item}}</el-button>
          </el-badge>
        </el-form-item>
        <el-form-item label label-width="0">
          <el-input v-model="form.name" :placeholder="placeHolder" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">Cancel</el-button>
        <el-button type="primary" @click="onConfirmDialog">Confirm</el-button>
      </span>
    </el-dialog>
    <el-card
      style="z-index:100;position:fixed;bottom:10px;right:10px;padding:0"
      :style="savePosition.style"
    >
      <div>
        <i
          style="cursor:pointer"
          class="el-icon-caret-top"
          @click="savePosition.style = 'bottom:620px;right:10px'"
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
  </div>
</template>

<script>
import draggable from "vuedraggable";
import { mapActions, mapGetters, mapState, mapMutations } from "vuex";

let id = 1;
export default {
  name: "two-list-headerslots",
  display: "Two list header slot",
  order: 14,
  computed: {
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
          .filter(
            data =>
              !this.search ||
              data.name.toLowerCase().includes(this.search.toLowerCase())
          );
      } catch (e) {
        return this.lists;
      }
    },
    placeHolder() {
      return this.form.title == "Handle Unify"
        ? "Choose new name for selected tags"
        : "";
    },
    listMainValues() {
      return this.lists.find(l => l.name == "listMain").values; //,
    },

    //  e => e.name
    //);
    listMainName() {
      try {
        return this.lists.find(l => l.name == "listMain").name;
      } catch (e) {}
    }
  },
  async created() {
    let allTags = "";
    let DBTAGS = await this.Tags();
    console.log({ DBTAGS });
    if (DBTAGS.length) {
      //&& 1 == 2
      this.allTags = DBTAGS;

      this.lists = Object.values(DBTAGS);
      this.lists = Object.entries(_.groupBy(DBTAGS, "header")).map(entry => {
        return {
          name: entry[0],
          show: true,
          values: entry[1]
        };
      });
      return console.log("this.lists");
    }
    console.log("start");
    allTags = await this.tagsUnique();
    console.log(allTags);
    this.allTags = allTags;
    this.lists.find(l => l.name == "listMain").values = allTags;

    console.log("end");
  },
  components: {
    draggable
  },
  data() {
    return {
      savePosition: { style: "" },
      groupedTags: { listName: "", values: [] },
      renameTag: "",
      allTags: [],
      form: {
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
  methods: {
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
      if (!this.groupedTags.listName) this.groupedTags.listName = payload.name;
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
        if (!this.groupedTags.values.length) this.groupedTags.listName = "";
      }
    },
    onConfirmDialog() {
      this.dialogFormVisible = false;
      if (this.form.title == "Add new header") {
        return this.lists.push({
          name: this.form.name,
          values: [],
          show: true
        });
      }
      if (this.form.title == "Rename header")
        this.lists[this.form.index].name = this.form.name;
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
      //return console.log(a);
      this.renameTag = a.name;
      //return console.log(this.lists[a]);
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
      this.form.title = "Rename header";
      this.form.index = index;
      this.dialogFormVisible = true;
    },
    changeData(e) {
      console.log(e.target.innerText);
      this.groupedTags.listName = e.target.innerText;
    },
    ...mapActions([
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
</style>