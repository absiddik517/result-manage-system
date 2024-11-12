<template>
  <Content>
    <div class="row">
      <div class="col-md-6">
        <Card varient="gray" body-class="p-0">
          <template #title>
            Classes <i v-show="loadingTable" class="fa fa-spinner fa-spin"></i>
          </template>
          <template #title_right>
            <Link :href="route('classes.create')">Create</Link>
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
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover y-middle text-nowrap">
              <thead class="bg-gray-1">
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Subject Count</th>
                  <th style="width: 30px"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in classes.data" :key="index" @click="handleRowClick(index)">
                  <td>{{ item.id }}</td>
                  <td>{{ item.name }}</td>
                  <td>{{ item.total_subjects }}</td>
                  <td class="text-right">
                    <Dropdown
                      stay
                      :header="item.name"
                      :id="'classindex' + index"
                    >
                      <Button
                        btnDropdown
                        type="button"
                        @click="deleteclass(item.delete_url)"
                      >
                        <i class="fa fa-trash"></i> Delete
                      </Button>
                    </Dropdown>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="p-2">
            <Pagination @traped="loadingTable = true" :items="classes" />
          </div>
        </Card>
      </div>
      <div class="col-md-6" v-if="class_index !== null">
        <Card varient="dark" :title="classes.data[class_index].name" body-class="p-0">
          <template #title_right>
            <Link :href="route('classes.edit', classes.data[class_index].id)">Edit</Link>
          </template>
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Short</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="(subjects, group) in table_data">
                <tr v-if="classes.data[class_index].has_group">
                  <td colspan="3" class="text-center"><strong>{{ group }}</strong></td>
                </tr>
                
                <tr v-for="(subject, index) in subjects">
                  <td>{{ index+1 }}</td>
                  <td>{{ subject.name }}</td>
                  <td>{{ subject.short_name }}</td>
                </tr>
              </template>
            </tbody>
          </table>
        </Card>
      </div>
    </div>
  </Content>
  <DeleteConfirm :deleteUrl="deleteUrl" item="class" />
</template>

<script>
import {
  AdminLayout,
  Modal,
  Card,
  Input,
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
import {useForm} from "@inertiajs/inertia-vue3";
import { reactive, ref } from "vue";

export default {
  name: "Classes",
  layout: AdminLayout,
  components: {
    Spinner,
    Modal,
    Input,
    Content,
    DeleteConfirm,
    Card,
    Button,
    Dropdown,
    Filterth,
    Pagination,
  },
  props: {
    classes: Object,
    params: Object,
  },
  data() {
    return {
      filter: reactive({
        search: this.params.search ?? null,
        per_page: this.params.per_page ?? 5,
      }),
      class_index:null,
      modal: { confirm: null },
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

        this.getclasses(query);
      }, 1000),
      deep: true,
    },
  },
  mounted() {
    let confirm = document.querySelector("#confirmModel");
    this.modal.confirm = new bootstrap.Modal(confirm);
  },
  methods: {
    getclasses(filter = {}) {
      //console.log(this.classes);
      this.loadingTable = true;
      Inertia.get(this.route("classes.index"), filter, {
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
    handleRowClick(index){
      this.class_index = index
    },
    showModal(data = null) {
      this.isEditing = data !== null;
      this.modalTitle = data == null ? "Create Class" : "Update Class";
      this.form.clearErrors();
      if (this.isEditing) {
        this.editUrl = data.edit_url;
        this.form.name = data.name;
        this.form.short_name = data.short_name;
        const sub = [];
        console.log(data)
        for(let i = 0; i < data.subjects.length; i++){
          sub.push({
            id: data.subjects[i].id,
            name: data.subjects[i].name,
            short_name: data.subjects[i].short_name,
          })
        }
        this.form.subjects = sub
      } else {
        this.form.reset();
      }
      this.modal.form.show();
    },
  },
  computed: {
    table_data(){
      const res = this.classes.data[this.class_index]?.subjects.reduce((result, subject) => {
          const groupKey = subject.group || "Common"; // Use "No Group" for null or undefined groups
          if (!result[groupKey]) {
              result[groupKey] = [];
          }
          result[groupKey].push(subject);
          return result;
      }, {});
      console.log(res);
      return res;
    }
  }
};
</script>


<style scoped>
  
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
