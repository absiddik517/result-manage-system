<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray" body-class="p-0" title="Sheet" :loading="loading">
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
          
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead class="thead-dark">
                <tr>
                  <th>#</th>
                  <th>Subject</th>
                  <th>Student Count</th>
                </tr>
              </thead>
              <tbody v-if="data.error || loading">
                <tr v-if="data.error && !loading">
                  <td colspan="3" class="text-center">{{ data.error }}</td>
                </tr>
                <tr v-if="loading">
                  <td colspan="3" class="text-center"><i class="fa fa-spinner fa-spin"></i></td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr v-for="(subject, index) in data.subjects">
                  <td>{{ index + 1 }}</td>
                  <td>{{ subject.name }}</td>
                  <td v-if="subject.status > 0">
                    Result found of {{ subject.status }} students
                  </td>
                  <td v-else>No result found</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="mt-3 text-center mb-3" v-if="!loading && !data.error && filter.exam_id && filter.class_id">
            <a :href="route('resultsheet', filter)" target="_blank"><Pdfd :size="50"/></a>
          </div>
        </Card>
          <div class="">
            
            <div class="p-2" ref="printableArea" v-if="false">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th rowspan="2" width="20">Roll</th>
                      <th rowspan="2">Name</th>

                      <th
                        v-for="(item, index) in data.thead"
                        :colspan="item.criteria.length + 1"
                        :key="'item' + index"
                      >
                        {{ item.subject_name }}
                      </th>

                      <th rowspan="2">Total</th>
                      <th rowspan="2">Grade</th>
                      <th rowspan="2">Point</th>
                    </tr>

                    <tr>
                      <template v-for="(item, key) in data.thead" :key="key">
                        <th
                          v-for="(title, index) in item.criteria"
                          :key="'cri' + index"
                        >
                          {{ title }}
                        </th>
                        <th>Total</th>
                      </template>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in data.result">
                      <td>{{ item.student_roll }}</td>
                      <td>{{ item.student_name }}</td>
                      <template
                        v-for="(subject, key) in item.subjects"
                        :key="key"
                      >
                        <td
                          v-for="(mark, cri) in subject.result"
                          :key="cri + '-' + index"
                          :class="{ 'text-danger': mark.status === 0 }"
                        >
                          {{ mark.mark_obtain }}
                        </td>
                        <td :class="{ 'text-danger': subject.point === 0 }">
                          <strong>{{ subject.total_mark_obtain }}</strong>
                        </td>
                      </template>
                      <td>{{ calculateTotalMarks(item) }}</td>
                      <td>{{ calculateGrade(item) }}</td>
                      <td>{{ calculatePoints(item).toFixed(2) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
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
  name: "Resultsheet",
  layout: AdminLayout,
  components: {
    Spinner,
    Input,
    Select,
    Pdfd,
    Content,
    Card,
    Button,
  },
  props: {
    classes: Object,
    exams: Object,
  },
  data() {
    return {
      filter: reactive({
        class_id: null,
        exam_id: null,
      }),
      data: reactive({
        subjects: undefined,
        error: 'Select exam and class first.',
      }),
      pages: reactive({
        width: 215.9,
        height: 279.4,
        margin: 0.5,
        unit: 'mm',
      }),
      loading: false,
    };
  },
  mounted() {
    console.log("m");
  },
  methods: {
    async get_result() {
      if (!this.filter.exam_id || !this.filter.class_id) return;
      try {
        this.loading = true;
        this.data.error = null;
        console.log(
          route("sheet.data", {
            exam_id: this.filter.exam_id,
            class_id: this.filter.class_id,
          })
        );
        const response = await axios.get(
          route("sheet.data", {
            exam_id: this.filter.exam_id,
            class_id: this.filter.class_id,
          })
        );
        this.data.subjects = response.data
      } catch (error) {
        this.data.error = error.response.data.message
      } finally {
        this.loading = false;
      }
    },
    printSheet() {
      console.log('printing sheet')
      const printContents = this.$refs.printableArea.innerHTML;
      const originalContents = document.body.innerHTML;
      document.body.innerHTML = printContents;
      window.print();
      document.body.innerHTML = originalContents;
      location.reload(); // Optional: reload to reset the page after printing
    },
    genPDF() {
      console.log('generating pdf')
      const doc = new jsPDF({
        orientation: "landscape",
        unit: "mm",
        format: "legal",
        filters: ["ASCIIHexEncode"],
      });
      doc.setFont("SolaimanLipi");
      console.log(doc.getFontList())
      //return;
      // Reference the HTML content
      const pdfContent = this.$refs.printableArea;
      const text = "রাজধানীর বনানী পেরিয়ে কড়াইল যাওয়া মানে যেন ‘দরজা খুলে ভিন্ন জগতে’ প্রবেশ করা, ঝকঝকে পাকা ভবন আর প্রশস্ত মসৃণ সড়ক থেকে একঝটকায় ভাঙা সরু রাস্তা আর ঘিঞ্জি টিনের সারিবদ্ধ ঘরের সামনে চলে যাওয়া। উচ্চ ও নিম্নবিত্তের মানুষের জীবনযাপনের বিভাজন রেখাটা সেখানে দাগ টেনে দেওয়ার মতোই স্পষ্ট। ‘দাগের’ ওপারে ঘিঞ্জি এলাকার বাসিন্দারা শোভন–সুরক্ষিত জীবনের সুযোগ পান না। তেমনই একজন ১৫ বছর বয়সী কিশোরী মিষ্টি (ছদ্মনাম)। গত ৮ মাস ধরে সে স্কুলে যায় না। বাল্যবিবাহের পর গর্ভধারণ, গর্ভপাত, নির্যাতনের মুখে সংসার ছেড়ে চলে আসা—এমন ঝড়-ঝাপটায় মিষ্টি স্কুল থেকে ঝরে পড়েছে।";
      const pageWidth = doc.internal.pageSize.width;
      const textWidth = doc.getTextWidth(text);
      const textX = (pageWidth - textWidth) / 2;

      doc.text(text, textX, 40);

      // Save the PDF
      doc.setLanguage("Bengali")
      doc.save("legal_landscape_sample.pdf");
    },
    calculateTotalMarks(student) {
      return Object.values(student.subjects).reduce((total, subject) => {
        return total + subject.total_mark_obtain;
      }, 0);
    },
    calculatePoints(student) {
      let failed_in = Object.values(student.subjects).filter(
        (subject) => subject.point === 0
      ).length;
      let total_point = Object.values(student.subjects).reduce(
        (total, subject) => {
          return total + subject.point;
        },
        0
      );
      let total_subjects = Object.keys(student.subjects).length;
      return failed_in === 0 ? total_point / total_subjects : 0.0;
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
  },
  computed: {
    get_exam_name() {
      const item = this.exams.find(
        (element) => element.value === this.filter.exam_id
      );
      return item ? item.label : null;
    },
    get_class_name() {
      const item = this.classes.find(
        (element) => element.value === this.filter.class_id
      );
      return item ? item.label : null;
    },
  },
};
</script>

<style scoped>
@media print {
  @page {
    /*size: 24in 8.5in;  Legal size */
    size: calc(var(--page-width) * 1var(--page-unit)) calc(var(--page-height) * 1var(--page-unit));
    margin: 1in; /* Optional: Adjust margins */
  }
  @page wide {
    size: a4 landscape;
  }
  
  
  body {
    transform: scale(0.9); /* Optional: Adjust content scaling */
    transform-origin: top left; /* Optional: Adjust origin for scaling */
  }

  /* Optional: Hide elements that should not be printed */
  .no-print {
    display: none;
  }
}
.hiwi{
  width: calc(var(--page-width) * 1var(--page-unit));
  background: red;
  color: green;
}
.bbwp {
  padding-bottom: 5px;
  border-bottom: 1.5px solid #ddd;
}

table thead tr th {
  text-align: center; /* Horizontal centering */
  vertical-align: middle; /* Vertical centering */
}

.mytable{
  width: calc(var(--page-width) * 1var(--page-unit));
  border: 1px solid #000;
}
</style>
