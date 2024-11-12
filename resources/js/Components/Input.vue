<template>
  <div :class="group_classes">
    <label v-show="!withoutLabel" :for="input_id">{{ label ?? ucword(field) }} <span v-show="!optional" class="text-danger">*</span> <i v-show="fetchingData || loading" class="fa fa-spinner fa-spin"></i></label>
    <select
      v-if="type == 'select'"
      ref="input"
      :name="field" 
      :disabled="disabled"
      :id="input_id"
      :class="{ 'is-invalid': form?.errors[field] }"
      :aria-describedby="input_id + '-error'"
      class="form-control"
      aria-invalid="true"
      @change="updateModelValue"
    >
      <option value="">{{ label ?? ucword(field) }}</option>
      <option v-for="(option, index) in options" :value="option[trackBy]">{{ option[labelKey] }}</option>
    </select>
    <input
      v-else
      ref="input"
      :value="modelValue"
      :disabled="disabled"
      :required="!optional"
      :max="max"
      :min="min"
      :placeholder="placeholder"
      :name="field"
      :id="input_id"
      :class="{ 'is-invalid': form?.errors[field] }"
      :aria-describedby="input_id + '-error'"
      class="form-control"
      aria-invalid="true"
      :type="type"
      @input="updateModelValue"
      @keydown.down="heighlightNext"
      @keydown.up="heighlightPrevious"
      @keydown.enter="selectHeighlighted"
    />
    
    <div ref="list" v-show="autocompleteData && showSuggestion" class="autocomplete-container">
      <ul class="autocomplete-list">
        <li v-for="(item, index) in autocompleteData" :class="{ selected: index === heighlightIndex}" @click="selectHeighlighted" @mouseenter="heighlightIndex = index" :key="index">
          {{ item[autocompleteLabelField] }}
        </li>
      </ul>
    </div>
    
    <span :id="input_id + 'HelpInline'" class="form-text">
      {{ helpText }}
    </span>
    
    <span
      :id="input_id + '-error'"
      class="error invalid-feedback"
      v-html="form?.errors[field]"
      ></span>
  </div>
</template>

<script>
import _ from 'lodash'
  export default {
    name: 'Input',
    props: {
      form: Object,
      field: String, 
      label: String,
      modelValue: [String, Number],
      withoutLabel: Boolean,
      onlyInput: Boolean, 
      withoutError: Boolean,
      disableIf: Boolean,
      optional: Boolean,
      getRow: {
        type: Function,
        default: function(params) {},
      },
      type: {
        type: String,
        default: 'text',
      },
      max:{
        default: undefined
      },
      min:{
        default: undefined
      },
      groupClass: {
        type: String,
        default: ''
      },
      autocomplete: {
        type: [String, Boolean],
        default: false,
      },
      autocompleteRoute: String,
      autocompleteAdditionalFields: Array,
      options: {
        type: [Object, Array],
        default: null,
      },
      trackBy: {
        type: String,
        default: 'id'
      },
      labelKey: {
        type: String,
        default: 'name'
      },
      id: {
        type: String,
        default: null
      },
      loading: {
        type: Boolean,
        default: false
      },
      helpText: {
        type: String,
        default: null
      }
    },
    data(){
      return {
        autocompleteData: [],
        autocompleteLabelField: null,
        fetchingData: false,
        showSuggestion: false,
        heighlightIndex: 0,
        at: Date.now(),
        listerner: null,
      }
    },
    mounted() {
      window.addEventListener("click", event => {
        if(!this.$refs?.list?.contains(event.target)){
          this.showSuggestion = false
        }
      });
    },
    computed: {
      disabled() {
        return this.form?.processing || this.disableIf;
      },
      group_classes() {
        return 'form-group '+this.groupClass
      },
      input_id() {
        if(this.id) return this.id
        return 'form_input_'+ this.field
      },
      placeholder() {
        let text = this.label ?? this.field.replace('_', ' ')
        if(!this.optional){
          text += ' *'
        }
        return text
      }
    },
    methods: {
      updateModelValue(event){
        let value = event.target.value;
        if(this.max && value > this.max){
          value = this.max;
        }
        this.$refs.input.value = value
        this.$emit('update:modelValue', value);
        this.$emit('changed', value);
        this.$emit('input', value);
        this.fetchAutocomplete(value);
      },
      selectHeighlighted(event) {
        event.preventDefault()
        if(this.autocompleteData.length == 0) return
        let item = this.autocompleteData[this.heighlightIndex][this.autocompleteLabelField]
        this.$emit('update:modelValue', item);
        this.getRow(this.autocompleteData[this.heighlightIndex]);
        this.showSuggestion = false;
      },
      heighlightNext(event) {
        event.preventDefault()
        if(this.autocompleteData.length > this.heighlightIndex + 1){
          this.heighlightIndex++;
        }
      },
      heighlightPrevious(event) {
        event.preventDefault()
        if(this.heighlightIndex > 0){
          this.heighlightIndex--;
        }
      },
      ucword(str) {
        str = str.replace(/_/g, " ")
        return str.charAt(0).toUpperCase() + str.slice(1);
      },
      async fetchAutocomplete(value){
        if(Date.now() - this.at <= 500) return;
        if(!this.autocomplete) return;
        if(!value) return;
        this.fetchingData = true;
        let auto = this.autocomplete.split('.')
        let data = {
          t: auto[0],
          f: auto[1],
          additional: this.autocompleteAdditionalFields,
          v: value
        };
        this.autocompleteLabelField = data.f;
        await axios.get(this.route(this.autocompleteRoute ?? 'autocomplete', data))
        .then(response => {
          this.heighlightIndex = 0;
          this.autocompleteData = response.data
          this.showSuggestion = true
          this.fetchingData = false
          this.at = Date.now()
        })
        .catch(err => {
          this.fetchingData = false
        });
      },
    }
  }
</script>


<style scoped>
  .autocomplete-container{
    width: 100%;
    position: relative;
  }
  .autocomplete-list {
    position: absolute;
    width: 100%;
    z-index: 999;
    padding: 0;
    box-shadow: 2px 2px 5px gray;
  }
  .autocomplete-list li {
    padding: 10px;
    cursor: pointer;
    background-color: #f9f9f9;
    border-bottom: 1px solid #ddd;
    list-style: none;
    margin: 0;
  }
  .autocomplete-list li:hover, .selected{
    background-color: #85e7ff!important;
  }
  
</style>