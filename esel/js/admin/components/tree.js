Vue.component('pages', {
  template: '\
  <ul class="nav bordered right-blue-gray-border-menu" v-cloak>\
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
    return {pages: [],current_page:"",limit:0,offset:0};
  },
  methods:{
    enter:function(page){
      if(page.folder){
        getPages(page.path,app.offset,app.limit);
        app.crumbs.push(page);

      }
    }
  }
});

function getPages(dir,start,limit){
  $.ajax({
    url:"/actions/EselAdminPanel/getPagesList",
    method:"POST",
    data:{"dir":dir,"start":start,"limit":limit,"all":1},
    success:function(res){
      app.pages=res.data.pages;
      app.count=res.data.count;
    }
  }).always(function(res){
    console.log(res);
  });
}


function setFolder(page){
  var crumbs=[];
  getPages(page,app.offset,app.limit);
  var tmp=app.crumbs;
for(var i=0;i<tmp.length;i++){

  crumbs.push(tmp[i]);
  if(tmp[i].path==page){
    break;
  }
}
  app.crumbs=crumbs;
}
