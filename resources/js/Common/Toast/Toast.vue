
<template>
  <TransitionGroup 
    tag="div"  
    name="fadeRight"
    class="position-fixed bottom-0 end-0 p-3" 
    style="z-index: 1151;">
    <ToastItem 
      v-for="(item, index) in toast.items"
      :item="item" 
      :index="index"
      :key="item.key"
      @remove="toast.remove(index)"
    />
  </TransitionGroup>
</template>

<script setup>
  import toast from '@/Store/toast'
  import { Inertia } from '@inertiajs/inertia'
  import { onUnmounted } from 'vue'
  import { usePage } from '@inertiajs/inertia-vue3'
  import ToastItem from '@/Common/Toast/ToastItem.vue'
  
  const page = usePage()
  const removeEventListener = Inertia.on('finish', () => {
    if(page.props.value.toast) toast.add(page.props.value.toast)
  })
  
  onUnmounted(() => removeEventListener())
</script>
