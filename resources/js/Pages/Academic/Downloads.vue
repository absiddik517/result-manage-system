<template>
  <Content>
    <div class="row">
      <div class="col-md-6">
        <Card varient="gray" title="Result entry sheet">
          <select class="form-control" v-model="form.entry.exam_id">
            <option value="">Exam</option>
            <option v-for="exam in exams" :value="exam.id"> {{ exam.name }} </option>
          </select>
          <div class="text-center mt-2">
            <a v-if="form.entry.exam_id" :href="route('pdf.result.form', {exam_id: form.entry.exam_id})" target="_blank">Download <Pdfd /></a>
          </div>
        </Card>
        <Card varient="dark" title="Exam Plan">
          <select class="form-control" v-model="form.plan.exam_id">
            <option value="">Exam</option>
            <option v-for="exam in exams" :value="exam.id"> {{ exam.name }} </option>
          </select>
          <div class="text-center mt-2">
            <a v-if="form.plan.exam_id" :href="route('pdf.result.form', {exam_id: form.plan.exam_id})" target="_blank">Download <Pdfd /></a>
          </div>
        </Card>
        <Card varient="gray" title="Admit Card">
          <select class="form-control" v-model="form.admit.exam_id">
            <option value="">Exam</option>
            <option v-for="exam in exams" :value="exam.id"> {{ exam.name }} </option>
          </select>
          <select class="form-control mt-2" v-model="form.admit.class_id">
            <option value="">Class</option>
            <option v-for="classs in classes" :value="classs.id"> {{ classs.name }} </option>
          </select>
          <div class="text-center mt-2">
            <a v-if="form.admit.exam_id && form.admit.class_id" :href="route('pdf.admit', {exam_id: form.admit.exam_id, class_id: form.admit.class_id})" target="_blank">Download <Pdfd /></a>
          </div>
        </Card>
        <Card varient="dark" title="Attendance Sheet">
          <select class="form-control" v-model="form.attendance.exam_id">
            <option value="">Exam</option>
            <option v-for="exam in exams" :value="exam.id"> {{ exam.name }} </option>
          </select>
          <select class="form-control mt-2" v-model="form.attendance.class_id">
            <option value="">Class</option>
            <option v-for="classs in classes" :value="classs.id"> {{ classs.name }} </option>
          </select>
          <select class="form-control mt-2" v-model="form.attendance.paper_size">
            <option value="">Paper Size</option>
            <option value="A4">A4</option>
            <option value="Legal">Legal</option>
          </select>
          <div class="text-center mt-2">
            <a v-if="form.attendance.exam_id && form.attendance.class_id" :href="route('pdf.attendance_sheet', {exam_id: form.attendance.exam_id, class_id: form.attendance.class_id, paper_size: form.attendance.paper_size,})" target="_blank">Download <Pdfd /></a>
          </div>
        </Card>
      </div>
    </div>
  </Content>
</template>


<script>
import {
  AdminLayout,
  Card,
  Input,
  Select,
  Button,
  Content,
} from "@/Components";
import { Pdfd } from "@/Icons"
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref } from "vue";

export default {
  name: "Downloads",
  layout: AdminLayout,
  components: {
    Input,
    Select,
    Content,
    Card,
    Pdfd,
    Button
  },
  props: {
    exams: Object,
    classes: Object,
  },
  data() {
    return {
      loading: false,
      form: {
        entry: reactive({
          exam_id: '',
        }),
        plan: reactive({
          exam_id: '',
        }),
        admit: reactive({
          exam_id: '',
          class_id: '',
        }),
        attendance: reactive({
          exam_id: '',
          class_id: '',
          paper_size: '',
        }),
      },
      filter: reactive({
        exam_id: '',
        class_id: '',
      }),
    }
  },
  
  methods: {
    async getExams() {
      alert('calling')
      this.loading = true;
      try {
        const response = await axios.get(route('result.get.select'));
        this.exams = response.data;
        console.log(response)
      } catch (error) {
        console.log('Error on getSubjects', error);
      } finally {
        this.loading = false;
      }
    },
    
  },
};
  
  
</script>