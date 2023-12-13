/**
 * ========================
 * == SHORTCODE BASELINE ==
 * ========================
 * Usage instructions
 *     6. Edit the _shortcodeName variable to match the name from load.php
 *     7. In the javascript, locate the "body" array. This is where you set the fields that will be available in the popup when you press the WYSIWYG button
 *     8. In the onsubmit function, pull all the properties from the body. You can follow the convention provided to get the variable from the data array.
 *     9. Add the variables into the generated shortcode string. Note that in this example, I'm passing content _between_ the shortcode tags
 *     10. Edit icon.png, as this is the icon that will display in the WYSIWYG
 */
(function() {

    // [Step 6] CHANGE ME
    var _shortcodeName = 'sampleshortcode';

    // DONT CHANGE ME
    var _shortcodeHook = '_' + _shortcodeName + '_shortcode';

    tinymce.create('tinymce.plugins.' + _shortcodeHook, {
        init : function( editor, url) {

            editor.addButton( _shortcodeName + '_shortcode', {
                title : _shortcodeName + ' Shortcode',
                cmd : _shortcodeName + '_shortcode',
                image : url + '/icon.png'
            });
 
            editor.addCommand( _shortcodeName + '_shortcode', function() {
                editor.windowManager.open({

                    title: 'Insert ' + _shortcodeName,

                    // [Step 7] EDIT ME
                    // A reference for fields:
                    // https://stackoverflow.com/questions/24871792/tinymce-api-v4-windowmanager-open-what-widgets-can-i-configure-for-the-body-op
                    body: [
                        {
                            type: 'textbox',
                            name: 'property',
                            label: 'Property',
                            tooltip: 'Put a helpful tooltip here.'
                        },
                        {
                            type: 'textbox',
                            name: 'content',
                            label: 'Content',
                            tooltip: 'This is wrapped by the shortcode tag, rather than being a property of it.'
                        }
                    ],
                    onsubmit: function( e ) {

                        // [Step 8] Fill in the shortcode in the WYSIWYG from the popup data
                        var property = '';
                        if( e.data.property != '' ){

                            property = ' property="' + e.data.property + '"';
                        }

                        var content = '';
                        if( e.data.content != '' ){

                            content = e.data.content;
                        }

                        // [Step 9] Return the shortcode string!
                        editor.insertContent( '[' + _shortcodeName + property + ']' + content + '[/' + _shortcodeName + ']' );
                    }
                });
            });
        },

    });
    // Register plugin
    tinymce.PluginManager.add( _shortcodeHook, tinymce.plugins[_shortcodeHook] );
})();