<template>
  <el-card>
    <el-row>
      <el-checkbox-group v-model="cityData" @change="$emit('cities',cityData)">
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
  "Digital economy and society",
  "Economy, finance and the euro",
  "Education and training",
  "Employment and social affairs",
  "Energy",
  "Environment",
  "Food safety",
  "Foreign affairs and security policy",
  "Fraud prevention",
  "Home affairs",
  "Humanitarian aid and civil protection",
  "Institutional affairs",
  "International cooperation and development",
  "Justice and fundamental rights",
  "Maritime affairs and fisheries",
  "Migration and asylum",
  "Public health",
  "Regional policy",
  "Research and innovation",
  "Single market",
  "Society",
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
      cityData: [""],
      // checkboxGroup2: ["Shanghai"],
      // checkboxGroup3: ["Shanghai"],
      // checkboxGroup4: ["Shanghai"],
      cities: cityOptions
    };
  }
};
</script>