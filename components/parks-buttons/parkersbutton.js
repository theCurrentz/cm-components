(function() {
    tinymce.create('tinymce.plugins.chromapro', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(editor, url) {
          editor.addButton( 'coolquotes', {
              title: 'Cool Quotes',
              cmd: 'coolquotes',
              icon: 'icon dashicons-editor-quote'
          });

          editor.addButton( 'dropcap', {
              title: 'DropCap',
              cmd: 'dropcap',
              icon: 'icon dashicons-editor-bold'
          });

          editor.addButton( 'question', {
              title: 'Question',
              cmd: 'question',
              icon: 'icon dashicons-editor-help'
              });

          editor.addButton( 'coolbutton', {
                  title: 'Cool Button',
                  cmd: 'coolbutton',
                  icon: 'icon dashicons-external'
                  });

          editor.addButton( 'bubble', {
                  title: 'Bubble',
                  cmd: 'bubble',
                  icon: 'icon dashicons-admin-comments'
                  });
          editor.addButton( 'table', {
                  title: 'Table',
                  cmd: 'table',
                  icon: 'icon dashicons-editor-table'
                      });
          editor.addButton( 'anchor', {
                  title: 'Anchor',
                  cmd: 'anchor',
                  icon: 'icon dashicons-list-view'
                      });

          //Gift Card Button and Command
          editor.addButton( 'gift-card', {
                  title: 'Gift Card',
                  cmd: 'gift',
                  icon: 'icon dashicons-feedback'
          });
          editor.addCommand('gift', function() {
              var selected_text = editor.selection.getContent();
              var return_text = '';
              return_text = '<section class="gg-box"><div class="gg-left"><img class="gg-image image-container image-container-left"/></div><div class="gg-right"><h2>Place Holder</h2><p>This is the first paragraph of the gift guide...</p><p>This is another paragraph of the gift guide...</p><a class="gg-buy" href="">Buy</a><span class="gg-seller"><span class="gg-price">$100</span></div><p class="gg-numb">1</p></section>   &nbsp;';
              editor.execCommand('mceInsertContent', 0, return_text);
          });

          //Gift Card Button and Command
          editor.addButton( 'rating-card', {
                  title: 'Rating Card',
                  cmd: 'rating-card',
                  icon: 'icon dashicons-cart'
          });
          editor.addCommand('rating-card', function() {
              var selected_text = editor.selection.getContent();
              var return_text = '';
              return_text = '<div class="rating_card"><div class="rating_card_left"><img href="#" class="rating_card_image image_container image_container_left"/><div class="rating_card_score"><div class="rating_card_score_text">Score:</div><div class="rating_card_score_text"> 9.8</div></div><div class="rating_card_stars rating"><span>★</span><span>★</span><span>★</span><span>★</span><span>☆</span></div></div><div class="rating_card_middle"><h2 class="rating_card_middle_title">Place Holder</h2><p>This is the first paragraph about the item...</p><p>This is another paragraph about the item...</p><div class="rating_card_shop" href="">Visit</div><span class="rating_card_seller"></div><div class="rating_card_right"><ul class="rating_card_list"><li>90+ Interesting Things</li><li>Fast Response</li><li>24hr Support</li><li>Money Back Guarantee</li><li>Top Security Feaures for everyday use</li><li>Beautiful design</li></ul></div><p class="rating_card_link_wrap"><a href="#" class="rating_card_a">&nbsp;</a></p></div>   &nbsp;';
              editor.execCommand('mceInsertContent', 0, return_text);
          });


          //add unique ID button and command
          editor.addButton( 'add-unique-id', {
              title: 'Add ID',
              cmd: 'add-unique-id',
              icon: 'icon dashicons-flag',
          });

          editor.addCommand('add-unique-id', function() {
            var selected_node = editor.selection.getNode(),
                applyidWindow = editor.windowManager.open( {
                    title: 'Add Unique ID',
                    body: [
                      {
                        type: 'textbox',
                        name: 'addUniqueID',
                        label: "Add Unique ID",
                        minWidth: 300,
                        minHeight: 40,
                        value: ''
                      }
                    ],
                    buttons: [
                      {
                        text: "Ok",
                        subtype: "primary",
                        onclick: function() {
                          applyidWindow.submit();
                        }
                      },
                    ],
                    onsubmit: function(e){
                      var returnId = e.data.addUniqueID;
                          selected_node.setAttribute("id", returnId);
                    }
                });
          });

          editor.addButton( 'clear-html', {
                  title: 'Clear HTML',
                  cmd: 'clear-html',
                  icon: 'icon dashicons-no'
          });
          editor.addCommand('clear-html', function() {
            var selected_text = editor.selection.getContent(),
            return_text = selected_text.replace(/\<(?!img).*?\>/g, "");
            editor.execCommand('mceInsertContent', 0, return_text);
          });

          editor.addButton( 'auto-correct', {
                  title: 'Auto Correct',
                  cmd: 'auto-correct',
                  icon: 'icon dashicons-editor-spellcheck'
          });
          editor.addCommand('auto-correct', function() {
            var selected_text = editor.getContent(),
                fragment = document.createDocumentFragment(),
                cont = document.createElement('div')
            cont.innerHTML = selected_text
            fragment.appendChild(cont)

            function textNodeFindnExecute(node) {
              let next;
              if (node.nodeType === 1) {
                if (node = node.firstChild) {
                  do {
                    next = node.nextSibling
                    textNodeFindnExecute(node)
                  } while (node = next)
                }
              } else if (node.nodeType === 3) {
                //pronouns variable is localized to the 'admin-assistant' script within chroma components
                [].forEach.call(properNouns, (word) => {
                  let wordMatch = new RegExp(word, 'i')
                  node.data = node.data.replace(wordMatch, word)
                })
              }
              return node;
            }

            var allNodes = fragment.children[0].children

            if (allNodes.length > 0) {
              [].forEach.call(allNodes, (e) => {
                e = textNodeFindnExecute(e)
              })
            }
            return_text = cont.innerHTML
            console.log(return_text)
            editor.setContent(return_text);
          });

          editor.addButton('image-container', {
                  type: 'menubutton',
                  title: 'Image Container',
                  icon: 'icon dashicons-align-right',
                  menu: [
                    {
                      text: 'Center',
                      onclick: function() {
                        var selected_text = editor.selection.getContent();
                        var return_text = '';
                        return_text = '<div class="image-container">' + selected_text + '</div>';
                        editor.insertContent(return_text);
                      }
                    },
                    {
                      text: 'Left',
                      onclick: function() {
                        var selected_text = editor.selection.getContent();
                        var return_text = '';
                        return_text = '<div class="image-container image-container-left">' + selected_text + '</div>';
                        editor.insertContent(return_text);
                      }
                    },
                    {
                      text: 'Right',
                      onclick: function() {
                        var selected_text = editor.selection.getContent();
                        var return_text = '';
                        return_text = '<div class="image-container image-container-right">' + selected_text + '</div>';
                        editor.insertContent(return_text);
                      }
                    },
                    {
                      text: 'Inline',
                      onclick: function() {
                        var selected_node = editor.selection.getNode();
                        selected_node.classList.add("chrommainline");
                      }
                    },
                    {
                      text: 'Dark',
                      onclick: function() {
                        var selected_text = editor.selection.getContent();
                        var return_text = '';
                        return_text = '<div class="image-container--dark">' + selected_text + '</div>';
                        editor.insertContent(return_text);
                      }
                    },
                  ]
            });

            editor.addCommand('image-container', function() {
                                  var selected_text = editor.selection.getContent();
                                  var return_text = '';
                                  return_text = '<div class="image-container">' + selected_text + '</div>';
                                  editor.execCommand('mceInsertContent', 0, return_text);
                            });
            editor.addCommand('image-container-left', function() {
                                  var selected_text = editor.selection.getContent();
                                  var return_text = '';
                                  return_text = '<div class="image-container image-container-left">' + selected_text + '</div>';
                                  editor.execCommand('mceInsertContent', 0, return_text);
                            });
            editor.addCommand('image-container-right', function() {
                                  var selected_text = editor.selection.getContent();
                                  var return_text = '';
                                  return_text = '<div class="image-container image-container-right">' + selected_text + '</div>';
                                  editor.execCommand('mceInsertContent', 0, return_text);
                            });

          editor.addCommand('coolbutton', function() {
                                var selected_node = editor.selection.getNode();
                                selected_node.classList.add("iphone-8-link");
                                selected_node.classList.add("blue-gradient");
                                selected_node.classList.add("box-shadow-rise");
                                selected_node.classList.add("ripple");
                          });

          editor.addCommand('question', function() {
                       var selected_text = editor.selection.getContent();
                       var return_text = '';
                       return_text = '<a href="#comments" class="postquestion"><span>' + selected_text + '</span><br><u>Let us know in the Comments below.</u></a>';
                       editor.execCommand('mceInsertContent', 0, return_text);
                   });

          editor.addCommand('coolquotes', function() {
                       var selected_text = editor.selection.getContent();
                       var return_text = '';
                       return_text = '<div class="divquote"><span class="coolquote">' + selected_text + '</span></div>';
                       editor.execCommand('mceInsertContent', 0, return_text);
                   });

          editor.addCommand('dropcap', function() {
                        var selected_text = editor.selection.getContent();
                        var return_text = '';
                        return_text = '<span class="dropcap">' + selected_text + '</span>';
                        editor.execCommand('mceInsertContent', 0, return_text);
                  });

          editor.addCommand('bubble', function() {
                                var selected_text = editor.selection.getContent();
                                var return_text = '';
                                return_text = '<span class="bubble">' + selected_text + '</span>';
                                editor.execCommand('mceInsertContent', 0, return_text);
                          });
          editor.addCommand('table', function() {
                                var selected_text = editor.selection.getContent();
                                var return_text = '';
                                return_text = '<table id="table"><tr><td></td><th>Width</th><th>Height</th><th>Depth</th></tr><tr><th>iPhone 8</th><td>138.3 mm</td><td>67.1 mm</td><td>7.1 mm</td></tr><tr><th>iPhone 8 Plus</th><td>138.44 mm</td><td>67.26 mm</td><td>7.21 mm</td></tr><tr><th>iPhone X</th><td>158.2 mm</td><td>77.9 mm</td><td>7.3 mm</td></tr></table>';
                                editor.execCommand('mceInsertContent', 0, return_text);
                                });
          editor.addCommand('anchor', function() {
                                var selected_text = editor.selection.getContent();
                                var return_text = '';
                                return_text = '<a name="' + selected_text + '">' + selected_text ;
                                editor.execCommand('mceInsertContent', 0, return_text);
                          });

        },

        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function(n, cm) {
            return null;
        },

        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Parkers TinyMCE  buttons',
                author : 'Parker Westfall',
                version : "2.0"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add( 'chromapro_tc_button', tinymce.plugins.chromapro );
})();
