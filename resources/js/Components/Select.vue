<template>
  <div :class="group_classes">
    <label v-show="!withoutLabel" :for="input_id">
      {{ labelText ?? 'label-text="??"' }}
      <span v-show="!optional" class="text-danger">*</span>
    </label>
    <Multiselect
      :model-value="modelValue"
      :placeholder="placeholder"
      :close-on-select="true"
      :required="!optional"
      :id="input_id"
      :searchable="searchable && !dependOn"
      :object="object"
      :options="data"
      :disabled="disabled"
      :loading="isFatching"
      :setting="{ width: '100%' }"
      @change="handelSelect"
      @search-change="fetchOptions"
    >
      <template v-slot:singlelabel="{ value }">
        <div class="multiselect-single-label">
          <img
            v-show="value.img"
            class="character-label-icon"
            :src="value.img || 'default-image-url'"
          />
          {{ value.label }}
          <span class="text-muted" v-show="additional.length > 0">
            &nbsp; ({{ value[additional[0]] }})</span
          >
        </div>
      </template>

      <template v-slot:option="{ option }">
        <img
          v-show="option.img"
          class="character-option-icon"
          :src="option.img || 'default-image-url'"
        />
        <div class="flex-1 d-flex justify-content-between">
          <span>{{ option.label }}</span>
          <span v-show="include" class="text-muted">{{ option[include] }}</span>
        </div>
      </template>
    </Multiselect>
    <span
      v-if="error"
      :id="input_id + '-error'"
      class="form-text text-danger"
      v-html="error"
    ></span>
  </div>
</template>

<script>
import Multiselect from "@vueform/multiselect";
import { random_str } from "@/Composable/functions";
import toast from "../Store/toast";
import axios from "axios";

export default {
  components: {
    Multiselect
  },
  props: {
    modelValue: [String, Number, Object],
    include: String,
    dependOn: {
      type: Object,
      default: null
    },
    searchable: {
      type: Boolean,
      default: false
    },
    object: Boolean,
    placeholder: {
      type: String,
      default: "Select one"
    },
    options: {
      type: [Array, Object],
      default: []
    },
    from: String,
    id: String,
    imgKey: String,
    trackBy: { type: String, default: "value" },
    label: { type: String, default: "label" },
    additional: { type: Array, default: [] },
    groupClass: String,
    field: String,
    disableIf: Boolean,
    labelText: String,
    error: String,
    loading: Boolean,
    optional: {
      type: Boolean,
      default: false
    },
    withoutLabel: Boolean,
    getRow: {
      type: Function,
      default: function () {}
    },
    send: {
      type: Object,
      default: {}
    }
  },
  data() {
    return {
      value: [],
      records: [],
      raw_records: [],
      isFatching: false,
      lastFetchingQuery: null,
      storage: {}
    };
  },
  computed: {
    data() {
      if (this.from) return this.records;
      return this.prepare(this.options ?? []);
    },
    disabled() {
      let bool = this.loading || this.disableIf;
      if (this.dependOn) {
        for (let field in this.dependOn) {
          if (this.dependOn[field] == "" || this.dependOn[field] == null) {
            bool = bool || true;
          }
        }
      }
      return bool;
    },
    group_classes() {
      return "form-group " + this.groupClass;
    },
    input_id() {
      return this.id || random_str();
    }
  },
  watch: {
    dependOn: {
      handler: function (state, old) {
        if (!this.sameObject(state, old)) {
          this.$emit("update:modelValue", "");
        }
        for (let field in state) {
          if (state[field] == "" || state[field] == null) return;
        }
        if (this.isFatching) return;
        if (this.dependOn) {
          let url = this.route(this.from, this.dependOn);
          if (this.storage[url]) {
            this.raw_records = this.storage[url];
            this.records = this.prepare(this.storage[url]);
          } else {
            this.fetchDependedOptions();
          }
        }
      },
      deep: true
    }
  },
  mounted() {
    this.fetchOptions(null, null, this.modelValue);
  },
  methods: {
    async fetchOptions(name = "", event = null, id = undefined) {
      if (this.dependOn) return;
      let form_data = this.send;
      if (name) form_data.name = name;
      if (id) form_data.id = id;

      if (!this.from) return;
      console.log(route(this.from, form_data));
      if (this.lastFetchingQuery == form_data) return;
      this.isFatching = true;
      try {
        await axios
          .get(`${this.route(this.from, form_data)}`)
          .then(response => {
            this.records = this.prepare(response.data);
            this.raw_records = response.data;
            this.lastFetchingQuery = form_data;
          });
        this.isFatching = false;
      } catch (error) {
        console.log(error);
      }
    },

    async fetchDependedOptions() {
      let url = this.route(this.from, this.dependOn);
      console.log(url);
      this.isFatching = true;
      //this.$emit("update:modelValue", ""); // it is removing initial value
      this.records = undefined;
      try {
        const response = await axios.get(
          `${this.route(this.from, this.dependOn)}`
        );
        this.raw_records = response.data;
        this.records = this.prepare(response.data);
        this.storage[url] = response.data;
      } catch ({ message }) {
        toast.add({
          type: "error",
          message
        });
      } finally {
        this.isFatching = false;
      }
    },

    prepare(result) {
      return result.map(item => {
        let obj = {
          label: item[this.label],
          value: item[this.trackBy]
        };
        if (this.imgKey) obj.img = item[this.imgKey];
        this.additional.forEach(field => {
          if (item[field]) obj[field] = item[field];
        });
        return obj;
      });
    },

    sameObject(first, secend) {
      if (!first || !secend) return false;

      for (let index in first) {
        if (!first[index] || first[index] != secend[index]) return false;
      }
      return true;
    },

    handelSelect(value) {
      this.$emit("update:modelValue", value);
      this.$emit("change", value);
      for (let index in this.raw_records) {
        if (this.raw_records[index][this.trackBy] == value) {
          if (this.getRow && typeof this.getRow === "function") {
            this.getRow(this.raw_records[index]);
          }
          return;
        }
      }
    },
  }
};
</script>

<style lang="scss" scoped>
@import "@vueform/multiselect/themes/default.css";
img {
  width: 22px;
  border-radius: 50%;
  margin-right: 6px;
}
</style>
