<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray">
          <template #title>
            Create New User
          </template>
          <template #title_right>
            <Link :href="route('user.index')">Back to users</Link>
          </template>
          <div class="row">
            <form @submit.prevent="submit">
              <Input v-model="form.name" field="name" label="Name" :form="form" />
              <Input v-model="form.email" field="email" label="Email" :form="form" />
              <Input v-model="form.password" field="password" label="Password" :form="form" type="password" />
              <div class="form-group">
                <label for="role_id">Role</label>
                <select v-model="form.role_id" id="role_id" class="form-control">
                  <option value="">Select Role</option>
                  <option v-for="role in roles" :value="role.id">{{ role.name }}</option>
                </select>
              </div>
              <div class="form-group text-right">
                <button class="btn btn-dark" type="submit" :disabled="!form.isDirty">
                  <i v-show="form.processing" class="fa fa-spinner fa-spin" style="width: 18px;"></i>
                  <i v-show="!form.processing" class="fa fa-save" style="width: 18px;"></i>
                  Save
                </button>
              </div>
            </form>
          </div>
        </Card>
        <pre>
          {{ form }}
        </pre>
      </div>
    </div>
  </Content>
</template>

<script>
import {
  AdminLayout,
  Modal,
  Input,
  Switch,
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
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import { reactive } from 'vue'
export default {
  layout: AdminLayout,
  props: {
    roles: Object,
  },
  components: {
    Content,
    Card,
    Button,
    Switch,
    Input,
  },
  data(){
    return {
      form: useForm({
        name: '',
        email: '',
        password: '',
        role_id: '',
      }),
    }
  },
  methods: {
    submit() {
      this.form.clearErrors();
      this.form.post(route('user.store'), {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
        },
        onError: error => {
          console.log(error)
        }
      });
    },
  }
  
}
</script>