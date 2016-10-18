// Generated by CoffeeScript 1.10.0
(function() {
  Vue.component('pages', {
    template: '<ul class="nav bordered right-blue-gray-border-menu tree in-loading"> <li v-for="page in pages" :class="{active:page.active}"> <a v-if="page.folder" v-bind:href="page.url" v-on:click.prevent="enter(page)" >{{page.name}}</a> <a v-else v-bind:href="\'pages/?page=\'+page.path" >{{page.name}}</a> </li> </ul>',
    props: ["pages"],
    methods: {
      enter: function(page) {
        $(this.$el).addClass("in-loading");
        return this.$parent.$emit("openFolder", page);
      }
    }
  });

}).call(this);
