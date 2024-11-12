import { reactive } from 'vue'

export default reactive({
  inertiaLoading: false,
  
  loading: (status = true) => {
    this.inertiaLoading = status
  },
  isLoading: () => {
    return this.inertiaLoading
  }
})