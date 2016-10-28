###
# Rendering Crumbs-like paths
###


Vue.component 'crumbs',
  template: '<ul :class="wrpClass">
  <li v-for="node in nodes">
  <a v-if="node.name==\'/\'" :href="node.name" v-on:click.prevent="selectNode(node)">
      <i class="fa fa-home" aria-hidden="true"></i>
  </a>
  <a v-else :href="node.url" v-on:click.prevent="selectNode(node)">{{node.name}}</a>
  </li>
  </ul>'
  props: ['wrpClass','path']
  created: ()-> console.log "Crumns created"
  methods:
    selectNode: (node)->
      this.$parent.$emit("selectNode",node)
  data: ()->{wrpClass: this.wrpClass}
  computed:
    nodes:()->
      nodes=[]
      path=this.path.replace(/\/$/,"")
      arr=path.split("/")
      for v,k in arr
        nodes.push {"name":(if v!="" then v else "/"),"url":(arr.slice(0,k+1).join("/")+"/").replace("//","/")}
      return nodes
