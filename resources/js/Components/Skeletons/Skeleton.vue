<template>
  <Input v-for="index in items" :key="index"/>
</template>
<script>
  import Input from '@/Components/Skeletons/Input.vue';
  
  export default {
    components: {
      Input,
    },
    props: {
      items: {
        type: Number,
        default: 1
      }
    }
  }
</script>