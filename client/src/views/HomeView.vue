<template>
  <div class="home">
    <VDatePicker v-model="date" />
    <div v-if="user === null">Loading...</div>
    <div v-else>{{ user }}</div>
    
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "HomeView",
  data() {
    return {
      user: null,
      date: new Date(),
    };
  },
  computed: {
    formattedDate() {
      const options = { month: "long", day: "numeric", year: "numeric" };
      return this.date.toLocaleDateString("en-US", options);
    },
  },
  methods: {
    getuser()
    {
      try {
      const response =  axios.get("http://localhost:8000/api/user");
      this.user= response.data;
    } catch (error) {
      return error
    }
    }
  },
  mounted() {
    // No changes here
  },
  async created() {
    await this.getuser();
  },
};
</script>