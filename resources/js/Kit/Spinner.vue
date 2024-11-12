<template>
  <i v-if="show" class="fa fa-spinner fa-spin mx-1"></i>
</template>

<script>
  export default {
    name: 'Spinner',
    props: {
      show: Boolean
    }
  }
</script>