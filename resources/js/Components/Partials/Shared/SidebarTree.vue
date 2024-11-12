<template>
  <li class="nav-item"> <!--  :class="{'menu-open menu-is-opening': active}" -->
    <a href="#" class="nav-link"> <!-- @click="handelTreeClick" :class="{'active': active}" -->
      <slot name="title">
        <i class="nav-icon fas fa-table"></i>
        <p>
            Title
            <i class="fas fa-angle-left right"></i>
        </p>
      </slot>
    </a>
    <ul class="nav nav-treeview"> <!--  :class="{'show': active, 'hide': !active}"  -->
      <slot name="links">
        
      </slot>
    </ul>
  </li>
</template>

<script>
  import { Inertia } from '@inertiajs/inertia'
  export default{
    props: {
      prefix: {
        type: String,
        default: null,
      },
    },
    data() {
      return{
        active: false
      }
    },
    mounted() {
      this.active = this.route().current(this.prefix + '.*')
      Inertia.on('finish', (event) => {
        this.active = this.route().current(this.prefix + '.*')
      })
    },
    methods: {
      handelTreeClick: () => {
        $('.nav-item').removeClass('menu-open menu-is-opening')
        $('.nav-treeview').hide()
        
      }
    }
    
  }
  
</script>

<style>
  .hide{
    display: none;
  }
  .show{
    display: block;
  }
</style>