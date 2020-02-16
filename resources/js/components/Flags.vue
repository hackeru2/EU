<template>
  <el-select
    v-model="value1"
    multiple
    placeholder="Select Flags"
    style="width:100%"
    @change="setUpdateFlags(value1)"
  >
    <el-option v-for="(item) in options" :key="item.value" :label="item.label" :value="item.value"></el-option>
  </el-select>
  <!-- <el-transfer
      @change="$emit('flagsChange', value)"
      v-model="value"
      ref="transfer"
      :titles="['Source', 'Target']"
      :button-texts="['Remove', 'Add']"
      :data="flags"
      style="text-align:left"
  ></el-transfer>-->
</template>

<script>
import { mapMutations } from "vuex";
export default {
  async mounted() {
    let flags = await axios.get("flag").then(({ data: flags }) => {
      return flags.map(f => {
        f.key = f.id;
        f.value = f.id;
        f.label = f.name;
        return f;
      });
    });
    try {
      this.value1 = await this.$store.getters.authUser.flags.map(f => f.id);
    } catch (e) {}
    // console.log(flags);
    this.setUpdateFlags(this.value1);
    this.flags = flags;
  },
  data() {
    // const generateData = _ => {
    //   const data = [];
    //   for (let i = 1; i <= 15; i++) {
    //     data.push({
    //       key: i,
    //       label: `Option ${i}`
    //       // disabled: i % 4 === 0
    //     });
    //   }
    //   return data;
    // };
    return {
      //  data: generateData(),
      _options: [
        {
          value: "Option1",
          label: "Option1"
        },
        {
          value: "Option2",
          label: "Option2"
        },
        {
          value: "Option3",
          label: "Option3"
        },
        {
          value: "Option4",
          label: "Option4"
        },
        {
          value: "Option5",
          label: "Option5"
        }
      ],
      flags: [],
      // value: [],
      value1: []
    };
  },
  computed: {
    options() {
      return this.flags.map(f => {
        return { value: f.value, label: f.label };
      });
    }
  },
  methods: {
    ...mapMutations(["setUpdateFlags"]),
    onTransferChange(e) {}
  }
};
</script>