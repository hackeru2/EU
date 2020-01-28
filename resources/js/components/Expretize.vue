<template>
  <el-card>
    <el-row>
      <el-checkbox-group v-model="checkboxGroup1">
        <el-checkbox-button v-for="city in cities" :label="city" :key="city">{{city}}</el-checkbox-button>
      </el-checkbox-group>
    </el-row>
  </el-card>
</template>
<script>
const cityOptions = [
  "Development",
  "Services",
  "Borders and security",
  "Budget",
  "Business and industry",
  "Climate action",
  "Competition",
  "Consumers",
  "Culture and media",
  "Customs",
  "iety",
  "e euro",
  "Education and training",
  "ffairs",
  "Energy",
  "Environment",
  "EU enlargement",
  "policy",
  "Food safety",
  "urity policy",
  "Fraud prevention",
  "Home affairs",
  "vil protection",
  "Institutional affairs",
  "on and development",
  "rights",
  "sheries",
  "Migration and asylum",
  "Public health",
  "Regional policy",
  "Research and innovation",
  "Single market",
  "Sport",
  "Statistics",
  "Taxation",
  "Trade",
  "Transport",
  "Youth"
];

export default {
  data() {
    return {
      checkboxGroup1: ["Shanghai"],
      checkboxGroup2: ["Shanghai"],
      checkboxGroup3: ["Shanghai"],
      checkboxGroup4: ["Shanghai"],
      cities: cityOptions
    };
  }
};
</script>