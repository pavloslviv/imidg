/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    config.filebrowserBrowseUrl = '/lib/ckeditor/plugins/kcfinder/browse.php?type=files';
    config.filebrowserImageBrowseUrl = '/lib/ckeditor/plugins//kcfinder/browse.php?type=images';
    config.filebrowserFlashBrowseUrl = '/lib/ckeditor/plugins//kcfinder/browse.php?type=flash';
    config.filebrowserUploadUrl = '/lib/ckeditor/plugins//kcfinder/upload.php?type=files';
    config.filebrowserImageUploadUrl = '/lib/ckeditor/plugins//kcfinder/upload.php?type=images';
    config.filebrowserFlashUploadUrl = '/lib/ckeditor/plugins//kcfinder/upload.php?type=flash';
    config.toolbar = [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
        { name: 'insert', items: [ 'Table', 'Image',  'Youtube','HorizontalRule', 'SpecialChar'  ] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        '/',
        { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
        { name: 'basicstyles', groups: [ 'basicstyles', 'align' ], items: [ 'Bold', 'Italic', 'Underline', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent' ] },
        { name: 'advstyles',  groups: [ 'cleanup' ], items:['Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']},
    ];

    config.extraPlugins = 'codemirror,youtube';
};
