/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

 CKEDITOR.editorConfig = function( config ) {
 	config.toolbarGroups = [
 		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
 		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
 		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
 		{ name: 'forms', groups: [ 'forms' ] },
 		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
 		{ name: 'about', groups: [ 'about' ] },
 		{ name: 'tools', groups: [ 'tools' ] },
 		'/',
 		{ name: 'styles', groups: [ 'styles' ] },
 		{ name: 'colors', groups: [ 'colors' ] },
 		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
 		{ name: 'insert', groups: [ 'insert' ] },
 		{ name: 'links', groups: [ 'links' ] },
 		'/',
 		{ name: 'others', groups: [ 'others' ] }
 	];

 	config.removeButtons = 'NewPage,Templates,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Flash,PageBreak,ShowBlocks,Scayt,Smiley,HorizontalRule';
    config.height="400px";
    config.resize_enabled = false;
    config.extraPlugins = 'widget,filetools,lineutils,notification,toolbar,button,notificationaggregator,imageuploader,uploadimage,uploadwidget,html5validation';
    config.skin = 'bootstrapck';
    config.filebrowserUploadUrl = 'inc/upload.php';
 };
