<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray" body-class="p-0">
          <template #title>
            Student <i v-show="loadingTable" class="fa fa-spinner fa-spin"></i>
          </template>
          <template #title_right>
            <Link :href="route('student.create')">
              Create
            </Link>
          </template>
          <div class="row p-2 gy-2">
            <div class="col-6">
              <input
                v-model="filter.search"
                type="text"
                placeholder="Search..."
                class="form-control"
              />
              <i
                v-show="filter.search"
                @click="filter.search = null"
                class="fa fa-times filter-close"
                style="right: 15px"
              ></i>
            </div>
            <div class="col-6 d-flex justify-content-end align-items-center">
              Show
              <select
                v-model="filter.per_page"
                class="ml-2 select_per_page"
                id="per_page"
              >
                <option disabled value="null">🔻</option>
                <option v-for="index in 100" :value="index * 5">
                  {{ index * 5 }}
                </option>
                <option value="all">All</option>
              </select>
              <Dropdown animate stay header="Filter" id="filterstudentDropdown">
                <template #btn>
                  <i class="fa fa-filter"></i>
                </template>

                <div class="px-2 py-1">
                  <Dropdown
                    animate
                    stay
                    header="Toggle Collumn"
                    id="filterColumnToggle"
                  >
                    <template #btn>
                      <i class="fa fa-eye"></i> Collumn visibility
                    </template>

                    <label
                      :for="field + 'dropdown'"
                      class="dropdown-item"
                      v-for="(value, field) in columns"
                    >
                      <input
                        v-model="columns[field]"
                        type="checkbox"
                        :id="field + 'dropdown'"
                      />
                      {{ field.replace("_", " ").toUpperCase() }}
                    </label>
                  </Dropdown>
                </div>
              </Dropdown>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover table-bordered y-middle text-nowrap">
              
              <thead class="bg-gray-1">
                <tr>
                  <th>Identity</th>
                  <th>Student</th>
                  <th>Group</th>
                  <th style="width: 30px"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(student, index) in students.data">
                  <td>
                    <div>{{ student.class_name }}</div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Roll:</span> <strong class="ml-2">{{ student.roll }}</strong></div>
                    <div v-if="student.section" class="d-flex justify-content-between"><span class="text-muted">Section:</span> <strong class="ml-2">{{ student.section }}</strong></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">ID:</span> <strong class="ml-2">{{ student.id }}</strong></div>
                  </td>
                  <td>
                    <div><center><strong>{{ student.name }}</strong></center></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Gender:</span> <span class="ml-2">{{ student.gender }}</span></div>
                  </td>
                  <td v-if="student.group">
                    <div><center><strong>{{ student.group }}</strong></center></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Optional:</span> <span class="ml-2">{{ student.optional }}</span></div>
                  </td>
                  <td v-else></td>
                  
                  <td class="text-right">
                    <Dropdown
                      stay
                      :header="student.name"
                      :id="'studentindex' + index"
                    >
                      <Button
                        btnDropdown
                        type="button"
                        @click="deletestudent(student.delete_url)"
                      >
                        <i class="fa fa-trash"></i> Delete
                      </Button>
                      <Button
                        btnDropdown
                        type="button"
                        @click="showModal(student)"
                      >
                        <i class="fa fa-edit"></i> Edit
                      </Button>
                    </Dropdown>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="p-2">
            <Pagination @traped="loadingTable = true" :items="students" />
          </div>
        </Card>
      </div>
    </div>
  </Content>
  <Modal id="academic_student_create_modal" :title="modalTitle" varient="light"
  :loading="loading">
    <template #body>
      <form @submit.prevent="submit" novalidate="novalidate">
        <Select
          v-model="form.class_id"
          label-text="Class"
          from="classes.get.select"
        />
        <Input v-model="form.roll" field="roll" label="Roll" :form="form" />
        <Input v-model="form.name" field="name" label="Name" :form="form" />
        <Input v-model="form.gender" field="gender" label="Gender" :form="form" />
        <Input v-model="form.section" field="section" label="Section" :form="form" />
        
      </form>
    </template>

    <template #action>
      <Button :isLoading="form.processing" :hideLabel="true" @click="submit">
        <i class="fa fa-save"></i>
      </Button>
    </template>
  </Modal>
  <DeleteConfirm :deleteUrl="deleteUrl" item="student" />
</template>

<script>
import {
  AdminLayout,
  Modal,
  Card,
  Input,
  Select,
  DeleteConfirm,
  Spinner,
  Dropdown,
  Pagination,
  Filterth,
  Button,
  Content,
  useValidateForm,
} from "@/Components";
import toast from "@/Store/toast.js";
import _ from "lodash";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";
import { reactive, ref } from "vue";
import { isRequired } from "intus/rules";

export default {
  name: "Student",
  layout: AdminLayout,
  components: {
    Spinner,
    Modal,
    Input,
    Select,
    Content,
    DeleteConfirm,
    Card,
    Button,
    Dropdown,
    Filterth,
    Pagination,
  },
  props: {
    students: Object,
    classes: Object,
    params: Object,
  },
  data() {
    return {
      form: useForm({
        class_id: null, 
        roll: null, 
        name: null, 
        gender: null, 
        section: null,
      }),

      filter: reactive({
        search: this.params.search ?? null,
        per_page: this.params.per_page ?? 5,
      }),

      modal: { form: null, confirm: null },
      modalTitle: null,
      isEditing: false,
      editUrl: null,
      deleteUrl: null,
      loadingTable: false,
      loading: false,
    };
  },
  watch: {
    filter: {
      handler: _.debounce(function (state, old) {
        let query = {};
        if (state.search) query.search = state.search;
        if (state.per_page) query.per_page = state.per_page;

        this.getstudents(query);
      }, 1000),
      deep: true,
    },
  },
  mounted() {
    let form = document.querySelector("#academic_student_create_modal");
    let confirm = document.querySelector("#confirmModel");
    this.modal.form = new bootstrap.Modal(form);
    this.modal.confirm = new bootstrap.Modal(confirm);
  },
  methods: {
    getstudents(filter = {}) {
      this.loadingTable = true;
      Inertia.get(this.route("student.index"), filter, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onSuccess: () => {
          this.loadingTable = false;
        },
        onError: (error) => {
          this.loadingTable = false;
          let message = "";
          for (let key in error) {
            message += error[key] + " ";
          }
          toast.add({
            type: "error",
            message,
          });
        },
      });
    },
    async getRollNumber() {
      this.loading = true;
      try {
        const response = await axios.get(route('student.roll.get'));
        this.form.roll = response.data;
        console.log(response)
      } catch (error) {
        console.log('Error on getSubjects', error);
      } finally {
        this.loading = false;
      }
    },
    showModal(data = null) {
      this.isEditing = data !== null;
      this.modalTitle = data == null ? "Create Student" : "Update Student";
      this.form.clearErrors();
      if (this.isEditing) {
        this.editUrl = data.edit_url;
        this.form.class_id = data.class_id;
        this.form.roll = data.roll;
        this.form.name = data.name;
        this.form.father_name = data.father_name;
        this.form.mother_name = data.mother_name;
        this.form.address = data.address;
        this.form.phone = data.phone;
      } else {
        this.form.reset();
        this.getRollNumber();
      }
      this.modal.form.show();
    },
    submit() {
      this.form.clearErrors();
      var url = this.isEditing ? this.editUrl : route("student.store");
      this.form.post(url, {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
          this.modal.form.hide();
        },
      });
    },
    update() {
      this.form.clearErrors();
      this.form.post(this.editUrl, {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
          this.modal.form.hide();
        },
      });
    },
    deletestudent(url) {
      this.deleteUrl = url;
      this.modal.confirm.show();
      Inertia.on("finish", () => {
        this.deleteUrl = null;
        this.modal.confirm.hide();
      });
    },
  },
};
</script>
