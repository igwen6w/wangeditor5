<?php

namespace Cth\WangEditor5;

use Encore\Admin\Form\Field;

class Editor extends Field
{
    protected $view = 'wangeditor5::index';

    public static $css = [
        'vendor/laravel-admin-ext/wangeditor5/style.css'
    ];

    public static $js = [
        'vendor/laravel-admin-ext/wangeditor5/index.js'
    ];


    public function render()
    {
        // config
        $config = config('admin.extensions.wangeditor5');

        // editor
        $editorMode = $config['editor_mode'] ?? 'simple';
        $editorConfig = json_encode($config['editor_config'] ?? []);

        // toolbar
        $toolbarMode = $config['toolbar_mode'] ?? 'simple';
        $toolbarConfig = json_encode($config['toolbar_config'] ?? []);

        // content
        $content = old($this->column, $this->value());

        // height
        $this->addVariables(['height' => $config['height'] ?? '500px']);

        $this->script = <<<EOT
        const { createEditor, createToolbar } = window.wangEditor;
        
        const editorConfig = {
            placeholder: '请输入内容',
            onChange(editor) {
              const html = editor.getHtml();
              $('#editor-{$this->id}').parent().parent().children('input[type="hidden"]').val(html);
            }
        };
        
        const editor = createEditor({
            selector: '#editor-{$this->id}',
            html: '{$content}',
            config: $.extend(editorConfig,{$editorConfig}),
            mode: '{$editorMode}', // or 'simple'
        });
        
        const toolbar = createToolbar({
            editor,
            selector: '#toolbar-{$this->id}',
            config: {$toolbarConfig},
            mode: '{$toolbarMode}', // or 'simple'
        });
EOT;

        return parent::render();
    }
}