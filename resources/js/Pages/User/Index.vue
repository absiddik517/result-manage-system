<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray">
          <template #title>Users <i v-show="loadingTable" class="fa fa-spinner fa-spin"></i> </template>
          <template #title_right>
            <Link :href="route('user.create')">Create</Link>
          </template> 
          
          
          <div class="row mb-3">
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
          
          <div>
            <div v-for="user in users.data" class="user d-flex justify-content-between align-items-center mb-3">
              <div class="flex-1 d-flex">
                <div class="photo-container">
                  <img :src="user.profile_photo_url" :alt="user.name">
                </div>
                <div class="d-flex flex-column">
                  <strong class="font-raleway">{{ user.name }}</strong>
                  <strong class="text-muted"><i class="fa-solid fa-user-shield" style="width: 20px;"></i> {{ user.role ?? 'NOT_SET' }}</strong>
                  <span class="font-quicksand">{{ user.email }}</span>
                </div>
              </div>
              <div class="text-right">
                <Dropdown
                    stay
                    :header="user.name"
                    :id="'permissionindex' + user.email"
                  >
                    <Button
                      btnDropdown
                      type="button"
                    >
                      <i class="fa fa-trash"></i> Delete
                    </Button>
                    <Link
                      :href="route('user.edit', user.id)"
                      class="dropdown-item"
                    >
                      <i class="fa fa-edit"></i> Edit
                    </Link>
                  </Dropdown>
              </div>
            </div>
          </div>
          <div v-if="users.data.length" class="p-2">
            <Pagination @traped="loadingTable = true" :items="users" />
          </div>
        </Card>
      </div>
    </div>
  </Content>
</template>

<script>
import {
  AdminLayout,
  Modal,
  Input,
  Card,
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
  name: "UserIndex",
  layout: AdminLayout,
  components: {
    Spinner,
    Modal,
    Content,
    DeleteConfirm,
    Card,
    Input,
    Button,
    Dropdown,
    Filterth,
    Pagination,
  },
  props: {
    users: Object,
    params: Object,
  },
  data(){
    return {
      loadingTable: false,
      filter: reactive({
        search: this.params.search,
        per_page: this.params.per_page,
      })
    }
  },
  
  watch: {
    filter: {
      handler: _.debounce(function (state, old) {
        let query = {};
        if (state.search) query.search = state.search;
        if (state.per_page) query.per_page = state.per_page;

        this.getUsers(query);
      }, 1000),
      deep: true,
    },
  },
  methods: {
    getUsers(filter = {}) {
      this.loadingTable = true;
      Inertia.get(this.route("user.index"), filter, {
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
  }
};
</script>

<style scoped>
  .user{
    border: 1px solid #eee;
    border-radius: 3px;
    padding: 8px 9px;
    background: #fafafa;
    color: #000;
    box-shadow: 2px 1px 6px 2px rgba(0,0,0,0.1);
  }
  
  .photo-container{
    --photo-width: 60px;
    overflow: hidden;
    border-radius: 50%;
    margin-right: 10px;
    width: var(--photo-width);
    height: var(--photo-width);
  }
  .photo-container img {
    width:100%;
    height: 100%;
  }
</style>
