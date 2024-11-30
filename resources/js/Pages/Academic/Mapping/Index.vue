<template>
  <Content>
    <div class="row">
      <div class="col-md-6">
        <Card varient="gray" body-class="p-0">
          <template #title>
            Subject Mapping
            <i v-show="loading" class="fa fa-spinner fa-spin"></i>
          </template>
          <template #title_right>
            <Link :href="route('exam.map.create')" >Create</Link>
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
              <Dropdown
                animate
                stay
                header="Filter"
                id="filtersubjectmappingDropdown"
              >
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
            <table class="table table-hover y-middle text-nowrap">
              <thead class="bg-gray-1">
                <tr>
                  <th>Exam</th>
                  <th>Class</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr 
                  v-for="(subjectmapping, index) in subjectmappings" 
                  :key="index" 
                  @click="handleRowClick(index)"
                  :class="{ 'selected': index == detail.index }"
                >
                  <td>{{ subjectmapping.exam }}</td>
                  <td>{{ subjectmapping.class }}</td>
                  
                  <td class="text-right">
                    <Dropdown
                      stay
                      :header="subjectmapping.name"
                      :id="'subjectmappingindex' + index"
                    >
                      <Button
                        btnDropdown
                        type="button"
                        @click="deleteMapping(subjectmapping.exam_id, subjectmapping.class_id)"
                      >
                        <i class="fa fa-trash"></i> Delete
                      </Button>
                    </Dropdown>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </Card>
      </div>
      <div class="col-md-6">
        <Card title="Detail" v-if="subjectmappings[detail.index]" >
          <div class="text-center">
            <h3>{{ subjectmappings[detail.index].exam }}</h3>
            <h4>{{ subjectmappings[detail.index].class }}</h4>
          </div>
          <div v-if="subjectmappings[detail.index].subjects">
            <div v-for="(item, subject_name) in subjectmappings[detail.index].subjects" :key="subject_name">
              <div class="row">
                <div class="col-6"><strong>{{ subject_name }}</strong></div>
                <div class="col-6 text-right">Full Mark: {{ item.full_mark }}</div>
              </div>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th style="width: 100px;">Full Mark</th>
                    <th style="width: 100px">Pass Mark</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(sec, ind) in item.criteria">
                    <td>{{ sec.title }} ({{ sec.short_title }})</td>
                    <td>{{ sec.full_mark }}</td>
                    <td>{{ sec.pass_mark }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            
          </div>
        </Card>
      </div>
    </div>
    <DeleteConfirm :deleteUrl="deleteUrl" item="exam" />
  </Content>
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

export default {
  name: "SubjectMapping",
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
    subjectmappings: Object,
    params: Object,
  },
  data() {
    return {
      filter: reactive({
        search: this.params.search ?? null,
        per_page: this.params.per_page ?? 5,
      }),
      detail: reactive({
        index: null,
        data: null,
      }),
      loading: false,
      deleteUrl: null,
      confirm_model: undefined,
    };
  },
  watch: {
    filter: {
      handler: _.debounce(function (state, old) {
        let query = {};
        if (state.search) query.search = state.search;

        this.getsubjectmappings(query);
      }, 1000),
      deep: true,
    },
  },
  mounted() {
    let confirm = document.querySelector("#confirmModel");
    this.confirm_model = new bootstrap.Modal(confirm);
    if(this.subjectmappings.length){
      this.detail.index = 0;
      this.detail.data = this.subjectmappings[0];
    }
  },
  methods: {
    handleRowClick(index){
      this.detail.index = index
    },
    deleteMapping(exam_id, class_id) {
        this.deleteUrl = route('exam.map.delete', {exam_id, class_id});
        this.confirm_model.show();
        Inertia.on("finish", () => {
            this.deleteUrl = null;
            this.confirm_model.hide();
        });
    },
    
    getsubjectmappings(filter = {}) {
      this.loading = true;
      Inertia.get(this.route("exam.map.index"), filter, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onSuccess: () => {
          this.loading = false;
        },
        onError: (error) => {
          this.loading = false;
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
  },
};
</script>


<style scoped>
  .selected {
    background: #6a76ffbf!important;
  }
  .items_container {
    --item: #e6e6e6;
    border: 1px solid var(--item);
    border-radius: 4px;
    margin-bottom: 5px;
  }
  .item_header {
    padding: 8px 10px;
    background: var(--item);
  }
  .item_header span {
    color: #000;
    font-weight: bold;
    font-size: 1.35rem;
  }
  .item {
    padding: 8px;
    overflow: visible;
  }
</style>
