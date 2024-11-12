<template>
  <div class="modal fade" id="confirmModel" tabindex="-1" aria-labelledby="conformModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <div class="d-flex justify-content-center">
            <div class="icon-container flex-center"><i class="fa fa-trash"></i></div>
          </div>
          
          <div class="confirm-title flex-center">
            Are you sure?
          </div>
          
          <div class="confirm-description">
            Once you delete you can not revart it.
            So be carefull before you click delete button.
          </div>
          
          <div class="confirm-action">
            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
              Cancel
            </button>
            <button @click="handelConfirmClick" class="btn btn-danger">
              Ok, Delete it!
            </button>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { reactive } from 'vue'
  
  export default {
    name: 'Confirm',
    
    methods: {
      handelConfirmClick: () => {
        alert('confirmed.');
      }
    }
  }
  
</script>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Krona+One&family=Raleway:wght@200;400&display=swap');

  .icon-container{
    height: 100px;
    width: 100px;
    border: 2px solid #b91c1c;
    border-radius: 50%;
    background-color: #fecaca;
    margin-bottom: 25px;
    margin-top: 10px;
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
    font-family: 'Raleway', sans-serif;
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
  
  .btn-close{
    position: absolute;
    top: 10px;
    right: 10px;
  }
</style>