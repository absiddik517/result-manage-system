<template>
  <div class="d-flex justify-content-between align-items-center">
    <div v-show="searchable">
      <div class="relative" v-if="filter[field].isActive">
        <select 
          v-if="options"
          :id="field + '_input'"
          class="form-control"
          :value="modelValue"
          @change="emitInput"
        >
          <option value="">Select {{ (label ?? field).toUpperCase() }}</option>
          <option v-for="option in options" :value="option.value">{{ option.label }}</option>
        </select>
        <input
          v-else
          :value="modelValue"
          @input="emitInput"
          :type="inputType"
          class="form-control"
          :placeholder="(label ?? field).toUpperCase()"
          autofocus=""
        />
        <i
          @click="closeSearchFn"
          class="fa fa-times filter-close"
        ></i>
      </div>
      <span class="capitalize searchable" @click="labelClick" v-else>{{ label ?? field }}</span>
      
    </div>
    
    <div v-show="!searchable">
      <span class="capitalize">{{ label ?? field }}</span>
    </div>
      
    <div v-show="!filter[field].isActive && sortable">
    <i
      @click="setOrder(field)"
      :class="{
        'fa-sort-asc':
          filter.order.field == field && filter.order.direction == 'asc',
        'fa-sort-desc':
          filter.order.field == field && filter.order.direction == 'desc',
        'fa-sort text-muted':
          filter.order.field != field || !filter.order.direction,
      }"
      class="fa pointer"
    ></i>
  </div>
    
  </div>
</template>

<script>
  import Select from '@/Components/Select.vue'
  export default{
    name: 'Filterth',
    components:{
      Select
    
    },
    props: {
      modelValue: {
        type: [String, Number, Array, Object],
        default: null
      },
      field: {
        type: String,
        required: true
      },
      label: String,
      sortable: {
        type: Boolean,
        default: false
      },
      searchable: {
        type: Boolean,
        default: false
      },
      filter: {
        type: Object,
        required: true
      },
      labelClick: {
        type: Function,
        default: () => {}
      },
      closeSearchFn: {
        type: Function,
        default: () => {}
      },
      setOrder: {
        type: Function,
        default: (x) => {}
      },
      options: {
        type: [Array, Object],
        default: null
      },
      value: String
    },
    
    data(){
      return {
        number: ['id'],
        date: ['date'],
        temp: 'not seted'
      }
    },
    
    computed: {
      inputType() {
        if(this.number.indexOf(this.field) != -1) return 'number';
        if(this.date.indexOf(this.field) != -1) return 'date';
        
        return 'text';
      }
    },
    
    methods: {
      emitInput(event){
        let value = event.target.value
        this.$emit('update:modelValue', value)
      }
    }
  }
</script>


<style scoped>
  .filter-close{
    cursor: pointer;
    position: absolute;
    z-index: 5;
    top: 50%;
    transform: translateY(-50%);
    right: 8px;
    color: gray;
    padding: 8px 8px;
  }
  
  .filter-close:hover {
    color: #000;
  }
  
  .capitalize{
    text-transform: capitalize;
  }
  .searchable {
    text-decoration-line: underline;
    text-decoration-color: #5a5a5a;
    text-decoration-style: dashed;
  }
</style>
