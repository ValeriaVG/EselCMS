window.getPages = function(dir, start, limit) {
  return window.sl.ajax("EselAdminPanel", "getPagesList", {
    "dir": dir,
    "start": start,
    "limit": limit,
    "all": 1
  }, function(res) {
    var i, p;
    i = 0;
    p = [];
    res.items.forEach(function(v) {
      p[i] = v;
      p[i].active = new RegExp(p[i].path + "$").test(document.location.href);
      return i++;
    });
    window.app.pages = p;
    window.app.count = res.count;
    return $(".in-loading").removeClass("in-loading");
  });
};

window.getTemplates = function(dir, start, limit) {
  Cookies.set("tree-limit", limit);
  return window.sl.ajax("EselAdminPanel", "getTplList", {
    "dir": dir,
    "start": start,
    "limit": limit
  }, function(res) {
    var i, p;
    i = 0;
    p = [];
    res.items.forEach(function(v) {
      p[i] = v;
      p[i].active = new RegExp(p[i].path + "$").test(document.location.href);
      return i++;
    });
    window.templates.items = p;
    window.templates.count = res.count;
    return $(".in-loading").removeClass("in-loading");
  });
};


/*
 Istall modules
 */

window.install = function(module) {
  return sl.ajax("EselAdminPanel", "setSafe", {
    "moduleName": module
  }, document.location.href = document.location.href);
};

window.uninstall = function(module) {
  return sl.ajax("EselAdminPanel", "setUnsafe", {
    "moduleName": module
  }, document.location.href = document.location.href);
};

window.sl.debug = true;

window.app = new Vue({
  el: "#app",
  components: ['crumbs', 'pages', 'tree'],
  data: {
    pages: [],
    path: ($_get['page'] != null ? cutPath($_get['page']) : "/"),
    offset: 0,
    limit: Cookies.get("tree-limit") || 10,
    count: 0
  },
  created: function() {
    getPages(this.path, this.offset, this.limit);
    this.$on("selectNode", function(node) {
      return this.path = node.url;
    });
    this.$on("openFolder", function(page) {
      if (page.folder) {
        return this.path = page.path;
      }
    });
    this.$on("pageChanged", function(page) {
      this.offset = (page - 1) * this.limit;
      return getPages(this.path, this.offset, this.limit);
    });
    return this.$on("limitChanged", function(limit) {
      Cookies.set("tree-limit", limit);
      this.limit = limit;
      return getPages(this.path, this.offset, this.limit);
    });
  },
  computed: {
    current_page: function() {
      return Math.floor(this.offset / this.limit) + 1;
    },
    new_page_url: function() {
      return "pages/?page=" + this.path;
    }
  },
  watch: {
    path: function(val) {
      this.offset = 0;
      return getPages(val, this.offset, this.limit);
    },
    offset: function(val) {
      return getPages(this.path, val, this.limit);
    },
    limit: function(val) {
      return getPages(this.path, this.offset, val);
    }
  },
  methods: {
    reload: function() {
      return getPages(this.path, this.offset, this.limit);
    },
    newFolder: function() {
      var d;
      d = this;
      return sl.askTo('Create a folder', 'Enter a folder name', function(value) {
        return sl.ajax("EselAdminPanel", "makePagesDir", {
          dir: d.path,
          name: value
        }, function(data) {
          return getPages(d.path, d.offset, d.limit);
        });
      });
    },
    removeFolder: function() {
      var d;
      d = this;
      return confirm('Are you sure you want to delete ' + d.path, function(data) {
        return sl.ajax("EselAdminPanel", "removePagesDir", {
          dir: d.path
        }, function(data) {
          var path, patharr;
          $.jGrowl("Folder was deleted", {
            theme: "tealed",
            header: "Done!"
          });
          patharr = d.path.split("/").pop();
          path = "/";
          if (patharr.length > 0) {
            path = patharr.join("/");
          }
          return app.path = path;
        });
      });
    }
  }
});

$(function() {
  return tinymce.init({
    selector: '.richText',
    plugins: ['advlist autolink lists link image charmap print preview hr anchor pagebreak', 'searchreplace wordcount visualblocks visualchars code fullscreen', 'insertdatetime media nonbreaking save table contextmenu directionality', 'emoticons template paste textcolor colorpicker textpattern imagetools codesample']
  }, {
    toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
    toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
    image_advtab: true,
    templates: [
      {
        title: 'Test template 1',
        content: 'Test 1'
      }, {
        title: 'Test template 2',
        content: 'Test 2'
      }
    ],
    content_css: ['/public/css/esel.min.css']
  });
});


/*
 * Rendering Crumbs-like paths
 */
Vue.component('crumbs', {
  template: '<ul :class="wrpClass"> <li v-for="node in nodes"> <a v-if="node.name==\'/\'" :href="node.name" v-on:click.prevent="selectNode(node)"> <i class="fa fa-home" aria-hidden="true"></i> </a> <a v-else :href="node.url" v-on:click.prevent="selectNode(node)">{{node.name}}</a> </li> </ul>',
  props: ['wrpClass', 'path'],
  created: function() {},
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
  template: '<ul class="nav bordered right-blue-grey-border-menu  tree in-loading"> <li v-for="page in pages" :class="{active:page.active,}" > <a v-if="page.folder&&!page.hidden" v-bind:href="page.url" v-on:click.prevent="enter(page)" classs="dark-primary" > <i class="fa fa-folder-o fa-fw dark-hint" aria-hidden="true" ></i> {{page.name}} </a> <a v-if="!page.folder&&!page.hidden" v-bind:href="base+page.path" > <i class="fa fa-file-text-o fa-fw dark-hint" aria-hidden="true"></i> {{page.name}} </a> <span v-if="page.hidden&&!page.folder" class="dark-hint"> <i class="fa fa-file-text-o fa-fw dark-hint" aria-hidden="true"></i> {{page.name}} </span> <span v-if="page.hidden&&page.folder" class="dark-hint"> <i class="fa fa-folder-o fa-fw dark-hint" aria-hidden="true"></i> {{page.name}} </span> </li> </ul>',
  props: ["pages", "base"],
  methods: {
    enter: function(page) {
      $(this.$el).addClass("in-loading");
      return this.$parent.$emit("openFolder", page);
    }
  }
});

if ($("#file-edit").length > 0) {
  window.fEdit = new Vue({
    el: "#file-edit",
    data: {
      saving: false,
      file: window.editor_file
    },
    methods: {
      saveTemplate: function() {
        return console.log(this.file);
      }
    }
  });
}

if ($("#page-edit").length > 0) {
  window.pEdit = new Vue({
    el: "#page-edit",
    data: {
      name: $("#page-edit [name=name]").val(),
      url: $("#page-edit [name=url]").val().replace(/\/\//, "/"),
      saving: false
    },
    computed: {
      path: function() {
        if (/\/index\.html$/.test(window.old_path) && this.url.split("/").length === window.old_path.split("/").length) {
          this.url.replace(/(\/|)$/, '/index.html');
        }
        return this.url.replace(/(\/|)$/, '.html');
      }
    },
    methods: {
      savePage: function(btn) {
        var data;
        window.blocks = {};
        $('[name^="blocks["]').each(function() {
          var name, tmp, value;
          tmp = $(this).attr("id").split("_");
          name = tmp[1];
          value = $(this).val();
          if ($(this).hasClass("richText")) {
            value = tinymce.get("block_" + name).getContent();
          }
          return window.blocks[name] = value;
        });
        data = {
          "path": $('[name=path]').val(),
          "template": $('[name=template]').val(),
          "name": $('[name=name]').val(),
          "blocks": window.blocks,
          "fields": [],
          "old_path": window.old_path
        };
        return sl.ajax("EselAdminPanel", "savePage", data, function() {
          var path;
          path = app.path;
          app.reload();
          if (data.old_path === !data.path) {
            document.location.href = document.location.href.replace(/\?page=(.*)/, "?page=" + data.path);
          }
          return $.jGrowl("Page was successfully saved", {
            header: "Saved",
            theme: 'tealed'
          });
        });
      },
      copyPage: function(btn) {
        window.blocks = {};
        return $('[name^="blocks["]').each(function() {
          var data, name, tmp, value;
          tmp = $(this).attr("id").split("_");
          name = tmp[1];
          value = $(this).val();
          if ($(this).hasClass("richText")) {
            value = tinymce.get("block_content").getContent();
          }
          window.blocks[name] = value;
          data = {
            "path": $('[name=path]').val(),
            "template": $('[name=template]').val(),
            "name": $('[name=name]').val() | 'New page',
            "blocks": window.blocks
          };
          return sl.ajax("EselAdminPanel", "savePage", data, function(res) {
            if (data.old_path === !data.path) {
              document.location.href = document.location.href.replace(/\?page=(.*)/, "?page=" + data.path);
            }
            return $.jGrowl("Page was successfully saved", {
              header: "Saved",
              theme: 'tealed'
            });
          });
        });
      },
      deletePage: function(btn) {
        return sl.ajax("EselAdminPanel", "deletePage", {
          "path": window.old_path
        }, function(data) {
          setTimeout(function() {
            return document.location.href = document.location.href.replace(/[^\/]+\.html$/, "");
          }, 500);
          return $.jGrowl("Page was successfully deleted", {
            header: "Deleted",
            theme: 'tealed'
          });
        });
      },
      changeTemplate: function() {
        return document.location.href = updateQueryStringParameter(document.location.href, "template", $('[name=template]').val());
      }
    }
  });
}

window.sl.debug = true;

window.templates = new Vue({
  el: "#templates-list",
  components: ['crumbs', 'items', 'tree'],
  data: {
    items: [],
    path: ($_get['template'] != null ? cutTplPath($_get['template']) : "/"),
    offset: 0,
    limit: Cookies.get("tree-limit") || 10,
    count: 0
  },
  created: function() {
    getTemplates(this.path, this.offset, this.limit);
    this.$on("selectNode", function(node) {
      return this.path = node.url;
    });
    this.$on("openFolder", function(page) {
      if (page.folder) {
        return this.path = page.path;
      }
    });
    this.$on("pageChanged", function(page) {
      this.offset = (page - 1) * this.limit;
      return getTemplates(this.path, this.offset, this.limit);
    });
    return this.$on("limitChanged", function(limit) {
      this.limit = limit;
      Cookies.set("tree-limit", limit);
      return getTemplates(this.path, this.offset, this.limit);
    });
  },
  computed: {
    current_page: function() {
      return Math.floor(this.offset / this.limit) + 1;
    },
    new_file_url: function() {
      return "editor/?template=" + this.path;
    }
  },
  watch: {
    path: function(val) {
      this.offset = 0;
      return getTemplates(val, this.offset, this.limit);
    },
    offset: function(val) {
      return getTemplates(this.path, val, this.limit);
    },
    limit: function(val) {
      return getTemplates(this.path, this.offset, val);
    }
  },
  methods: {
    reload: function() {
      return getTemplates(this.path, this.offset, this.limit);
    },
    newFolder: function() {
      var d;
      d = this;
      return sl.askTo('Create a folder', 'Enter a folder name', function(value) {
        return sl.ajax("EselAdminPanel", "makeTplDir", {
          dir: d.path,
          name: value
        }, function(data) {
          return getTemplates(d.path, d.offset, d.limit);
        });
      });
    },
    removeFolder: function() {
      var d;
      d = this;
      return confirm('Are you sure you want to delete ' + d.path, function(data) {
        return sl.ajax("EselAdminPanel", "removeTplDir", {
          dir: d.path
        }, function(data) {
          var path, patharr;
          $.jGrowl("Folder was deleted", {
            theme: "tealed",
            header: "Done!"
          });
          patharr = d.path.split("/").pop();
          path = "/";
          if (patharr.length > 0) {
            path = patharr.join("/");
          }
          return app.path = path;
        });
      });
    }
  }
});

var tmp, updateQueryStringParameter;

tmp = document.location.search.replace("?", "").split("&");

window.$_get = [];

$(tmp).each(function() {
  var v;
  v = this.split("=");
  if (v.length === 2) {
    return window.$_get[v[0]] = v[1];
  }
});

Array.prototype.clean = function(deleteValue) {
  var i, j, ref;
  for (i = j = 0, ref = this.length; 0 <= ref ? j <= ref : j >= ref; i = 0 <= ref ? ++j : --j) {
    if (this[i] === deleteValue) {
      this.splice(i, 1);
      i--;
    }
  }
  return this;
};

window.cutPath = function(url) {
  return url.replace(/[^\/]+\.htm[l|]$/, "");
};

window.cutTplPath = function(url) {
  return url.replace(/[^\/]+\.twig$/, "");
};


/*
  updateQueryStringParameter copied from
  http://stackoverflow.com/a/6021027/2010837
  and translated to coffeescript
 */

updateQueryStringParameter = function(uri, key, value) {
  var re, separator;
  re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
  separator = "&";
  if (uri.indexOf('?') === -1) {
    separator = "?";
  }
  if (uri.match(re)) {
    return uri.replace(re, '$1' + key + "=" + value + '$2');
  } else {
    return uri + separator + key + "=" + value;
  }
};
