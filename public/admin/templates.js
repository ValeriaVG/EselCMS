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
