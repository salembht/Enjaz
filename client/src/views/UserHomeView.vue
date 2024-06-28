<template>
  <div class="home">
    <VDatePicker v-model="date" />
   
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "UserHomeView",
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
    async getuser() {
      try {
        const response = await axios.get("http://localhost:8000/api/user");
        this.user = response.data;
      } catch (error) {
        console.error(error);
        this.user = "Error: Unable to fetch user data";
      }
    },
  },
  async created() {
    await this.getuser();
  },
};
</script>