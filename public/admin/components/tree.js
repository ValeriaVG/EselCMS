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
