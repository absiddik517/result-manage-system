<template>
    <button 
    class="btn" 
    :class="btnClasses" 
    type="button" 
    :id="id" 
    data-bs-toggle="dropdown" 
    :data-bs-auto-close="dropdownClose" 
    aria-expanded="false">
      <slot name="btn">
        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
      </slot>
    </button>
    <div
    class="dropdown-menu"
    :aria-labelledby="id"
    :class="classes"
    >
      <h5 v-if="header" class="dropdown-header">{{ header }}</h5>
      <slot>
        <a class="dropdown-item" href="#">Action</a>
        <a class="dropdown-item" href="#">Another action</a>
      </slot>
    </div>
  
</template>

<script setup>
  import { ref, onMounted } from 'vue';
  
  const props = defineProps({
    id: {
      type: String,
      required: true,
    },
    header: {
      type: String,
      default: null
    },
    animate: {
      type: Boolean,
      default: false,
    },
    animation: {
      type: String,
      default: 'fadeIn'
    },
    veriant: {
      type: String,
      default: 'light'
    },
    stay:{
      type: Boolean,
      default: false
    },
    toggleIcon:{
      type: Boolean,
      default: false
    },
    wFull: Boolean,
  })
  
  const classes = ref('');
  const btnClasses = ref('');
  const dropdownClose = ref('');
  
  onMounted(() => {
    if(props.animate && props.animation){
      classes.value = `animate ${props.animation}`
    }
    btnClasses.value = `btn-${props.veriant}`
    if(props.toggleIcon){
      btnClasses.value += ' dropdown-toggle'
    }
    if(props.wFull){
      classes.value += ' w-full'
    }
    dropdownClose.value = props.stay ? 'outside' : 'inside'
    //alert(dropdownClose.value)
  })
  
</script>

<style>
  .w-full {
    width: 100vw;
  }
</style>