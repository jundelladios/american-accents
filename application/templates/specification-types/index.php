<!-- Prism Editor -->
<script src="<?php echo american_accent_plugin_base_url() . 'application/assets/libs/vue-prism/editor.js'; ?>"></script>
<link rel="stylesheet" href="<?php echo american_accent_plugin_base_url() . 'application/assets/libs/vue-prism/editor.css'; ?>" />

<!-- custom highlighter: -->
<script src="<?php echo american_accent_plugin_base_url() . 'application/assets/libs/vue-prism/prism.js'; ?>"></script>
<link rel="stylesheet" href="<?php echo american_accent_plugin_base_url() . 'application/assets/libs/vue-prism/theme.css'; ?>" />

<style>
  .height-200{
    height: 200px  
  }
  
  .my-editor {
    /* we dont use `language-` classes anymore so thats why we need to add background and text color manually */
    /* height:300px; */

    background: #2d2d2d;
    color: #ccc;

    /* you must provide font-family font-size line-height. Example:*/
    font-family: Fira code, Fira Mono, Consolas, Menlo, Courier, monospace;
    font-size: 14px;
    line-height: 1.5;
    padding: 5px;
  }

  .prism-editor__textarea {
      border-radius: 0;
  }

  /* optional class for removing the outline */
  .prism-editor__textarea:focus {
    outline: none;
  }
</style>

<div v-cloak id="spectypesController" class="wrap">

    <h1 class="wp-heading-inline">
    Specification Types</h1>

    <a href="javascript:void(0)" @click.prevent="formInputs(true, {...defaultValue})" class="page-title-action">Add New</a>

    <hr class="wp-header-end">

    <ul class="subsubsub">
        <li class="all">
            <a href="javascript:void(0)" @click.prevent="toggleActive" class="current" aria-current="page">
                <span v-if="inactive">Active Specification Type</span>
                <span v-else>Inactive Specification Type</span>
            </a>
        </li>
    </ul>
    
    <form action="/" @submit.prevent="init">
        <p class="search-box mb-3">
            <input type="search" v-model="search" id="post-search-input" name="s" value="">
            <input type="submit" id="search-submit" class="button" value="Search Code">
        </p>
    </form>

    <?php require_once(american_accent_plugin_base_dir() . 'application/templates/specification-types/lists.php'); ?>

    <?php require_once(american_accent_plugin_base_dir() . 'application/templates/specification-types/form.php'); ?>

</div>

<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/specification-types/json.js'; ?>"></script>

<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/specification-types/index.js'; ?>"></script>