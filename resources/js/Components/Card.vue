<template>
  <div class="card no-print" :class="varientClass" style="overflow: visiable;">
    <div class="card-header d-flex justify-content-between align-items-center print-bg">
      <div class="card-title flex-1">
        <slot name="title">{{ title }} <i v-show="loading" class="fa fa-spin fa-spinner"></i></slot>
      </div>
      <div class="card-tools flex-1 text-right">
        <slot name="title_right">{{ title_right }}</slot>
      </div>
    </div>
    <div class="card-body" :class="bodyClass">
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  const props = defineProps({
    isTable: {
      type: Boolean,
      default: false
    },
    varient: {
      type: String,
      default: 'light'
    },
    loading: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'Card Title'
    },
    title_right:{
      type: String,
    },
    bodyClass: {
      type: String,
      default: null
    }
  })
  
  const varientClass = ref('');
  const bodyClass = ref('');
  
  onMounted(() => {
    varientClass.value = `card-${props.varient}`
    bodyClass.value = props.isTable ? 'table-responsive p-0' : ''
    bodyClass.value += props.bodyClass
  })
  
</script>

<style>
  @media print {
    .no-print {
      display: none;
    }
  }
</style>