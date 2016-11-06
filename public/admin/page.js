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
        this.saving = true;
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
          pEdit.saving = false;
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
        this.saving = true;
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
            pEdit.saving = false;
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
        pEdit.saving = true;
        return sl.ajax("EselAdminPanel", "deletePage", {
          "path": window.old_path
        }, function(data) {
          pEdit.saving = false;
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
