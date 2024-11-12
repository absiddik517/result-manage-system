<template>
  <div class="form-group gray">
    <div class="label rounded-full animate-pulse" :style="styles"></div>
    <div class="h-input rounded animate-pulse"></div>
  </div>
</template>

<script>
  export default {
    name: 'Input',
    methods: {
      randomWidth(){
        let num = Math.floor(Math.random() * 99);
        console.log('initital number: '+ num) 
        if(num <= 10){
          console.log('multiply by 10, num: '+ num)
          num = num * 10
        }
        let multiplier = Math.ceil((100 - num)/20);
        return num * multiplier ?? 90
      }
    },
    computed: {
      styles(){
        return 'width: '+this.randomWidth()+'px;'
      }
    }
  }
</script>

<style scoped>
  .gray{
    --bg-color: #9ca3af;
  }
  .h-input{
    height: 35px;
    background: var(--bg-color);
  }
  .label {
    height: 18px;
    background: var(--bg-color);
    margin-bottom: 8px;
    /*width: 80px;*/
  }
  
  .rounded{
    border-radius: 3px;
  }
  .rounded-full{
    border-radius: 88px;
  }
  .animate-pulse{	
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  }
  
  @keyframes pulse {
    0%, 100% {
      opacity: 1;
    }
    50% {
      opacity: .5;
    }
  }
  
</style>