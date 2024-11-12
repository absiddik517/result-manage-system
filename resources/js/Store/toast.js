import { reactive } from 'vue'

export default reactive({
  items: [],
  autoRemoveAfter: 20000,
  add(item){
    this.items.unshift({
      key: this.uniqueKey(),
      time: Date.now(),
      ...item
    })
  },
  remove(index){
    //alert(JSON.stringify(this.items))
    this.items.splice(index, 1)
  },
  uniqueKey(length = 10) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  }
})