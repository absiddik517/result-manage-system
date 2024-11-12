<template>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card" style="overflow: hidden;">
              <div class="card-header">
                <h3 class="card-title">
                  <select v-model="itemPerPage">
                    <option v-for="index in 110" :value="index">{{ index }}</option>
                  </select> 
                  <Spinner :show="isLoading" />
                </h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input v-model.lazy="search" type="text" name="table_search" class="form-control float-right" placeholder="Search">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users.data">
                      <td>{{ user.id }}</td>
                      <td>{{ user.name }}</td>
                      <td>{{ user.email }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
                <pagination :items="users"></pagination>
            </div>
          </div>
        </div>
      </div>
    </section>
</template>

<script>
  import AdminLayout from '@/Layouts/AdminLayout'
  import Pagination from '@/Components/Pagination'
  import Spinner from '@/Kit/Spinner'
  import { Inertia } from '@inertiajs/inertia'
  export default {
    name: 'Table',
    layout: AdminLayout,
    components: {
        Pagination,
        Spinner
    },
    props: {
      users: Object,
      params: Object,
    },
    mounted(){
      
    },
    methods: {
      getUsers(){
        this.isLoading = true
        Inertia.get('/admin/table', { 
          search: this.search,
          itemPerPage: this.itemPerPage,
        }, {
          preserveState: true,
          replace: true, 
          onSuccess: () => {
            this.isLoading = false
          }
        })
      }
    },
    data() {
      return {
        search: this.params.search,
        itemPerPage: this.params.itemPerPage,
        delay: 3000,
        isLoading: false,
      }
    },
    watch:{
      search(current, old){
        this.getUsers()
      },
      itemPerPage(current, old){
        this.getUsers()
      }
    }
  }
</script>