<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray" body-class="p-0">
          <template #title> Exam <i v-show="loadingTable" class="fa fa-spinner fa-spin"></i>
          </template>
          <template #title_right>
            <Button class="mr-2" type="button" varient="light" @click="showModal(null)">
              Create
            </Button>
          </template>
          <div class="row p-2 gy-2">
            <div class="col-6">
              <input v-model="filter.search" type="text" placeholder="Search..." class="form-control" />
              <i v-show="filter.search" @click="filter.search = null" class="fa fa-times filter-close" style="right: 15px;"></i>
            </div>
            <div class="col-6 d-flex justify-content-end align-items-center">
              Show
              <select v-model="filter.per_page" class="ml-2 select_per_page" id="per_page">
                <option disabled value="null">🔻</option>
                <option v-for="index in 100" :value="index * 5">{{ index * 5 }}</option>
                <option value="all">All</option>
              </select>
              <Dropdown animate stay header="Filter" id="filterexamDropdown">
                <template #btn>
                  <i class="fa fa-filter"></i>
                </template>
                
                <div class="px-2 py-1">
                  <Dropdown animate stay header="Toggle Collumn" id="filterColumnToggle">
                    <template #btn>
                      <i class="fa fa-eye"></i>  Collumn visibility
                    </template>
                    
                    <label :for="field + 'dropdown'" class="dropdown-item" v-for="(value, field) in columns">
                      <input v-model="columns[field]" type="checkbox" :id="field + 'dropdown'"> 
                      {{ field.replace('_', ' ').toUpperCase() }}
                    </label>
                  </Dropdown>
                </div>
              </Dropdown>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover y-middle text-nowrap">
              <div v-show="loadingTable" class="overlays">
                <span>Loading... <i class="fa fa-spin fa-spinner"></i></span>
              </div>
              <thead class="bg-gray-1">
                <tr>
                  <th v-show="columns.id">ID</th>
                  <th v-show="columns.name"> Name </th>
<th v-show="columns.short_name"> Short Name </th>
                  <th v-show="columns.action" style="width: 30px;"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(exam, index) in exams.data">
                  <td v-show="columns.id">{{ exam.id }}</td>
                                      <td v-show="columns.name">{{ exam.name }}</td>
                    <td v-show="columns.short_name">{{ exam.short_name }}</td>
                  <td v-show="columns.action" class="text-right">
                    <Dropdown stay :header="exam.name" :id="'examindex' + index">
                      <Button
                        btnDropdown
                        type="button"
                        @click="deleteexam(exam.delete_url)"
                      >
                        <i class="fa fa-trash"></i> Delete
                      </Button>
                      <Button btnDropdown type="button" @click="showModal(exam)">
                        <i class="fa fa-edit"></i> Edit
                      </Button>
                    </Dropdown>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="p-2">
            <Pagination @traped="loadingTable = true" :items="exams" />
          </div>
        </Card>
      </div>
    </div>
  </Content>
  <Modal id="academic_exam_create_modal" :title="modalTitle" varient="light">
    <template #body>
      <form @submit.prevent="submit" novalidate="novalidate">
                  <Input v-model="form.name" field="name" label="Name" :form="form" />
          <Input v-model="form.short_name" field="short_name" label="Short Name" :form="form" />
      </form>
    </template>

    <template #action>
      <Button :isLoading="form.processing" :hideLabel="true" @click="submit">
        <i class="fa fa-save"></i>
      </Button>
    </template>
  </Modal>
  <DeleteConfirm :deleteUrl="deleteUrl" item="exam"/>
</template>

<script>
import {
  AdminLayout, Modal, Card, Input, DeleteConfirm, Spinner, Dropdown, Pagination, Filterth, Button, Content, useValidateForm,
} from "@/Components";
import toast from '@/Store/toast.js';
import _ from "lodash";
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref } from "vue";
import { isRequired } from "intus/rules";

export default {
  name: "Exam",
  layout: AdminLayout,
  components: {
    Spinner, Modal, Input, Content, DeleteConfirm, Card, Button, Dropdown, Filterth, Pagination,
  },
  props: {
    exams: Object,
    params: Object,
  },
  data() {
    return {
      form: useValidateForm({
                  name: [null, [isRequired()]],
          short_name: [null, [isRequired()]],
      }),

      filter: reactive({
        search: this.params.search ?? null,
        per_page: this.params.per_page ?? 5,
      }),
      columns: reactive({
        id: true,
                name: true, 
        short_name: true, 
        action: true
      }),

      modal: {form: null, confirm: null},
      modalTitle: null,
      isEditing: false,
      editUrl: null,
      deleteUrl: null,
      loadingTable: false,
    };
  },
  watch: {
    filter: {
      handler: _.debounce(function (state, old) {
        let query = {};
        if (state.search) query.search = state.search;
        if (state.per_page) query.per_page = state.per_page;
        
        this.getexams(query);
      }, 1000),
      deep: true,
    },
  },
  mounted() {
    let form = document.querySelector("#academic_exam_create_modal");
    let confirm = document.querySelector("#confirmModel");
    this.modal.form = new bootstrap.Modal(form);
    this.modal.confirm = new bootstrap.Modal(confirm);
  },
  methods: {
    getexams(filter = {}) {
      this.loadingTable = true;
      Inertia.get(this.route("exam.index"), filter, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onSuccess: () => {
          this.loadingTable = false;
        },
        onError: error => {
          this.loadingTable = false;
          let message = '';
          for(let key in error){
            message += error[key] + ' ';
          }
          toast.add({
            type: 'error',
            message
          })
        }
      });
    },
    showModal(data = null) {
      this.isEditing = data !== null;
      this.modalTitle = data == null ? "Create Exam" : "Update Exam";
      this.form.clearErrors();
      if (this.isEditing) {
        this.editUrl = data.edit_url;
                  this.form.name = data.name;
          this.form.short_name = data.short_name;
      } else {
        this.form.reset();
      }
      this.modal.form.show();
    },
    submit() {
      this.form.clearErrors();
      var url = this.isEditing ? this.editUrl : route("exam.store");
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
    deleteexam(url) {
      this.deleteUrl = url;
      this.modal.confirm.show();
      Inertia.on('finish', () => {
        this.deleteUrl = null
        this.modal.confirm.hide()
      })
    },
  },
};
</script>

