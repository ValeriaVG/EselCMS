Vue.component('pages', {
  template: '\
  <ul class="table-of-contents" v-cloak>\
  <li v-for="page in pages">\
  <a v-if="page.folder" v-bind:href="page.url" v-on:click.prevent="enter(page)">{{page.name}}</a>\
  <a v-else v-bind:href="\'pages/?page=\'+page.path" v-bind:text="current_page"\
  v-bind:class="{active: (page.path==current_page.replace(\/^(\\\/)\/,\'\'))}">{{page.name}}</a>\
  </li>\
  </ul>',
  props:{
    pages:Array,
    current_page:String
  },
  data:function(){
    return {pages: [],current_page:""};
  },
  methods:{
    enter:function(page){
      if(page.folder){
        getPages(page.path);
        app.crumbs.push(page);

      }
    }
  }
});

function getPages(dir){
  $.ajax({
    url:"/actions/EselAdminPanel/getPagesList",
    method:"POST",
    data:{"dir":dir},
    success:function(res){
      app.pages=res.data;
    }
  });
}


function setFolder(page){
  var crumbs=[];
  getPages(page);
  var tmp=app.crumbs;
for(var i=0;i<tmp.length;i++){

  crumbs.push(tmp[i]);
  if(tmp[i].path==page){
    break;
  }
}
  app.crumbs=crumbs;
}
