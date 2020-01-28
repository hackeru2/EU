<template>
  <el-container>
    <el-main>
      <el-collapse v-model="activeName" accordion>
        <el-collapse-item name="1">
          <template slot="title">
            Main Topics
            <i class="header-icon el-icon-info"></i>
          </template>
          <Expretize />
        </el-collapse-item>
        <el-collapse-item title="Flags" name="2">
          <Flags />
        </el-collapse-item>
        <el-collapse-item title="Description" name="3">
          <el-card>
            <el-input type="textarea" :rows="2" placeholder="Please input" v-model="textarea"></el-input>
          </el-card>
        </el-collapse-item>
        <el-collapse-item title="Controllability" name="4">
          <div>Decision making: giving advices about operations is acceptable, but do not make decisions for the users;</div>
          <div>Controlled consequences: users should be granted the freedom to operate, including canceling, aborting or terminating current operation.</div>
        </el-collapse-item>
      </el-collapse>
    </el-main>
  </el-container>
</template>
<script>
import Expretize from "./Expretize";
import Flags from "./Flags";
export default {
  data() {
    return {
      textarea: "",
      activeName: ""
    };
  },
  components: { Expretize, Flags }
};
</script>