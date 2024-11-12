<template>
    <div class="">
        <div class="wrapper" style="position: relative;">
          
            <span v-if="inertiaLoading" class="backgroundAnimation loader"></span>
            <div
            v-if="inertiaLoading"
            style="
              position: fixed;
              top: 12px;
              right: 12px; 
              z-index: 10000;
            ">
              <i class="text-red fa fa-spinner fa-spin mx-1"></i>
            </div>
          
          <Toast />
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__wobble" src="../../../storage/app/public/images/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
            </div>
        
            <!-- Navbar -->
            <Navbar />
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <Sidebar />

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                              <h1 class="m-0">{{ pageTitle }}</h1>
                            </div><!-- /.col -->
                            <Breadcrumb />
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <slot></slot>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->


           <Footer />
        </div>
    </div>
</template>

<script>
import Sidebar from '@/Components/Partials/Sidebar.vue'
import Navbar from '@/Components/Partials/Navbar.vue'
import Footer from '@/Components/Partials/Footer.vue'
import Toast from '@/Common/Toast/Toast.vue'
import Breadcrumb from '@/Common/Breadcrumb.vue'
import { Inertia } from '@inertiajs/inertia'
import site from '@/Store/site.js'
//import { router } from '@inertiajs/inertia-vue3'
export default {
    name: "AdminLayout",
    components: {
        Footer,
        Navbar,
        Sidebar,
        Toast,
        Breadcrumb
    },
    
    data(){
      return{
        inertiaLoading: false
      }
    },
    mounted() {
      this.init() 
      document.body.classList.remove('sidebar-open')
      document.body.classList.add('sidebar-close')
      Inertia.on('start', (event) => {
        this.inertiaLoading = true;
        //site.loading()
      })
      Inertia.on('finish', (event) => {
        if (event.detail.visit.completed) {
          this.inertiaLoading = false;
          //site.loading(false)
        } else if (event.detail.visit.interrupted) {
          this.inertiaLoading = false;
          //site.loading(false)
        } else if (event.detail.visit.cancelled) {
          this.inertiaLoading = false;
          //site.loading(false)
        }
        document.body.classList.remove('sidebar-open')
        document.body.classList.add('sidebar-close')
      })
    },
    computed: {
      pageTitle() {
        let page = this.$page.component
        let parts = page.split('/')
        return parts[parts.length - 1]
      }
    },
    methods: {
        init() {
            let SELECTOR_LOADER = '.preloader'
            setTimeout(() =>{
                let $loader = $(SELECTOR_LOADER)
                if($loader) {
                    $loader.css('height', 0)
                    setTimeout(() =>{
                        $loader.children().hide()
                    }, 2000)
                }
            },2000)
        }
    }
}
</script>


<style scope>
 
.loader {
    left: 0;
    position: fixed;
    right: 0;
    top: 0;
    z-index: 10000;
}
.backgroundAnimation {
    height: 4px;
    background: #27c4f5 -webkit-gradient(linear,left top, right top,from(#27c4f5),color-stop(#a307ba),color-stop(#fd8d32),color-stop(#70c050),to(#27c4f5));
    background: #27c4f5 linear-gradient(to right,#27c4f5,#a307ba,#fd8d32,#70c050,#27c4f5);
    background-size: 500%;
    -webkit-animation: 2s linear infinite barprogress,.3s fadein;
    animation: 2s linear infinite barprogress,.3s fadein;
    width: 100%;
}
@-webkit-keyframes barprogress {
    0% {
        background-position: 0% 0
    }
    to {
        background-position: 125% 0
    }
}
@keyframes barprogress {
    0% {
        background-position: 0% 0
    }
    to {
        background-position: 125% 0
    }
}

@-webkit-keyframes fadein {
    0% {
        opacity: 0
    }
    to {
        opacity: 1
    }
}

@keyframes fadein {
    0% {
        opacity: 0
    }
    to {
        opacity: 1
    }
}
</style>
