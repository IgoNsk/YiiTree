jQuery(function ($) {
                
$("#"+jsTreeOptions.id)
  .jstree({ 
    // List of active plugins
    "plugins" : [ 
      "themes", "json_data", "ui", "dnd", "crrm"
    ],
    // I usually configure the plugin that handles the data first
    // This example uses JSON as it is most common
    "json_data" : { 
      // This tree is ajax enabled - as this is most common, and maybe a bit more complex
      // All the options are almost the same as jQuery's AJAX (read the docs)
      "ajax" : {
        // the URL to fetch the data
        "url" : jsTreeOptions.treeLoadRoute,
        // the `data` function is executed in the instance's scope
        // the parameter is the node being loaded 
        // (may be -1, 0, or undefined when loading the root nodes)
        "data" : function (n) { 
          // the result is fed to the AJAX request `data` option
          if (n.attr) {
            return { 
              "operation" : "get_children", 
              "id" : n.attr("id").replace("node_","")
            }; 
          }
          else {
            return { 
              "operation" : "get_tree", 
              "id" : jsTreeOptions.node
            }; 
          }
        }
      }
    },
    "crrm" : { 
      "move" : {
        "check_move" : function (m) { 
          var p = this._get_parent(m.o);
          if(!p) return false;
          p = p == -1 ? this.get_container() : p;
          if(p === m.np) return true;
          if(p[0] && m.np[0] && p[0] === m.np[0]) return true;
          return false;
        }
      }
    },
    "dnd" : {
      "drop_target" : false,
      "drag_target" : false
    },
    // the UI plugin - it handles selecting/deselecting/hovering nodes
    "ui" : {
      // this makes the node with ID node_4 selected onload
      "initially_select" : [ "node_"+jsTreeOptions.node ]
    },
    "themes": {
      "dots": true,
      "theme": "classic",
      "icons": false
    }
  })
  .delegate("a", "click.jstree", $.proxy(function (event) {
    location.href = $(this).attr("href");
  }))
  .bind("move_node.jstree", function (event, data) {
    data.rslt.o.each(function (i) {
      $.ajax({
        async : false,
        type: 'POST',
        url: "/admin/SiteTreeManagement/treeData/?"+
          jQuery.param({operation: 'move_node', id: $(this).attr("id").replace("node_","")}),
        data : {
          "ref" : data.rslt.cr === -1 ? 1 : data.rslt.np.attr("id").replace("node_",""), 
          "position" : data.rslt.cp + i
        },
        success : function (r) {
          if (!r.status) {
            $.jstree.rollback(data.rlbk);
            alert(r.message);
          }
          else {
            $(data.rslt.oc).attr("id", "node_" + r.id);
            if(data.rslt.cy && $(data.rslt.oc).children("UL").length) {
              data.inst.refresh(data.inst._get_parent(data.rslt.oc));
            }
          }
        }
      });
      return false;
    });
  });
});
