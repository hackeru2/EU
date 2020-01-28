<template>
  <el-card>
    <el-transfer v-model="value" :data="flags" style="text-align:left"></el-transfer>
  </el-card>
</template>

<script>
export default {
  async mounted() {
    let flags = await axios.get("flag").then(({ data: flags }) => {
      return flags.map(f => {
        f.key = f.id;
        f.label = f.name;
        return f;
      });
    });
    console.log(flags);
    this.flags = flags;
  },
  data() {
    const generateData = _ => {
      const data = [];
      for (let i = 1; i <= 15; i++) {
        data.push({
          key: i,
          label: `Option ${i}`
          // disabled: i % 4 === 0
        });
      }
      return data;
    };
    return {
      //  data: generateData(),
      flags: [],
      value: [1, 4]
    };
  }
};
</script>