Vue.component 'pagination',
  template: '
  <div>
  Page # <input type="number" v-model="page" class="tiny-input"> of {{Math.ceil(count/limit)}} by <input type="number" v-model="limit" class="tiny-input">
  </div>
  '
  props: ['page','limit','count']
  watch:
    page: (val)->
      if val<=0
        val=1
        this.page=val
      if val>=Math.ceil(this.count/this.limit)
        val=Math.ceil(this.count/this.limit)
        this.page=val
      this.$parent.$emit("pageChanged",val)
    limit: (val)->
      if val<=0
        val=1
        this.limit=val
      this.$parent.$emit("limitChanged",val)
