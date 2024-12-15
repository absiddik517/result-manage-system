<template>
  <Content>
    <Card varient="gray" title="Student Promotion" :loading="loading">
      <form @submit.prevent="submit">
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
        <div class="col-2" style="text-align: center">FROM</div>
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
              <tr
                :class="{ not_promoted: !student.promoted }"
                v-for="(student, index) in form.students"
                :key="index"
              >
                <td>
                  <input
                    type="checkbox"
                    v-model="student.promoted"
                    tabindex="-1"
                    @change="handlePromotionChange(index)"
                  />
                </td>
                <td>
                  <div>
                    <strong>{{ student.name }}</strong>
                  </div>
                  <div>
                    Roll: <strong>{{ student.old_roll }}</strong>
                  </div>
                </td>
                <td>
                  <Input
                    withoutLabel
                    optional
                    @change="validateRoll(index)"
                    v-model="student.roll"
                    label="New Roll"
                    :form="form"
                    :disableIf="!student.promoted"
                    :field="'students.'+index+'.roll'"
                    type="number"
                  />
                  
                  <template v-if="has_group" :key="index">
                    <Select
                      :disableIf="!student.promoted"
                      withoutLabel
                      optional
                      :searchable="false"
                      v-model="student.group_id"
                      field="group_id"
                      label-text="Group"
                      label="name"
                      trackBy="id"
                      :form="form"
                      :options="groups"
                      :error="form.errors['students.'+index+'.group_id']"
                    />
                    
                    <Select
                      withoutLabel
                      :disableIf="!student.promoted"
                      v-model="student.optional_subject_id"
                      label-text="Third Subject"
                      :form="form"
                      optional
                      from="subject.select"
                      :dependOn="{ group_id: student.group_id }"
                      :error="form.errors['students.'+index+'.optional_subject_id']"
                    />
                  </template>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="form-group text-right">
            <Button>
              <i
                class="fa"
                :class="{
                  'fa-spinner fa-spin': form.processing,
                  'fa-save': !form.processing,
                }"
              ></i>
              Save
            </Button>
          </div>
        </div>
        
      </div>
      <div class="row">
        <div class="col-12">
          <pre>{{ students }}</pre>
          <pre>{{ form }}</pre>
        </div>
      </div>
      </form>
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
    Content
  } from "@/Components";
  import { useForm } from "@inertiajs/inertia-vue3";
  import { reactive, ref } from "vue";
  import toast from "@/Store/toast";

  export default {
    name: "Classes",
    layout: AdminLayout,
    components: {
      Input,
      Select,
      Content,
      Switch,
      Card,
      Button
    },
    props: {
      classes: Object,
      groups: Object
    },
    data() {
      return {
        form: useForm({
          from_class_id: "",
          to_class_id: "",
          has_group: false,
          students: []
        }),
        loading: false,
        students: undefined,
        has_group: false,
        temp: undefined
      };
    },
    methods: {
      async getStudents() {
        if (!this.form.from_class_id) return;
        this.loading = true;
        try {
          await axios
            .get(route("student.get", this.form.from_class_id))
            .then(response => {
              this.students = response.data;
              this.prepareForm(response.data);
            });
          this.loading = false;
        } catch (error) {
          toast.add({
            type: "error",
            message: error.response.data.message
          });
          this.loading = false;
          console.log(error);
        }
      },
      prepareForm(data) {
        let students = [];
        for (let i = 0; i < data.length; i++) {
          students.push({
            ...data[i],
            promoted: true,
            class_id: this.form.from_class_id,
            to_class_id: this.form.to_class_id,
            roll: "",
          });
        }
        this.form.students = students;
        this.form.students = students;
      },
      handleChange() {
        let index = this.classes.findIndex(
          classs => classs.id === this.form.to_class_id
        );
        let from_class = this.classes[index - 1];
        this.form.from_class_id = from_class.id;
        this.has_group = this.classes[index].has_group;
        this.form.has_group = this.classes[index].has_group;
      },
      handlePromotionChange(index) {
        this.form.students[index].roll = "";
      },
      validateRoll(indexToSkip) {
        const rollArray = this.form.students
          .map(student => student.roll) // Extract rolls
          .filter(
            (roll, index) => index !== indexToSkip && roll && !isNaN(roll)
          );
        const current = this.form.students[indexToSkip].roll;
        if (rollArray.includes(current)) {
          toast.add({
            type: "error",
            message: "Roll already exist."
          });
          this.form.students[indexToSkip].roll = "";
        }
      },

      submit() {
        this.form.clearErrors();
        try {
          this.form.post(route("student.promote"), {
            preserveScroll: true,
            onSuccess: () => {
              this.form.reset();
            },
            onError: error => {
              console.log(error);
            }
          });
        } catch (error) {
          console.log(error);
        }
      }
    }
  };
</script>

<style scoped>
  .not_promoted{
    background: #f8d7da;
  }
</style>
