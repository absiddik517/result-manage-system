<template>
  <div class="modal bounceInUp" ref="modal" id="confirmModel" tabindex="-1" aria-labelledby="conformModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" :class="{'animation-bg' : isLoading}">
        <div class="modal-body">
          
          <div class="d-flex justify-content-center">
            <div class="icon-container flex-center" :class="{'animation-pulse' : isLoading}">
              <i class="fa fa-trash"></i>
            </div>
          </div>
          
          <div class="confirm-title flex-center">
            Are you sure?
          </div>
          
          <div class="confirm-description">
            Once you delete {{ itemName }} you can not revart it.
            It may crash the system.
            So be carefull before you click the delete button.
          </div>
          
          <div class="confirm-action">
            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
              No, Cancel
            </button>
            <button @click="handelConfirmClick()" class="btn btn-danger" :disabled="isLoading">
              <i v-if="isLoading" class="fa fa-spinner fa-spin"></i>
              <template v-else>Yes, Delete it!</template>
            </button>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { reactive } from 'vue'
  import { Inertia } from "@inertiajs/inertia";
  
  export default {
    name: 'Confirm',
    props: { 
      deleteUrl: {
        required: true,
      },
      item: {
        type: String,
        default: null,
      }
    },
    
    computed: {
      itemName(){
        if(this.item){
          return ` the ${this.item},`;
        }
        return '';
      }
    },
    
    mounted(){
      let confirm = document.querySelector("#confirmModel");
      this.modal = new bootstrap.Modal(confirm);
    },
    
    data(){
      return {
        isLoading: false,
        modal: null,
      }
    },
    
    
    methods: {
      handelConfirmClick(event){
        Inertia.delete(this.deleteUrl, {
        replace: true,
        onBefore: () => {
          this.isLoading = true;
        },
        onFinish: (finish) => {
          this.isLoading = false;
        }
      });
      }
    }
  }
  
</script>

<style>
  .icon-container{
    height: 100px;
    width: 100px;
    border: 2px solid #b91c1c;
    border-radius: 50%;
    background-color: #fecaca;
    margin-bottom: 25px;
    margin-top: 30px;
  }
  
  .flex-center{
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .icon-container i {
    color: #b91c1c;
    font-size: 40px;
  }
  
  .confirm-title{
    color: #4b5563;
    font-weight: bold;
    font-size: 1.2rem;
    font-family: 'Krona One', sans-serif;
    margin-bottom: 25px;
  }
  
  .confirm-description{
    text-align: center;
    margin-bottom: 25px;
    font-family: 'Quicksand', sans-serif;
    font-weight: 400;
    padding: 0 20px;
  }
  
  .confirm-action{
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    justify-content: space-evenly;
  }
  
  .confirm-action button{
    width: 40%;
  }
  
  
  .animation-pulse {
    animation: pulse 2s infinite;
  }
  
  .animation-bg {
    animation: bganimation 2s infinite;
  }
  
  @keyframes bganimation {
      0% {
          background: #fff;
          transform: scale(1);
      }
  
      50% {
          background: #f3d3d3;
          transform: scale(0.95);
      }
  
      100% {
          background: #fff;
          transform: scale(1);
      }
  }
  
  @keyframes pulse {
      0% {
          /*transform: scale(0.9);*/
          box-shadow: 0 0 0 0 rgba(229, 62, 62, 1);
      }
  
      70% {
          /*transform: scale(1);*/
          box-shadow: 0 0 0 50px rgba(229, 62, 62, 0);
      }
  
      100% {
          /*transform: scale(0.9);*/
      }
  }

  
</style>