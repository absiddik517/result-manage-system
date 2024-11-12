<template>
  <Content>
    <Card varient="gray" title="Create New Student">
      <template #title_right>
        <Link :href="route('student.index')">Back</Link>
      </template>

      <div>
        <form @submit.prevent="submit">
          <Select
            v-model="form.class_id"
            label-text="Class"
            from="classes.get.select"
          />
          <Input v-model="form.roll" field="roll" label="Roll" :form="form" />
          <Input v-model="form.name" field="name" label="Name" :form="form" />
          <Input v-model="form.gender" field="gender" label="Gender" :form="form" />
          <Input v-model="form.section" field="section" label="Section" :form="form" />
        
          <div class="form-group text-right">
            <Button>
              <i class="fa" :class="{'fa-spinner fa-spin': form.processing, 'fa-save': !form.processing}"></i>
              Save
            </Button>
          </div>
          
          <div>
            <pre>{{ form }}</pre>
          </div>
        </form>
      </div>
    </Card>
  </Content>
</template>

<script>
import {
  AdminLayout,
  Card,
  Input,
  Switch,
  Select,
  Button,
  Content,
} from "@/Components";
import { useForm } from "@inertiajs/inertia-vue3";
import { reactive, ref } from "vue";

export default {
  name: "Classes",
  layout: AdminLayout,
  components: {
    Input,
    Select,
    Content,
    Switch,
    Card,
    Button,
  },
  props: {
    student: Object,
    groups: Object,
  },
  data() {
    return {
      form: useForm({
        name: this.student.name ?? '',
        class_id: this.student.class_id ?? '',
        roll: this.student.roll ?? '',
        gender: this.student.gender ?? '',
        section: this.student.section ?? '',
        group_id: this.student.group_id ?? '',
        third_subject_id: this.student.third_subject_id ?? '',
      }),
      loading: false,
    };
  },
  methods: {
    submit() {
      this.form.clearErrors();
      try{
        this.form.post(route('classes.store'), {
          preserveScroll: true,
          onSuccess: () => {
            this.form.reset();
          },
          onError: error => {
            console.log(error)
          }
        });
      }catch(error){
        console.log(error)
      }
    },
  },
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
