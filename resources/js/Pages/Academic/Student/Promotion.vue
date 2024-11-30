<template>
  <Content>
    <Card varient="gray" title="Student Promotion" :loading="loading">
      <div class="row">
        <div class="col-5">
          <Select 
            withoutLabel 
            placeholder="To"
            :options="classes"
            trackBy="id"
            label="name"
            v-model="form.to_class_id"
            @change="handleChange"
          />
        </div>
        <div class="col-2" style="text-align: center;">
          FROM
        </div>
        <div class="col-5">
          <Select 
            withoutLabel 
            placeholder="From"
            :options="classes"
            trackBy="id"
            label="name"
            v-model="form.from_class_id"
            @change="getStudents"
            :disableIf="!form.to_class_id"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <table class="table table-bordered">
            <tbody>
              <tr :class="{ 'not_promoted' : !student.promoted }" v-for="(student, index) in form.students" :key="index">
                <td>
                  <input type="checkbox" v-model="student.promoted" tabindex="-1" @change="handlePromotionChange(index)">
                </td>
                <td>
                  <div>
                    <strong>{{ student.name }}</strong>
                  </div>
                  <div>
                    Roll: <strong>{{ student.old_roll }}</strong>
                  </div>
                </td>
                <td style="width: 120px;">
                  <input @change="validateRoll(index)" v-model="student.roll" :disabled="!student.promoted" type="text" placeholder="New Roll" class="form-control"/>
                </td>
                <td style="width: 120px;" v-if="has_group">
                  <input type="text" placeholder="New Roll" class="form-control"/>
                </td>
                <td style="width: 120px;" v-if="has_group">
                  <input type="text" placeholder="New Roll" class="form-control"/>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div>
          <pre>{{ has_group }}</pre>
          <pre>{{ form.students }}</pre>
        </div>
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
import toast from '@/Store/toast';

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
    classes: Object,
  },
  data() {
    return {
      form: useForm({
        from_class_id: '',
        to_class_id: '',
        students: []
      }),
      loading: false,
      students: undefined,
      has_group: false,
    };
  },
  methods: {
    async getStudents(){
      if(!this.form.from_class_id) return;
      this.loading = true;
      try{
        await axios
          .get(route('student.get', this.form.from_class_id))
          .then((response) => {
            this.students = response.data;
            this.prepareForm(response.data)
          })
          this.loading = false;
      }catch(error){
        toast.add({
          type: 'error',
          message: error.response.data.message
        })
        this.loading = false;
        console.log(error);
      }
    },
    prepareForm(data){
      let students = [];
      for(let i = 0; i < data.length; i++){
        students.push({
          id: data[i].id,
          promoted: true,
          class_id: this.form.from_class_id,
          to_class_id: this.form.to_class_id,
          old_roll: data[i].roll,
          roll: '',
          name: data[i].name,
          group_id: data[i].group_id,
          optional_subject_id: data[i].optional_subject_id,
        })
      }
      this.form.students = students
    },
    handleChange(){
      let index = this.classes.findIndex(classs => classs.id === this.form.to_class_id);
      let from_class = this.classes[index - 1];
      this.form.from_class_id = from_class.id;
      this.has_group = this.classes[index].has_group
    },
    handlePromotionChange(index){
      this.form.students[index].roll = ''
    },
    validateRoll(index){
      console.log('ok')
    }
    /*
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
    */
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
  
  table tbody tr td{
    vertical-align: middle;
  }
</style>
