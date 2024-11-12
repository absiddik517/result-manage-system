<template>
  
  <label :for="com_id" class="switch">
      <div class="label">{{ cLabel }} </div>
      <input :id="com_id" :checked="isChecked" :value="value" @change="updateInput" type="checkbox" name="" >
      <span class="wrapper">
        <div class="toggler round">&nbsp;</div>
      </span>
  </label>
</template>

<script>
export default {
  name: "Switch",
  model: {
    prop: 'modelValue',
    event: 'change'
  },
  props: {
    sizeLg: Boolean,
    id: {
      type: String,
      default: null
    },
    value: String,
    modelValue: { default: "" },
    label: { type: String, required: true},
    trueValue: { default: true },
    falseValue: { default: false }
  },
  mounted(){
    
  },
  computed: {
    isChecked() {
      if (this.modelValue instanceof Array) {
        return this.modelValue.includes(this.value)
      }
      // Note that `true-value` and `false-value` are camelCase in the JS
      return this.modelValue === this.trueValue
    },
    com_id(){
      if(this.id) return this.id;
      var result           = '';
      var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      var charactersLength = characters.length;
      for ( var i = 0; i < 10; i++ ) {
          result += characters.charAt(Math.floor(Math.random() * charactersLength));
      }
      return result;
    },
    cLabel() {
      let label = this.label;
      label = label.replace('_', ' ');
      
      return label;
    }
  },
  methods: {
    updateInput(event) {
  let isChecked = event.target.checked;
  let newValue;

  if (this.modelValue instanceof Array) {
    newValue = [...this.modelValue];

    if (isChecked) {
      newValue.push(this.value);
    } else {
      newValue.splice(newValue.indexOf(this.value), 1);
    }
  } else {
    newValue = isChecked ? this.trueValue : this.falseValue;
  }

  this.$emit('update:modelValue', newValue);
}

  }
};
</script>

<style scoped>

    .switch{
      --switch-width: 40px;
      --switch-height: calc(var(--switch-width)/2 + 4px);
      --circle-size: calc(var(--switch-height) - 8px);
      --icon-size: calc(var(--circle-size) - 5px);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 0px;
      margin-bottom: 16px;
      width: 100%;
    }
    
    
    input {
      display: none;
    }
    
    .label-right {
      margin-left: 16px;
      margin-right: 0!important;
    }
    
    .label{
      margin-right: 16px;
      text-align: justify;
    }
    
    .wrapper {
      min-width: var(--switch-width);
      position: relative;
    }
    .toggler{
      position: absolute;
      cursor: pointer;
      height: var(--switch-height);
      width: var(--switch-width);
      border-radius: var(--switch-width);
      top: 50%;
      right:0;
      left: 0;
      transform: translateY(-50%);
      background-color: red;
      color: red;
      -webkit-transition: 0.4s;
      transition: 0.4s;
    }
    
    
.toggler:before {
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  content: "\00D7";
  height: var(--circle-size);
  width: var(--circle-size);
  border-radius: 100%;
  left: 4px;
  bottom: 4px;
  font-size: var(--icon-size);
  background-color: white;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}
    
    
input:checked + .wrapper .toggler:before {
  content: "\2713";
  color: #17a2b8;
}
input:checked + .wrapper .toggler {
  background-color: #17a2b8;
}

input:focus + .to {
  box-shadow: 0 0 1px #101010;
}

input:checked + .wrapper .toggler:before {
  -webkit-transform: translateX(var(--circle-size));
  -ms-transform: translateX(var(--circle-size));
  transform: translateX(var(--circle-size));
}

.wrapper .toggler .round {
  border-radius: 34px;
}

.wrapper .toggler .round:before {
  border-radius: 50%;
}
</style>
