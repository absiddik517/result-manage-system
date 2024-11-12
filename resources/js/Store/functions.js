import { reactive } from 'vue'
import { usePage } from "@inertiajs/inertia-vue3";
export function uniqueKey(length = 10) {
  var result           = '';
  var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  var charactersLength = characters.length;
  for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
}
  
export function hasPermissionTo(permission){
  const permissions = usePage().props.value.auth_permissions;
  return permissions.indexOf(permission) !== -1
}
  
export function hasAnyPermissionTo(permissions){
  for(let i = 0; i < permissions.length; i++){
    if(hasPermissionTo(permissions[i])){
      return true;
    }
  }
  return false;
}

export function hasAllPermissionTo(permissions){
  for(let i= 0; i < permissions.length; i++){
    if(!hasPermissionTo(permissions[i])){
      return false;
    }
  }
  return true;
}
