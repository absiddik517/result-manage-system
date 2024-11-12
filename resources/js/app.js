require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { hasPermissionTo, hasAnyPermissionTo, hasAllPermissionTo } from "@/Store/functions.js";



const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
const HighlightDirective = {
  mounted(el, binding) {
    highlight(el, binding);
  },
  updated(el, binding) {
    highlight(el, binding);
  },
};

function highlight(el, binding) {
  let searchTerms = Array.isArray(binding.value) ? binding.value : [binding.value];
  searchTerms = searchTerms.filter((element) => {
    return element !== null && element !== undefined && element !== '';
  });
  
  let instances = [];
  if (el.textContent) {
    for (let j = 0; j < searchTerms.length; j++) {
      if (el.textContent.match(new RegExp(searchTerms[j], 'gi'))) {
        console.log('match found')
        instances.push(el);
      }
    }
  }
  console.log(instances)
  if (instances) {
    for (let i = 0; i < instances.length; i++) {
      let span = document.createElement('span');
      span.classList.add('highlight');

      let highlighted = instances[i].innerHTML;
      for (let j = 0; j < searchTerms.length; j++) {
        highlighted = highlighted.replace(
          new RegExp(searchTerms[j], 'gi'),
          `<span class="highlight">${searchTerms[j]}</span>`
        );
      }

      instances[i].innerHTML = highlighted;
      console.log(highlighted)
    }
  }
}


const app = createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
      
      const MyPlugins = {
        install: (app) => {
          app.directive('highlight', HighlightDirective)
        },
      };
      
      return createApp({ render: () => h(app, props) })
        .use(plugin)
        .use(MyPlugins)
        .component('InertiaHead', Head)
        .component('InertiaLink', Link)
        .component('Link', Link)
        .mixin({ methods: { 
          route, hasPermissionTo,
          hasAllPermissionTo, hasAnyPermissionTo
          } 
        })
        .mount(el);
    }
});

InertiaProgress.init({ 
  color: 'red',
  delay: 100,
  showSpinner: true,
});



