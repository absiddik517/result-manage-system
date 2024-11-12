import intus from "intus";
import {useForm} from "@inertiajs/inertia-vue3";

export default function (definition, rememberKey) {
  let fields = {}; // holds fields with default values
  let rules = {}; // holds fields with their validation rules
  
  for (let field in definition) {
    fields[field] = definition[field][0];
    rules[field] = definition[field][1] || [];
  }

  let form = rememberKey ? useForm(rememberKey, fields) : useForm(fields);

  return new Proxy(form, {
    get(target, prop) {
      if (prop === "submit") {
        form.clearErrors();

        const validation = intus.validate(form.data(), rules);

        if (!validation.passes()) {
          form.setError(validation.errors());
          console.log(validation.errors())
          return () => {};
        }
      }

      return target[prop];
    },
  });
}