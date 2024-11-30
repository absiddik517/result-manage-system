<template>
  <Content>
    <div class="row">
      <div class="col-md-4">
        <Card varient="gray" body-class="p-0">
          <template #title>
            Setting <i v-show="loading" class="fa fa-spinner fa-spin"></i>
          </template>
          <div class="row">
            <div class="accordion" id="accordionExample">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                  <button
                    class="accordion-button"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseOne"
                    aria-expanded="true"
                    aria-controls="collapseOne"
                  >
                    <i class="fa fa-institution mr-2"></i> Institute
                  </button>
                </h2>
                <div
                  id="collapseOne"
                  class="accordion-collapse collapse show"
                  aria-labelledby="headingOne"
                  data-bs-parent="#accordionExample"
                >
                  <div class="accordion-body">
                    <form @submit.prevent="submit_institute">
                      <Input 
                        withoutLabel
                        v-model="form.institute.name"
                        field="name"
                        label="Name"
                        :form="form.institute"
                      />
                      <Input 
                        withoutLabel
                        v-model="form.institute.short_name"
                        field="short_name"
                        label="Short Name"
                        :form="form.institute"
                      />
                      <Input 
                        withoutLabel
                        v-model="form.institute.line_1"
                        field="line_1"
                        label="Line 1"
                        :form="form.institute"
                      />
                      <Input 
                        withoutLabel
                        optional
                        v-model="form.institute.line_2"
                        field="line 2"
                        label="Name"
                        :form="form.institute"
                      />
                      <Input 
                        withoutLabel
                        optional
                        v-model="form.institute.line_3"
                        field="line_3"
                        label="Line 3"
                        :form="form.institute"
                      />
                      <Input 
                        withoutLabel
                        optional
                        v-model="form.institute.line_4"
                        field="line_4"
                        label="Line 4"
                        :form="form.institute"
                      />
                      <div class="form-group text-right">
                        <Button :disabled="!form.institute.isDirty">
                          <i class="fa" :class="{'fa-spinner fa-spin': form.institute.processing, 'fa-save': !form.institute.processing}"></i>
                          Save
                        </Button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseTwo"
                    aria-expanded="false"
                    aria-controls="collapseTwo"
                  >
                    <i class="fa fa-image mr-2"></i> Institute Logo
                  </button>
                </h2>
                <div
                  id="collapseTwo"
                  class="accordion-collapse collapse"
                  aria-labelledby="headingTwo"
                  data-bs-parent="#accordionExample"
                >
                  <div class="accordion-body">
                    <form @submit.prevent="submit_logo">
                      <input class="form-control" type="file" @input="inputfile" accept="image/*"/>
                      <progress v-if="form.logo.progress" :value="form.logo.progress.percentage" max="100">
                        {{ form.logo.progress.percentage }}%
                      </progress>
                      <div class="preview">
                        <img :src="image_preview" alt="Preview">
                      </div>
                      
                      <div class="form-group text-right">
                        <Button :disabled="!form.logo.isDirty">
                          <i class="fa" :class="{'fa-spinner fa-spin': form.logo.processing, 'fa-save': !form.logo.processing}"></i>
                          Save
                        </Button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseThree"
                    aria-expanded="false"
                    aria-controls="collapseThree"
                  >
                    <i class="fa fa-percent mr-2"></i>Pass Mark
                  </button>
                </h2>
                <div
                  id="collapseThree"
                  class="accordion-collapse collapse"
                  aria-labelledby="headingThree"
                  data-bs-parent="#accordionExample"
                >
                  <div class="accordion-body">
                    
                    <form @submit.prevent="submit_pass_mark">
                      <Input 
                        withoutLabel
                        type="number"
                        v-model="form.pass_mark.mark"
                        field="mark"
                        label="Pass Mark Percentage"
                        :form="form.pass_mark"
                      />
                      <div class="form-group text-right">
                        <Button :disabled="!form.pass_mark.isDirty">
                          <i class="fa" :class="{'fa-spinner fa-spin': form.pass_mark.processing, 'fa-save': !form.pass_mark.processing}"></i>
                          Save
                        </Button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseFour"
                    aria-expanded="false"
                    aria-controls="collapseFour"
                  >
                    <i class="fa fa-file-text mr-2"></i> Admit card template
                  </button>
                </h2>
                <div
                  id="collapseFour"
                  class="accordion-collapse collapse"
                  aria-labelledby="headingThree"
                  data-bs-parent="#accordionExample"
                >
                  <div class="accordion-body">
                    
                    <form @submit.prevent="submit_admit_template">
                      <Select 
                        withoutLabel
                        :options="select_template"
                        v-model="form.admit.template"
                        field="template"
                        placeholder="Selete Template"
                        :form="form.admit"
                      />
                      <div class="form-group text-right">
                        <Button :disabled="!form.admit.isDirty">
                          <i class="fa" :class="{'fa-spinner fa-spin': form.admit.processing, 'fa-save': !form.admit.processing}"></i>
                          Save
                        </Button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Card>
      </div>
          
      <div class="col-md-8">
        <Card varient="dark" title="Preview">
          <div class="pad">
            <div class="logo">
              <img :src="image_preview" alt="Logo">
            </div>
            <div class="text">
              <h3>{{ form.institute.name }}</h3>
              <p>{{ form.institute.line_1 }}</p>
              <p>{{ form.institute.line_2 }}</p>
              <p>{{ form.institute.line_3 }}</p>
            </div>
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
  Content
} from "@/Components";
import toast from "@/Store/toast.js";
import _ from "lodash";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";
import { reactive, ref } from "vue";
import { isRequired } from "intus/rules";

export default {
  name: "Subject",
  layout: AdminLayout,
  components: {
    Input,
    Select,
    Content,
    Card,
    Button
  },
  props: {
    settings: Object,
    admit_templates: [Object, Array],
  },
  data() {
    return {
      form: {
        institute: useForm(this.settings.institute),
        logo: useForm({
          logo: this.settings.logo
        }),
        pass_mark: useForm({
          mark: this.settings.pass_mark
        }),
        admit: useForm({
          template: this.settings.admit_template
        }),
      },
      loading: false,
      image_preview: this.settings.logo ?? '',
    };
  },
  computed: {
    select_template(){
      let result = [];
      for(let i = 0; i < this.admit_templates.length; i++){
        result.push({
          label: this.capitalizeWords(this.admit_templates[i].replace('-', ' ')),
          value: this.admit_templates[i],
        })
      }
      return result;
    }
  },
  methods: {
    inputfile(e){
      const file = e.target.files[0]
      this.form.logo.logo = file
      if(file){
        const reader = new FileReader();
        reader.onload = e => {
          this.image_preview = e.target.result
        }
        reader.readAsDataURL(file)
      }
      console.log(this.image_preview)
    },
    submit_institute() {
      this.form.institute.clearErrors();
      this.form.institute.post(route('setting.institute'), {
        preserveScroll: true,
        onSuccess: () => {
          console.log('success');
        }
      });
    },
    submit_logo() {
      this.form.logo.clearErrors();
      this.form.logo.post(route('setting.logo'), {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
          this.modal.form.hide();
        }
      });
    },
    submit_pass_mark() {
      this.form.pass_mark.clearErrors();
      this.form.pass_mark.post(route('setting.pass_mark'), {
        preserveScroll: true,
        onSuccess: () => {
          
        }
      });
    },
    submit_admit_template() {
      this.form.admit.clearErrors();
      this.form.admit.post(route('setting.admit'), {
        preserveScroll: true,
        onSuccess: () => {
          
        }
      });
    },
    
    capitalizeWords(text) {
      return text
        .split(" ") // Split the text into words
        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()) // Capitalize the first letter of each word
        .join(" "); // Join the words back into a single string
    },
  }
};
</script>

<style scope>
  .preview{
    margin: 10px 0;
    border: 1px dotted #000;
    padding: 8px;
    width: 100%;
  }
  .preview img{
    width: 100%;
  }
  
  .pad{
    width: 100%;
    overflow: hidden;
    position: relative;
  }
  .pad .logo{
    width: 80px;
    float: left;
    position: absolute;
    top: 0;
    left:0;
    z-index: 1;
  }
  .pad .logo img{
    width: 100%;
  }
  
  .pad .text{
    width: 100%;
    text-align: center;
    background: transparent;
    float: left;
    z-index: 2;
  }
  
  .pad .text h3, .pad .text p{
    margin: 0;
  }
  
  
</style>
