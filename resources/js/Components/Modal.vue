<template>
  <div class="modal fade" :id="id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div :class="varientClass" class="modal-header d-flex justify-content-end align-items-center">
          <h5 class="modal-title">{{ title }} <i v-show="loading" class="fa fa-spin fa-spinner"></i></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <slot name="wrapper">
        <div class="modal-body">
            <slot name="body">BODY</slot>
        </div>
        <div :class="varientClass" class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-times"></i>
          </button>
          <slot name="action"></slot>
        </div>
        </slot>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  
  const props = defineProps({
    id: String,
    title: {
      type: String,
      default: 'MODAL TITLE'
    },
    loading: {
      type: Boolean,
      default: false
    },
    varient: {
      type: String,
      default: 'light'
    },
  })
  
  const varientClass = ref('');
  
  onMounted(() => {
    varientClass.value = `bg-${props.varient}`
  })
</script>