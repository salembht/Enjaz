<template>
  <div class="row">


  <aside class="col-2 m-5 bd-aside sticky-xl-top text-muted align-self-start mb-3 mb-xl-5 px-2">
    <VDatePicker v-model="date" onchange="formattedDate" />
  </aside>
 
  <div  class=" col-8 container m-0 bd-cheatsheet container-fluid bg-body">
    <h1>مهماتي اليومية</h1>

    <div class="row mb-3">
      <div class="col-8">
        <input type="text" class="form-control" v-model="newTasktitle" placeholder="ادخل المهمة الجديدة" />
      </div>
      <div class="col-4">
        <button class="btn btn-primary" @click="createTask">اضافة مهمة</button>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <table class="table table-striped">
          <thead>
            <tr>
             
              <th>المهمة</th>
              <th>حالتها</th>
              <th>العمليات </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="task in uncompletedTask" :key="task.id">
             
              <td>{{ task.title }}</td>
              <td>
                <span class="text-danger">
                  لم تكتمل
                </span>
              </td>
              <td>
                <button class="btn btn-sm btn-primary  m-1" @click="updateTaskStatus(task.id, true)">
                  اكملتها
                </button>
                <button class="btn btn-sm btn-warning m-1" @click="showEditModal(task)">
                  تعديل
                </button>
                <button class="btn btn-sm btn-danger m-1" @click="deleteTask(task.id)">
                  حذف
                </button>
              </td>
            </tr>
            <tr>
              <td></td>
            </tr>
            <tr v-for="task in completedTask" :key="task.id">
              <td>{{ task.title }}</td>
              <td>
                <span class="text-success"> مكتملة
                </span>
              </td>
              <td>
                <button class="btn btn-sm  btn-primary m-1" @click="updateTaskStatus(task.id, false)">
                  غير مكتملة
                </button>
                <button class="btn btn-sm btn-warning m-1" @click="showEditModal(task)">
                  تعديل
                </button>
                <button class="btn btn-sm btn-danger m-1" @click="deleteTask(task.id)">
                  حذف
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal" tabindex="-1" role="dialog" :class="{ 'd-block': showModal }">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">تعديل المهمة</h5>
            <button type="button" class="close" @click="closeModal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="text" class="form-control" v-model="editedTask.title" />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="updateTask">
              حفظ
            </button>
            <button type="button" class="btn btn-secondary" @click="closeModal">
              الغاء
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  
 
</template>

<script>
/* eslint-disable */
import axios from "axios";
import Swal from "sweetalert2";
export default {
  name: "UserHomeView",
  data() {
    return {
      user: null,
      date: new Date(),
      tasks: [],
      newTasktitle: "",
      editedTask: 'null',
      showModal: false,
      taskDate: this.date,
    };
  },
  computed: {
    // return the taskes after filtering 
    completedTask() {
    return this.tasks.filter((task) => task.status == "complate" && task.date == this.taskDate);
  },
  uncompletedTask() {
    return this.tasks.filter((task) => task.status == null && task.date == this.taskDate);
  }

  },
  methods: {

    async formattedDate() {
      const year = this.date.getFullYear();
      const month = String(this.date.getMonth() + 1).padStart(2, '0');
      const day = String(this.date.getDate()).padStart(2, '0');
      this.taskDate = `${year}-${month}-${day}`;
      this.getTasks();
    },
    async getTasks() {
  try {
    const response = await axios.get("http://localhost:8000/api/tasks");
    this.tasks = response.data;
    return response.data;
  } catch (error) {
    console.error(error);
    return [];
  }
},
    createTask() {
      if (this.newTasktitle.trim() !== '') {
        const csrfToken = this.getCsrfToken();


        axios.post('http://localhost:8000/api/tasks', {
          title: this.newTasktitle,
          date: this.taskDate,
          _token: csrfToken
        })
          .then(response => {
            this.tasks.push(response.data);
            this.newTasktitle = '';
          })
          .catch(error => {
            console.error(error);
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: error.response ? error.response.data.message : 'An error occurred. Please try again.'
            });
          });
      }
    },

    updateTaskStatus(taskId, completed) {
      const route = completed
        ? `http://localhost:8000/api/tasks/complete/${taskId}`
        : `http://localhost:8000/api/tasks/uncomplete/${taskId}`;
      const csrfToken = this.getCsrfToken();
      axios
        .post(route, { _token: csrfToken })
        .then(() => {
          this.getTasks();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    showEditModal(task) {
      this.editedTask = { ...task };
      this.showModal = true;
    },
    updateTask() {

      const csrfToken = this.getCsrfToken();
      axios.put(`http://localhost:8000/api/tasks/${this.editedTask.id}`, {
        title: this.editedTask.title,
        _token: csrfToken
      })
        .then(() => {
          this.getTasks();
          this.closeModal();
        })
        .catch(error => {
          console.error(error);
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error.response ? error.response.data.message : 'An error occurred. Please try again.'
          });
        });
    },
    deleteTask(taskId) {

      const csrfToken = this.getCsrfToken();
      axios
        .delete(`http://localhost:8000/api/tasks/${taskId}`, { _token: csrfToken })
        .then(() => {
          this.getTasks();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    closeModal() {
      this.showModal = false;
      this.editedTask = 'null';
    },
    getCsrfToken() {
      const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
      if (csrfTokenMeta) {
        return csrfTokenMeta.getAttribute("content");
      } else {
        console.error("CSRF token meta tag not found.");
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "CSRF token not found. Please refresh the page and try again.",
        });
        return "";
      }
    },
  },
  async created() {
    this.tasks = await this.getTasks();
  await this.formattedDate();
  },
  watch: {
    date: {
      handler: async function (newValue) {
        await this.formattedDate();
      },
      immediate: true,
    },
    
  },
};
</script>
