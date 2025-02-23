<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="dark" :title="`Planning ${isCreating ? ' (Create)' : ' (Update)'}`" :loading="loading">
          <div>
            <form @submit.prevent="submit">
              <div class="row">
                <div :class="{'col-md-6': has_previous, 'col-12': !has_previous,}">
                  <Select v-model="form.exam_id" label-text="Exam" :options="exams" @change="getForm"/>
                  <Select v-model="form.class_id" label-text="Order reference"
                  :options="classes" 
                  @change="getForm"/>
                </div>
                <div class="col-md-6">
                  <Card v-if="has_previous" title="Previous exams">
                    <template #title_right>
                      <Button type="button" @click="newPlan()" varient="info">New Plan</Button>
                    </template>
                    <div @click="copyPreviousMapping(data)" v-for="(data, exam) in form_data.previous" :key="exam" style="width:100%; padding: 10px 18px; border-radius: 5px; background: rgba(154,162,255,0.749); margin-bottom: 8px;">
                      {{ exam }}
                    </div>
                  </Card>
                </div>
              </div>
              
              
              <div class="row">
              <div
                class="items_container col-sm-12 col-md-6"
                v-for="(item, index) in form.mappings"
              >
                <div class="item_header d-flex justify-content-between align-items-center" >
                  <span>{{ item.name }}</span>
                  <div>
                    <Button
                      v-show="index > 0"
                      size="sm"
                      class="ml-2"
                      varient="primary"
                      type="button"
                      @click="copyPrevious(index)"
                      ><i class="fa fa-copy"></i></Button>
                    <Button
                      v-show="this.form.mappings.length > 1"
                      size="sm"
                      class="ml-2"
                      varient="success"
                      type="button"
                      @click="addCriteria(index)"
                      ><i class="fa fa-plus"></i></Button>
                    <Button
                      v-show="this.form.mappings.length > 1"
                      size="sm"
                      class="ml-2"
                      varient="danger"
                      type="button"
                      @click="removeMapping(index)"
                      ><i class="fa fa-trash"></i></Button>
                  </div>
                </div>
                <div class="item row">
                  <Input
                    v-model="item.full_mark"
                    :field="'mappings.' + index + '.full_mark'"
                    label="Full Mark"
                    :form="form"
                    group-class="col-12"
                    type="number"
                  />
                  <div v-for="(cri, ind) in form.mappings[index].criteria"
                  :key="'oofrow'+ind">
                    <div class="row criborder" :key="'keyof'+ind">
                      <div :key="'keyofbtn'+ind+'parant'+index" @click="removeCriteria(index, ind)" v-show="form.mappings[index].criteria.length > 1" class="farmbtn">
                        <i class="fa fa-times"></i>
                      </div>
                      <Input
                        v-model="cri.title"
                        :field="'mappings.'+ index +'.criteria.' + ind + '.title'"
                        label="Title"
                        :form="form"
                        group-class="col-6"
                      />
                      <Input
                        v-model="cri.short_title"
                        :field="'mappings.'+ index +'.criteria.' + ind + '.short_title'"
                        label="Short Title"
                        :form="form"
                        group-class="col-6"
                      />
                      <Input
                        v-model="cri.full_mark"
                        :field="'mappings.'+ index +'.criteria.' + ind + '.full_mark'"
                        label="Full Mark"
                        :form="form"
                        group-class="col-6"
                        type="number"
                        @change="fillPassMark(index, ind)"
                      />
                      <Input
                        v-model="cri.pass_mark"
                        :field="'mappings.'+ index +'.criteria.' + ind + '.pass_mark'"
                        label="Pass Mark"
                        :form="form"
                        group-class="col-6"
                        type="number"
                      />
                      
                    </div>
                  </div>
                </div>
              </div>
              </div>
              
              <div class="form-group text-right">
                <Button :disabled="!form.isDirty">
                  <i class="fa" :class="{'fa-spinner fa-spin': form.processing, 'fa-save': !form.processing}"></i>
                  Save
                </Button>
              </div>
            </form>
          </div>
        </Card>
      </div>
    </div>
  </Content>
</template>

<script>
import { Content, AdminLayout, Card, Button, Input, Select, Switch } from '@/Components';
import { Inertia } from "@inertiajs/inertia";
import {useForm} from "@inertiajs/inertia-vue3";
import toast from "@/Store/toast.js";
export default {
  name: 'Index',
  layout: AdminLayout,
  components: {Content, Card, Button, Input, Select, Switch},
  props: {
    exams: [Object, Array],
    classes: [Object, Array],
  },
  data(){
    return {
      form: useForm({
        exam_id: '',
        class_id: '',
        mappings: [],
      }),
      loading: false,
      pass_at: 33,
      form_data: undefined,
      has_previous: false,
      isCreating: undefined,
    }
  },
  methods: {
    fillPassMark(index, ind){
      const full_mark = this.form.mappings[index].criteria[ind].full_mark
      const pass_mark = Math.ceil((full_mark*this.pass_at)/100)
      this.form.mappings[index].criteria[ind].pass_mark = pass_mark
    },
    submit(){
      this.form.clearErrors();
      var url = route("exam.map.store");
      this.form.post(url, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
          console.log(res)
          if(res.props.toast.type == 'success') {
            this.form.reset();
          }
        },onError:(error) => {
          console.log(error)
        }
      });
    },
    async getForm() {
      if (!this.form.class_id || !this.form.exam_id) return;
      this.loading = true;
      this.form.mappings = [];
      let url = route('exam.map.form', {
          exam_id: this.form.exam_id,
          class_id: this.form.class_id
        });
      console.log(url)
      try {
        const response = await axios.get(url);
        if(response.data.previous.length === 0){
          this.form.mappings = this.convertToArray(response.data.mappings);
          this.has_previous = false
        }else{
          this.has_previous = true
          this.form_data = response.data;
        }
        this.isCreating = response.data.isCreating;
        console.log(this.form.mappings)
      } catch (error) {
        toast.add({
          type: 'error',
          message: error.response.data.message
        })
        console.log('Error on getSubjects', error.response.data.message);
      } finally {
        this.loading = false;
      }
    },
    newPlan(){
      this.form.mappings = this.convertToArray(this.form_data.mappings);
    },
    copyPreviousMapping(data){
      this.form.mappings = this.convertToArray(data);
    },
    addCriteria(index){
      this.form.mappings[index].criteria.push({
        title: '',
        short_title: '',
        full_mark: '',
        pass_mark:''
      });
    },
    copyPrevious(index){
      let previous = this.form.mappings[index-1];
      let current = this.form.mappings[index];
      current.full_mark = previous.full_mark
      current.criteria = previous.criteria
      console.log('previous', previous)
      console.log('current', current)
    },
    removeCriteria(index, ind){
      
      if(this.form.mappings[index].criteria.length > 1 && confirm('Are you sure to delete?')){
        this.form.mappings[index].criteria.splice(ind, 1);
      }
    },
    removeMapping(index){
      if(this.form.mappings.length == 1) return;
      if(confirm('Are you sure to delete?')){
        this.form.mappings.splice(index, 1);
      }else{
        this.form.mappings.splice(index, 1);
      }
    },
    convertToArray(obj) {
      return Object.values(obj);
    }
  }
}
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
  .criborder{
    border: 1px solid #ddd;
    padding-top:5px;
    margin-bottom: 6px;
    position: relative;
  }
  .farmbtn{
    position: absolute;
    top:-12px;
    right: -12px;
    z-index: 2;
    background: red;
    width: 25px;
    height: 25px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    color:#fff;
  }
</style>