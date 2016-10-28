Vue.component('pagination', {
  template: '<div class="row dark-secondary smaller"> <div class="col phone-6"> <label>Page:</label> <input type="number" v-model="page" class="tiny-input"> of {{ Math.ceil(this.count/this.limit) }} </div> <div class="col phone-6"> <label>Per page:</label> <input type="number" v-model="limit" class="tiny-input"> </div> </div>',
  props: ['page', 'limit', 'count'],
  watch: {
    page: function(val) {
      var comp;
      comp = this;
      if (val <= 0) {
        val = 1;
        comp.page = val;
      }
      if (val >= Math.ceil(comp.count / comp.limit)) {
        val = Math.ceil(comp.count / comp.limit);
        comp.page = val;
      }
      return comp.$parent.$emit("pageChanged", val);
    },
    limit: function(val) {
      if (val <= 0) {
        val = 1;
        this.limit = val;
      }
      return this.$parent.$emit("limitChanged", val);
    }
  }
});
