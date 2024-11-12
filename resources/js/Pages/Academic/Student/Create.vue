<template>
  <Content>
    <Card varient="gray" title="Create New Student" :loading="loading">
      <template #title_right>
        <Link :href="route('student.index')">Back</Link>
      </template>

      <div>
        <form @submit.prevent="submit">
          <Input
            optional
            v-model="form.class_id"
            field="class_id"
            label="Class"
            :form="form"
            type="select"
            :options="classes"
            @change="getNewRoll"
          />
          <Input
            optional
            v-model="form.roll"
            field="roll"
            label="Roll"
            :form="form"
          />
          <Input
            optional
            v-model="form.name"
            field="name"
            label="Name"
            :form="form"
          />
          <Input
            optional
            v-model="form.gender"
            field="gender"
            label="Gender"
            :form="form"
            type="select"
            :options="genders"
          />
          <Input
            optional
            v-model="form.section"
            field="section"
            label="Section"
            :form="form"
            type="select"
            :options="sections"
          />
          <Input
            v-if="classes[form.class_id]?.has_group"
            optional
            v-model="form.group_id"
            field="group_id"
            label="Group"
            :form="form"
            type="select"
            :options="groups"
          />
          <Select
            v-if="classes[form.class_id]?.has_group"
            v-model="form.optional_subject_id"
            :field="'subjects.common.' + index + '.subject_id'"
            label-text="Third Subject"
            :form="form"
            optional
            from="subject.select"
            :dependOn="{ group_id: form.group_id }"
            :error="form.errors.optional_subject_id"
          />

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

          <div>
            <pre>{{ classes }}</pre>
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
    classes: Object,
    groups: Object,
  },
  data() {
    return {
      form: useForm({
        name: "",
        class_id: "",
        roll: "",
        gender: "",
        section: "",
        group_id: "",
        optional_subject_id: "",
      }),
      loading: false,
      genders: [
        {
          name: 'Male',
          id: 'Male',
        },
        {
          name: 'Female',
          id: 'Female',
        },
        {
          name: 'Others',
          id: 'Others',
        }
      ],
      sections: [
        {
          name: 'A',
          id: 'A',
        },
        {
          name: 'B',
          id: 'B',
        },
        {
          name: 'C',
          id: 'C',
        }
      ]
    };
  },
  methods: {
    submit() {
      this.form.clearErrors();
      try {
        this.form.post(route("student.store"), {
          preserveScroll: true,
          onSuccess: () => {
            this.form.reset();
          },
          onError: (error) => {
            console.log(error);
          },
        });
      } catch (error) {
        console.log(error);
      }
    },
    async getNewRoll(){
      if(!this.form.class_id) return
      this.loading = true;
      try {
        const response = await axios.get(route('student.roll.get', {class_id: this.form.class_id}));
        this.form.roll = response.data;
        console.log(response)
      } catch (error) {
        console.log('Error on getSubjects', error);
      } finally {
        this.loading = false;
      }
    }
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
