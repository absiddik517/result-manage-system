<template>
  <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><inertia-link :href="route('admin.dashboard.index')">Home</inertia-link></li>
        <li class="breadcrumb-item active" v-for="(item, index) in items"><a href="#">{{ item }}</a></li>
      </ol>
  </div>
</template>

<script setup>
  import { onUnmounted, onMounted, ref } from 'vue'
  import { Inertia } from '@inertiajs/inertia'
  const items = ref([]);
  
  onMounted(() => {
    items.value = filter(route().current())
  })
  
  const removeEventListener = Inertia.on('finish', () => {
    items.value = filter(route().current())
  })
  
  const filter = data => {
    const removeIfExists = [
      'admin', 'index'
    ];
    let split = data.split('.');
    let final = split.filter(item => {
      return removeIfExists.indexOf(item) < 0;
    }).map(item => {
      return item.charAt(0).toUpperCase() + item.slice(1);
    })
    return final;
  }
  
  onUnmounted(() => removeEventListener())
</script>