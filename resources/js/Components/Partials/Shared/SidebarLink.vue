<template>
  <li class="nav-item">
    <Link 
      :href="route(name)" 
      class="nav-link"
      :class="{'active' : active}"
    >
      <i class="nav-icon" :class="icon"></i>
      <p>{{ text }}</p>
    </Link>
  </li>
</template>

<script>
  import { Inertia } from '@inertiajs/inertia'
  export default {
    name: 'SidebarLink',
    props: {
      name: {
        type: String,
        default: ''
      },
      icon: {
        type: String,
        default: 'far fa-circle'
      },
      text: {
        type: String,
        default: 'Link text'
      }
    },
    data() {
      return {
        active: false
      }
    },
    mounted(){
      this.active = this.route().current() == this.name
      Inertia.on('finish', (event) => {
        this.active = this.route().current() == this.name
      })
    }
  }
</script>