<template>
  <div v-if="items.links" class="row mt-3">
    <div class="col-sm-12 col-md-5">
      <div
        class="dataTables_info text-center text-md-left"
        id="example2_info"
        role="status"
        aria-live="polite"
      >
        Showing {{ (items.meta.current_page - 1) * items.meta.per_page }} to
        {{ items.meta.to }} of {{ items.meta.total }} entries
      </div>
    </div>
    <div v-if="items.meta.links.length > 3" class="col-sm-12 col-md-7">
      <div class="table-responsive">
        <ul class="pagination">
          <li
            v-for="(page, index) in items.meta.links"
            style="padding: 1px"
            class="paginate_button page-item"
            :class="{ disabled: !page.url, active: page.active }"
          >
            <inertia-link
              :href="page.url"
              aria-controls="example2"
              @click="emitClick"
              :data-dt-idx="index"
              :tabindex="index"
              class="page-link"
              preserve-scroll
              replace
              ><span v-html="page.label"></span
            ></inertia-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>

import { Inertia } from "@inertiajs/inertia";
export default {
  name: "Pagination",
  props: {
    items: Object,
  },
  data() {
    return {
      links: [],
    };
  },
  mounted() {
    this.replaceIcons();
    Inertia.on('finish', this.replaceIcons)
  },
  
  methods: {
    replaceIcons() {
      const replace = {
        "&laquo; Previous": "&laquo;",
        "Next &raquo;": "&raquo;",
      };
      for (let i = 0; i < this.items.meta.links.length; i++) {
        if (this.items.meta.links[i].label in replace) {
          this.items.meta.links[i].label =
            replace[this.items.meta.links[i].label];
        }
      }
    },
    
    emitClick(event){
      let url = event.target.getAttribute('href');
      this.$emit('traped')
    }
  },
};
</script>
