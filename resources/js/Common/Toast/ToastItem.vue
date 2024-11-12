<template>
  <div class="toast show" role="alert" style="transition: all 1s;">
      <div class="toast-header">
        <i class="fa mr-2" :class="{
          'text-danger fa-times-circle': item.type == 'error',
          'text-success fa-check-circle': item.type == 'success',
        }"></i>
        <strong class="me-auto toast-title" :class="{
          'text-danger': item.type == 'error',
          'text-success': item.type == 'success',
        }">
          <span v-if="item.type=='success'">Success</span>
          <span v-if="item.type == 'error'">Error</span>
        </strong>
        <small>Close in {{ second }} second<span v-if="second > 1">s</span></small>
        <button type="button" class="btn-close" @click="emit('remove')"></button>
      </div>
      <div class="timer d-flex justify-content-end"><div :class="{
        'bg-danger': item.type == 'error',
        'bg-success': item.type == 'success',
      }" :style="width"></div></div>
      <div class="toast-body toast-message" v-html="item.message"></div>
    </div>
</template>

<script setup>
  import { onMounted, ref } from 'vue'
  import toast from '@/Store/toast'
  const props = defineProps({
    item: {
      type: Object,
      default: {
        message: 'Message not found',
        type: 'error'
      }
    },
    index: {
      type: Number, 
      default: null
    },
    removeAfter: {
      type: Number, 
      default: 20000
    },
  })
  
  const width = ref('width: 100%');
  const second = ref(20);
  
  const emit = defineEmits(['remove'])
  
  onMounted(() => {
    const myInterval = setInterval(() => {
      let diff = props.item.time + props.removeAfter - Date.now();
      let persent = (diff/props.removeAfter)*100;
      second.value = Math.round(diff / 1000);
      width.value = `width: ${persent}%`;
      //console.log(width.value)
      if(diff < 1){
        emit('remove')
        clearInterval(myInterval)
      }
    }, 1)
  })
  
</script>

<style scoped>
  .timer{
    width: 100%;
    height: 2px;
    padding: 0 2px;
  }
  .timer div{
    height: 100%;
    border-radius: 4px;
  }
  .toast-title{
    font-family: 'Space Mono', Monospace;
  }
  
  .toast-message {
    font-family: 'Raleway', Sans-Serif;
    font-weight: 400;
    font-size: 1rem;
  }
</style>