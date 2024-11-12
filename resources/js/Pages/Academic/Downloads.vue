<template>
  <Content>
    <div class="row">
      <div class="col-md-6">
        <Card varient="dark" :loading="loading" title="Downloads" body-class="p-0">
          <table class="table table-bodered">
            <tbody>
              <tr>
                <td>Result entry sheet</td>
                <td style="width: 40%;">
                  <select class="form-control" v-model="filter.exam_id">
                    <option value="">Exam</option>
                    <option v-for="exam in exams" :value="exam.id"> {{ exam.name }} </option>
                  </select>
                </td>
                <td class="text-right" style="width: 40px;">
                  <a :href="route('pdf.result.form', {exam_id: filter.exam_id})" target="_blank"><Pdfd /></a>
                </td>
              </tr>
              <tr>
                <td>Exam plan</td>
                <td style="width: 40%;">
                  <select class="form-control" v-model="filter.exam_id">
                    <option value="">Exam</option>
                    <option v-for="exam in exams" :value="exam.id"> {{ exam.name }} </option>
                  </select>
                </td>
                <td class="text-right" style="width: 40px;">
                  <a :href="route('pdf.exam.form', {exam_id: filter.exam_id})" target="_blank"><Pdfd /></a>
                </td>
              </tr>
            </tbody>
          </table>
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
  },
  data() {
    return {
      loading: false,
      filter: reactive({
        exam_id: '',
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