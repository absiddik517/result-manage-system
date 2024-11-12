<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray">
          <template #title>
            {{ role_name }}
          </template>
          <template #title_right>
            <Button
              class="mr-2"
              type="button"
              varient="light"
              @click="submit"
              :loading="form.processing"
            >
              Save
            </Button>
          </template>
          <div class="row">
            <div v-for="(no, key) in permissions"  class="col-sm-12 col-md-6">
              <Card varient="light">
                <template #title>
                  <label :for="key">{{ key }}</label>
                </template>
                <template #title_right>
                  <input type="checkbox" :id="key" @change="trackChange" :data-key="key" :checked="selectedAll[key]">
                </template>
                <form @submit="submit">
                  <div v-for="(permission, index) in permissions[key]">
                    <Switch v-model="form.permission_id" :label="permission.name" :value="permission.id"
                    @change="updateSelected"
                    />
                  </div>
                  <div>
                  </div>
                </form>
              </Card>
            </div>
          </div>
        </Card>
        <pre>
          
        {{ role_permissions }}
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
import {useForm} from "@inertiajs/inertia-vue3";
import { reactive } from 'vue'
export default {
  layout: AdminLayout,
  props: {
    permissions: Object,
    role_id: [String, Number],
    role_name: String,
    role_permissions: Array
  },
  components: {
    Content,
    Card,
    Button,
    Switch
  },
  data(){
    return {
      form: useForm({
        permission_id: this.role_permissions
      }),
      selectedAll: reactive({})
    }
  },
  mounted(){
    this.defaultSelection();
  },
  methods: {
    submit() {
      this.form.clearErrors();
      this.form.post(route('admin.gate.permission.update', this.role_id), {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
          this.modal.form.hide();
        },
        onError: error => {
          console.log(error)
        }
      });
    },
    trackChange(event){
      let key = event.target.getAttribute('data-key')
      let isChecked = event.target.checked
      
      const permissions = this.permissions[key];
      let permission_id = []
      for(let permission in permissions){
        if(isChecked){
          if(this.form.permission_id.indexOf(permissions[permission].id) >= 0) continue;
          this.form.permission_id.push(permissions[permission].id)
        }else{
          this.form.permission_id.splice(this.form.permission_id.indexOf(permissions[permission].id));
        }
      }
      this.updateSelected()
    },
    updateSelected(){
      for(let permission_group in this.permissions){
        let isSelected = true
        for(let permission in this.permissions[permission_group]){
          isSelected = isSelected && (this.form.permission_id.indexOf(this.permissions[permission_group][permission].id) != -1)
        }
        this.selectedAll[permission_group] = isSelected
      }
    },
    defaultSelection(){
      for(let permission in this.permissions){
        this.selectedAll[permission] = true
      }
      this.updateSelected()
    },
  }
  
}
</script>