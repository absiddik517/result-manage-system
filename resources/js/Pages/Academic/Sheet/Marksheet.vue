<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray" body-class="p-0 no-print" title="Sheet" :loading="loading">
          <template #title_right>
            <a v-if="data.loaded && !loading" :href="route('marksheets', filter)" target="_blank">
              <i class="fa fa-print"></i> All Marksheet
            </a>
          </template>
          <div class="row p-2 gy-2">
            <Select
              v-model="filter.exam_id"
              label-text="Exam"
              :options="exams"
              withoutLabel
              placeholder="Exam"
              groupClass="col-6"
              @change="get_result"
            />
            <Select
              v-model="filter.class_id"
              label-text="Class"
              :options="classes"
              withoutLabel
              placeholder="Class"
              groupClass="col-6"
              @change="get_result"
            />
          </div>
          
          <div>
            <table class="table table-bordered">
              <thead class="thead-dark">
                <tr>
                  <th style="width: 50px; text-align:center;">Roll</th>
                  <th style="text-align: center;">Name</th>
                  <th style="text-align: center;">File</th>
                </tr>
              </thead>
              <tbody v-if="!data.loaded || loading">
                <tr v-if="!loading">
                  <td colspan="3" style="text-align:center;">{{ data.error }}</td>
                </tr>
                <tr v-else>
                  <td colspan="3" style="text-align:center;">
                  <i class="fa fa-spinner fa-spin"></i> </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr v-for="student in data.students">
                  <td>{{ student.roll }}</td>
                  <td>{{ student.name }}</td>
                  <td style="text-align: right;">
                    <a :href="route('marksheet', {class_id: filter.class_id,
                    exam_id: filter.exam_id, student_id: student.student_id})"
                    target="_blank"><Pdfd :size="30"/></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div>
            <pre>{{ data.students }}</pre>
          </div>
        </Card>
      </div>
    </div>
  </Content>
</template>

<script>
import {
  AdminLayout,
  Spinner,
  Input,
  Select,
  Content,
  Card,
  Button,
} from "@/Components";
import { Pdfd } from "@/Icons";
import toast from "@/Store/toast.js";
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref } from "vue";
import jsPDF from "jspdf";

export default {
  name: "ClassBy",
  layout: AdminLayout,
  components: {
    Spinner,
    Input,
    Pdfd,
    Select,
    Content,
    Card,
    Button,
  },
  props: {
    institute: Object,
    classes: Object,
    exams: Object,
    students: Object
  },
  data() {
    return {
      filter: reactive({
        class_id: null,
        exam_id: null,
      }),
      data: reactive({
        students: undefined,
        loaded: false,
        error: 'Select Exam and Class First.',
      }),
      pages: reactive({
        width: 215.9,
        height: 279.4,
        margin: 0.5,
        unit: "mm",
      }),
      loading: false,
    };
  },
  mounted() {
    
  },
  methods: {
    async get_result() {
      console.log(this.institute);
      if (!this.filter.exam_id || !this.filter.class_id) return;
      try {
        this.loading = true;
        this.loaded = false;
        console.log(
          route("result.list", {
            exam_id: this.filter.exam_id,
            class_id: this.filter.class_id,
          })
        );
        const response = await axios.get(
          route("result.list", {
            exam_id: this.filter.exam_id,
            class_id: this.filter.class_id,
          })
        );
        this.data.students = response.data.students;
        this.data.loaded = true
      } catch ( error ) {
        console.log(error)
        this.data.error = error.response.data.message
        this.data.loaded = false
      } finally {
        this.loading = false;
        console.log(this.data)
      }
    },
    calculateGrade(student) {
      const point = this.calculatePoints(student);
      if (point >= 5) return "A+";
      else if (point >= 4) return "A";
      else if (point >= 3.5) return "A-";
      else if (point >= 3) return "B";
      else if (point >= 2) return "C";
      else if (point >= 1) return "D";
      else return "F"; // for points below 1
    },
    calculateGradeOf(mark) {
      if (mark >= 5) return "A+";
      else if (mark >= 4) return "A";
      else if (mark >= 3.5) return "A-";
      else if (mark >= 3) return "B";
      else if (mark >= 2) return "C";
      else if (mark >= 1) return "D";
      else return "F"; // for points below 1
    },
  },
};
</script>

<style scoped>
@media print {
  @page {
    size: A4;
    margin: 10mm; /* Adjust margins as needed */
  }

  /* Optional styling for the print view */
  body {
    width: 210mm;
    height: 297mm;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }

  /* Apply custom styles for print */
  .content {
    padding: 10mm;
    font-size: 12pt; /* Adjust font size for better readability on A4 */
  }

  .bbwp {
    display: block !important;
  }

  /* Optional: Hide elements that should not be printed */
  .no-print {
    display: none;
  }
}
.bbwp {
  padding-bottom: 5px;
  border-bottom: 1.5px solid #ddd;
  display: none;
}

.student .col-12,
.student .col-6,
.student .col-5 {
  border-bottom: 1px dotted #000;
  font-size: 22px;
}

.student div{
  padding:10px 8px;
}
.fail{
  background: #ddd;
  color: red;
}
</style>
