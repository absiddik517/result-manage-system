<template>
  <button 
  @click="handelClick" 
  :key="buttonKey" 
  :type="type" 
  :disabled="disabled || isLoading" 
  class="btn" 
  :class="classes"
  >
    <span v-if="clicked && isLoading && hideLabel"></span>
    <slot v-else>Submit</slot> 
    <i v-if="clicked && isLoading" class="fa fa-spinner fa-spin"></i>
  </button>
</template>

<script setup>
  import { onMounted, onUnmounted, ref  } from 'vue'
  import Spinner from './../Kit/Spinner.vue'
  import { uniqueKey } from '@/Store/functions.js'
  import { Inertia } from '@inertiajs/inertia'

  const props = defineProps({
    type: {
      type: String,
      default: 'submit',
    },
    isLoading: {
      type: Boolean,
      default: false
    },
    classes: {
      type: String,
      default: null,
    },
    hideLabel: {
      type: Boolean,
      default: false,
    },
    uppercase: {
      type: Boolean,
      default: true
    },
    varient: {
      type: String,
      default: 'dark'
    },
    class: {
      type: String,
      default: ''
    },
    size: {
      type: String,
      default: 'md'
    },
    spinner: {
      type: Boolean,
      default: true
    },
    btnDropdown: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    }
  })
  
  const classes = ref('');
  const buttonKey = ref('');
  const clicked = ref(false);
  
  onMounted(() => {
    let classesArray = [];
    if(props.btnDropdown){
      classesArray.push('dropdown-item')
    }else{
      classesArray.push(`btn-${props.varient}`);
      classesArray.push(props.size ? `btn-${props.size}` : '');
    }
    classesArray.push(`${props.class}`);
    classesArray.push(props.uppercase ? `text-uppercase` : '');
    if(props.classes) classesArray.push(props.classes)
    classes.value = classesArray.join(' ');
    buttonKey.value = uniqueKey();
  })
  
  const handelClick = () => {
    clicked.value = true;
  }
  
  const removeEventListener = Inertia.on('finish', () => {
    clicked.value = false;
  })
  
  onUnmounted(() => removeEventListener())
  
</script>
