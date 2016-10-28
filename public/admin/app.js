window.sl.debug = true;

window.app = new Vue({
  el: "#app",
  components: ['crumbs', 'pages', 'tree'],
  data: {
    pages: [],
    path: ($_get['page'] != null ? cutPath($_get['page']) : "/"),
    offset: 0,
    limit: 10,
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
