
/*
 * Rendering Crumbs-like paths
 */
Vue.component('crumbs', {
  template: '<ul :class="wrpClass"> <li v-for="node in nodes"> <a v-if="node.name==\'/\'" :href="node.name" v-on:click.prevent="selectNode(node)"> <i class="fa fa-home" aria-hidden="true"></i> </a> <a v-else :href="node.url" v-on:click.prevent="selectNode(node)">{{node.name}}</a> </li> </ul>',
  props: ['wrpClass', 'path'],
  created: function() {
    return console.log("Crumns created");
  },
  methods: {
    selectNode: function(node) {
      return this.$parent.$emit("selectNode", node);
    }
  },
  data: function() {
    return {
      wrpClass: this.wrpClass
    };
  },
  computed: {
    nodes: function() {
      var arr, i, k, len, nodes, path, v;
      nodes = [];
      path = this.path.replace(/\/$/, "");
      arr = path.split("/");
      for (k = i = 0, len = arr.length; i < len; k = ++i) {
        v = arr[k];
        nodes.push({
          "name": (v !== "" ? v : "/"),
          "url": (arr.slice(0, k + 1).join("/") + "/").replace("//", "/")
        });
      }
      return nodes;
    }
  }
});

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

Vue.component('pages', {
  template: '<ul class="nav bordered right-blue-gray-border-menu tree in-loading"> <li v-for="page in pages" :class="{active:page.active}" > <a v-if="page.folder&&!page.hidden" v-bind:href="page.url" v-on:click.prevent="enter(page)" > <i class="fa fa-folder-o fa-fw dark-hint" aria-hidden="true" ></i> {{page.name}} </a> <a v-if="!page.folder&&!page.hidden" v-bind:href="\'pages/?page=\'+page.path" > <i class="fa fa-file-text-o fa-fw dark-hint" aria-hidden="true"></i> {{page.name}} </a> <span v-if="page.hidden&&!page.folder" class="dark-hint"> <i class="fa fa-file-text-o fa-fw dark-hint" aria-hidden="true"></i> {{page.name}} </span> <span v-if="page.hidden&&page.folder" class="dark-hint"> <i class="fa fa-folder-o fa-fw dark-hint" aria-hidden="true"></i> {{page.name}} </span> </li> </ul>',
  props: ["pages"],
  methods: {
    enter: function(page) {
      $(this.$el).addClass("in-loading");
      return this.$parent.$emit("openFolder", page);
    }
  }
});
