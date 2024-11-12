<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray" body-class="p-0">
          <template #title>
            Results <i v-show="loadingTable" class="fa fa-spinner fa-spin"></i>
          </template>
          <template #title_right>
            <Link
              class="mr-2"
              type="button"
              varient="light"
              :href="route('result.create')"
            >
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
              <Dropdown animate stay header="Filter" id="filterindexDropdown">
                <template #btn>
                  <i class="fa fa-filter"></i>
                </template>
                <div class="p-2">
                  <Select
                    v-model="filter.class_id"
                    label-text="Class"
                    from="classes.get.select"
                  />
                  <Select
                    v-model="filter.student_id"
                    label-text="Student"
                    :depend-on="{class_id: filter.class_id}"
                    from="student.get.select"
                  />
                  <Select
                    v-model="filter.exam_id"
                    label-text="Exam"
                    from="exam.get.select"
                  />
                </div>
              </Dropdown>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover y-middle text-nowrap">
              <thead class="bg-gray-1">
                <tr>
                  <th v-show="columns.student_id">Student Id</th>
                  <th v-show="columns.subject_id">Subject Id</th>
                  <th v-show="columns.total_mark_obtain">Result</th>
                  <th v-show="columns.result">Detail</th>
                  <th v-show="columns.action" style="width: 30px"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(result, index) in results.data">
                  <td v-show="columns.student_id">
                    <div class="d-flex flex-column">
                      <span>{{ result.student_name }}</span>
                      <span>{{ result.exam_name }}</span>
                      <span>{{ result.class_name }}</span>
                    </div>
                  </td>
                  <td v-show="columns.subject_id">{{ result.subject_name }}</td>
                  <td v-show="columns.total_mark_obtain">
                    <div class="d-flex flex-column">
                      <span class="font-weight-bold"
                        >Marks: {{ result.total_mark_obtain }}</span
                      >
                      <span>Grade: {{ result.grade }}</span>
                      <span>Point: {{ result.point }}</span>
                    </div>
                  </td>
                  <td v-show="columns.result">
                    <div class="d-flex flex-column" style="min-width: 100px">
                      <div
                        v-for="(res, ind) in result.result"
                        :key="'rescri' + index"
                        class="d-flex flex-row justify-content-between"
                        :class="{
                          'text-danger font-weight-bold': res.status !== 1,
                          'text-dark': res.status === 1,
                        }"
                      >
                        <span>{{ res.short_title }}</span>
                        <span>{{ res.mark_obtain }}</span>
                      </div>
                    </div>
                  </td>
                  <td v-show="columns.action" class="text-right">
                    <Dropdown
                      stay
                      :header="result.name"
                      :id="'indexindex' + index"
                    >
                      <Button
                        btnDropdown
                        type="button"
                        @click="deleteindex(result.delete_url)"
                      >
                        <i class="fa fa-trash"></i> Delete
                      </Button>
                      <Button
                        btnDropdown
                        type="button"
                        @click="showModal(index)"
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
            <Pagination @traped="loadingTable = true" :items="results" />
          </div>
        </Card>
      </div>
    </div>
  </Content>
  <Modal
    id="academic_result_index_create_modal"
    :title="modalTitle"
    varient="light"
  >
    <template #body>
      <form @submit.prevent="submit" novalidate="novalidate">
        <Input
          v-model="form.student_id"
          field="student_id"
          label="Student Id"
          :form="form"
        />
        <Input
          v-model="form.class_id"
          field="class_id"
          label="Class Id"
          :form="form"
        />
        <Input
          v-model="form.exam_id"
          field="exam_id"
          label="Exam Id"
          :form="form"
        />
        <Input
          v-model="form.subject_id"
          field="subject_id"
          label="Subject Id"
          :form="form"
        />
        <Input
          v-model="form.total_mark_obtain"
          field="total_mark_obtain"
          label="Total Mark Obtain"
          :form="form"
        />
        <Input v-model="form.point" field="point" label="Point" :form="form" />
        <Input v-model="form.grade" field="grade" label="Grade" :form="form" />
        <Input
          v-model="form.status"
          field="status"
          label="Status"
          :form="form"
        />
        <Input
          v-model="form.result"
          field="result"
          label="Result"
          :form="form"
        />
      </form>
    </template>

    <template #action>
      <Button :isLoading="form.processing" :hideLabel="true" @click="submit">
        <i class="fa fa-save"></i>
      </Button>
    </template>
  </Modal>
  <DeleteConfirm :deleteUrl="deleteUrl" item="index" />
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
import { reactive, ref } from "vue";
import { isRequired } from "intus/rules";

export default {
  name: "Index",
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
    results: Object,
    params: Object,
  },
  data() {
    return {
      form: useValidateForm({
        student_id: [null, [isRequired()]],
        class_id: [null, [isRequired()]],
        exam_id: [null, [isRequired()]],
        subject_id: [null, [isRequired()]],
        total_mark_obtain: [null, [isRequired()]],
        point: [null, [isRequired()]],
        grade: [null, [isRequired()]],
        status: [null, [isRequired()]],
        result: [null, [isRequired()]],
      }),

      filter: reactive({
        search: this.params.search ?? null,
        per_page: this.params.per_page ?? 5,
        page: this.params.page ?? null,
        student_id: this.params.student_id ?? null,
        class_id: this.params.class_id ?? null,
        exam_id: this.params.exam_id ?? null,
      }),
      columns: reactive({
        id: true,
        student_id: true,
        class_id: true,
        exam_id: true,
        subject_id: true,
        total_mark_obtain: true,
        point: true,
        grade: true,
        status: true,
        result: true,
        action: true,
      }),

      modal: { form: null, confirm: null },
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
        if (state.class_id) query.class_id = state.class_id;
        if (state.student_id) query.student_id = state.student_id;
        if (state.exam_id) query.exam_id = state.exam_id;

        this.getindices(query);
      }, 1000),
      deep: true,
    },
  },
  mounted() {
    let form = document.querySelector("#academic_result_index_create_modal");
    let confirm = document.querySelector("#confirmModel");
    this.modal.form = new bootstrap.Modal(form);
    this.modal.confirm = new bootstrap.Modal(confirm);
  },
  methods: {
    getindices(filter = {}) {
      this.loadingTable = true;
      Inertia.get(this.route("result.index"), filter, {
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
    showModal(data = null) {
      this.isEditing = data !== null;
      this.modalTitle = data == null ? "Create Index" : "Update Index";
      this.form.clearErrors();
      if (this.isEditing) {
        this.editUrl = data.edit_url;
        this.form.student_id = data.student_id;
        this.form.class_id = data.class_id;
        this.form.exam_id = data.exam_id;
        this.form.subject_id = data.subject_id;
        this.form.total_mark_obtain = data.total_mark_obtain;
        this.form.point = data.point;
        this.form.grade = data.grade;
        this.form.status = data.status;
        this.form.result = data.result;
      } else {
        this.form.reset();
      }
      this.modal.form.show();
    },
    submit() {
      this.form.clearErrors();
      var url = this.isEditing ? this.editUrl : route("result.store");
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
    deleteindex(url) {
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
