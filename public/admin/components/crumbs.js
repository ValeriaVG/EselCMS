
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
