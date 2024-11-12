<template>
  <Content>
    <Card varient="gray" title="Create New Subject">
      <template #title_right>
        <Link :href="route('classes.index')">Back</Link>
      </template>

      <div>
        <form @submit.prevent="submit">
          <Input v-model="form.name" field="name" label="Name" :form="form" />
          <Input
            v-model="form.short_name"
            field="short_name"
            label="Short Name"
            :form="form"
          />
          <Switch
            label="Has Group"
            v-model="form.has_group"
            @change="handleGroupChange"
          />
          <span>Common Subjects</span>
          <div class="row p-0">
            <div
              class="items_container col-sm-12 col-md-4"
              v-for="(item, index) in form.subjects.common"
              :key="index"
            >
              <div
                class="item_header d-flex justify-content-between align-items-center"
              >
                <span>Subject {{ index + 1 }}</span>
                <div>
                  <Button
                    v-show="form.subjects.common.length == index + 1"
                    varient="success"
                    @click="addSubject"
                    type="button"
                    >Add</Button
                  >
                  <Button
                    v-show="form.subjects.common.length > 1"
                    class="ml-2"
                    varient="danger"
                    type="button"
                    @click="removeSubject(index)"
                    >Remove</Button
                  >
                </div>
              </div>
              <div class="item row">
                <Select
                  v-model="item.subject_id"
                  :field="'subjects.common.' + index + '.subject_id'"
                  label-text="Subject"
                  :form="form"
                  optional
                  from="subject.select"
                  :send="{ group_id: false }"
                  :error="form.errors[`subjects.common.${index}.subject_id`]"
                />
              </div>
            </div>
          </div>
          <div v-if="form.has_group && form.subjects.group.length">
            <span>Group subject</span>
            <div class="row p-0">
              <div
                class="items_container col-sm-12 col-md-4"
                v-for="(item, index) in form.subjects.group"
                :key="index"
              >
                <div
                  class="item_header d-flex justify-content-between align-items-center"
                >
                  <span>Subject {{ index + 1 }}</span>
                  <div>
                    <Button
                      v-show="form.subjects.group.length == index + 1"
                      varient="success"
                      @click="addGroupSubject"
                      type="button"
                      >Add</Button
                    >
                    <Button
                      v-show="form.subjects.group.length > 1"
                      class="ml-2"
                      varient="danger"
                      type="button"
                      @click="removeGroupSubject(index)"
                      >Remove</Button
                    >
                  </div>
                </div>
                <div class="item row">
                  <Select
                    v-model="item.group_id"
                    :field="'subjects.group.' + index + '.group_id'"
                    label-text="Group"
                    :form="form"
                    label="name"
                    trackBy="id"
                    :options="groups"
                    optional
                    :error="form.errors[`subjects.group.${index}.group_id`]"
                  />

                  <Select
                    v-model="item.subject_id"
                    :field="'subjects.' + index + '.subject_id'"
                    label-text="Subject"
                    :form="form"
                    from="subject.select"
                    :dependOn="{ group_id: item.group_id }"
                    optional
                    :error="form.errors[`subjects.group.${index}.subject_id`]"
                  />
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
    subjects: Object,
    groups: Object,
  },
  data() {
    return {
      form: useForm({
        name: "",
        short_name: "",
        has_group: false,
        subjects: {
          common: [
            {
              subject_id: "",
              group_id: null,
            },
          ],
          group: [],
        },
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
    addSubject() {
      this.form.subjects.common.push({
        subject_id: "",
        group_id: null,
      });
    },
    addGroupSubject() {
      this.form.subjects.group.push({
        subject_id: "",
        group_id: "",
      });
    },
    handleGroupChange() {
      if (this.form.has_group) {
        this.form.subjects.group.push({
          group_id: "",
          subject_id: "",
        });
      } else {
        this.form.subjects.group = [];
      }
    },
    removeSubject(index) {
      if (this.form.subjects.common.length == 1) return;
      if (this.isEditing && confirm("Are you sure to delete?")) {
        this.form.subjects.common.splice(index, 1);
      } else {
        this.form.subjects.common.splice(index, 1);
      }
    },
    removeGroupSubject(index) {
      if (this.form.subjects.group.length == 1) return;
      if (this.isEditing && confirm("Are you sure to delete?")) {
        this.form.subjects.group.splice(index, 1);
      } else {
        this.form.subjects.group.splice(index, 1);
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
