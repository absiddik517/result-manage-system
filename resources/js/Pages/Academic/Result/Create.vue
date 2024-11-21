<template>
  <Content>
    <Card varient="gray" :loading="loading" title="Result Form">
      <template #title_right>
        <Link
          class="mr-2"
          :href="route('result.index')"
        >
          Go Back
        </Link>
      </template>
      
      <div>
        <form @submit.prevent="submit">
          <Select v-if="!exam_id" v-model="form.exam_id" label-text="Exam" :options="exams" @change="getForm"/>
          <Select v-if="!class_id" v-model="form.class_id" label-text="Class" :options="classes" @change="getForm"/>
          <Select 
            v-model="form.subject_id" 
            label-text="Subject" 
            from="result.get.subject" 
            :depend-on="{class_id: form.class_id}"
            @change="getForm"/>
          <div class="row" v-if="form.results.length">
            <div class="col-md-4" v-for="(item, index) in form.results" :key="'index'+index">
              <div class="contain">
                <div class="form-check form-switch mb-2">
                  <input tabindex="-1" v-model="item.appeared" class="form-check-input" type="checkbox" :id="`flexSwitchCheckDefault_${index}_id`">
                  <label class="form-check-label" :for="`flexSwitchCheckDefault_${index}_id`" :class="{'failed' : form.results[index].grade
                    === 'F', 'passed': form.results[index].grade !== 'F'}">
                    {{ item.roll }}. {{ item.student_name }}
                    <i v-if="item.id" class="fa fa-pencil"></i>
                  </label>
                </div>
                <div class="row">
                  <div class="col-md-6 col-xs-6 col-sm-6" v-for="(row, ind) in item.result" :key="'result'+ind">
                    <input 
                      :disabled="!item.appeared"
                      class="form-control mb-2"
                      :class="{'has-errors': form?.errors[`results.${index}.result.${ind}.mark_obtain`]}"
                      v-model="form.results[index].result[ind].mark_obtain"
                      :placeholder="row.pass_mark+' < '+row.short_title + ' < ' + row.full_mark" 
                      type="number"
                      :max="row.full_mark"
                      @input="handel_mark_change(index, ind)"
                    />
                    <span v-if="form?.errors[`results.${index}.result.${ind}.mark_obtain`]" class="error-message">{{ form?.errors[`results.${index}.result.${ind}.mark_obtain`] }}</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    Total: {{ form.results[index].total_mark_obtain }}
                  </div>
                  <div class="col-6 text-right">
                    Grade: <span :class="{'failed' : form.results[index].grade === 'F', 'passed': form.results[index].grade !== 'F'}">{{ form.results[index].grade }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group text-right">
            <Button>
              <i class="fa" :class="{'fa-spinner fa-spin': form.processing, 'fa-save': !form.processing}"></i>
              Save
            </Button>
          </div>
        </form>
      </div>
    </Card>
  </Content>
</template>

<script>
import {
  AdminLayout,
  Modal,
  Card,
  Input,
  Select,
  Spinner,
  Dropdown,
  Button,
  Content,
} from "@/Components";
import toast from "@/Store/toast.js";
import _ from "lodash";
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref } from "vue";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
  name: "Index",
  layout: AdminLayout,
  components: {
    Spinner,
    Modal,
    Input,
    Select,
    Content,
    Card,
    Button,
    Dropdown,
  },
  props: {
    exams: Object,
    classes: Object,
    exam_id: Number,
    class_id: Number,
  },
  data(){
    return {
      form: useForm({
        exam_id: this.exam_id ?? null,
        class_id: this.class_id ?? null,
        subject_id: null,
        results: [],
      }),
      loaded: false,
      loading: false,
    }
  },
  methods: {
    submit() {
      this.form.clearErrors();
      this.form.post(route("result.store"), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: ({ props }) => {
          if (props.toast?.type === 'success') {
            this.form.reset();
          }
        },
        onError: console.error
      });
    },
    async getForm(){
      if(!this.form.class_id || !this.form.exam_id || !this.form.subject_id) return 0;
      this.loading = true;
      this.loaded = false;
      this.form.results = []
      try {
        let url = route('result.get.student', {
          exam_id: this.form.exam_id,
          class_id: this.form.class_id,
          subject_id: this.form.subject_id
        });
        console.log(url)
        const response = await axios.get(url);
        this.form.results = response.data;
        this.loaded = true;
        console.log(response)
      } catch (error) {
        console.log(error)
        toast.add({
          type: 'error',
          message: error.response.data.message
        })
      } finally {
        this.loading = false;
      }
    },
    handel_mark_change(index, ind){
      let data = this.form.results[index];
      let item = data.result[ind];
      // input validation 
      if(item.mark_obtain > item.full_mark){
        toast.add({
          message: `Marks(${item.mark_obtain}) can not be more the full mark (${
          item.full_mark })`,
          type: 'error'
        })
        item.mark_obtain = item.full_mark*1
      }
      // item calculation 
      item.status = (item.mark_obtain >= item.pass_mark) ? 1 : 0;
      data.total_mark_obtain = data.result.reduce((sum, item) => sum + item.mark_obtain, 0);
      data.status =  Number(data.result.every(item => item.status === 1));
      data.grade = this.grade(data.total_mark_obtain, data.full_mark, data.status, true)
      data.point = this.grade(data.total_mark_obtain, data.full_mark, data.status, false)
      console.log(data)
    },
    grade(score, totalScore, status, isGrade) {
      if(!status){
        return (isGrade) ? 'F' : 0;
      }
      const percentage = (score / totalScore) * 100;
      if (percentage >= 80) {
          return (isGrade) ? 'A+' : 5;
      } else if (percentage >= 70) {
          return (isGrade) ? 'A' : 4;
      } else if (percentage >= 60) {
          return (isGrade) ? 'A-' : 3.5;
      } else if (percentage >= 50) {
          return (isGrade) ? 'B' : 3;
      } else if (percentage >= 40) {
          return (isGrade) ? 'C' : 2;
      } else if (percentage >= 33) {
          return (isGrade) ? 'D' : 1;
      }else {
          return (isGrade) ? 'F' : 0;
      }
    }
  }
  
}
</script>

<style scoped>
  .contain{
    border: 1px solid #ddd;
    padding: 8px;
    margin-bottom: 12px;
  }
  
  .passed {
    color: green;
    font-weight: bold;
  }
  .failed {
    color: red;
    font-weight: bold;
  }
  
  .has-errors{
    margin-bottom: 0!important;
    border-color: red;
  }
  
  .error-message{
    font-weight: bold;
    color:red;
  }
  
  .form-check {
    margin-left: 20px!important;
  }
  
</style>