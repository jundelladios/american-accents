<div v-cloak id="colorsController" class="wrap">

    <h1 class="wp-heading-inline">
    Color Collections</h1>

    <a href="javascript:void(0)" @click.prevent="formInputs(true, {...defaultValue, colorjson: []})" class="page-title-action">Add New</a>

    <hr class="wp-header-end">

    <ul class="subsubsub">
        <li class="all">
            <a href="javascript:void(0)" @click.prevent="toggleActive" class="current" aria-current="page">
                <span v-if="inactive">Active Collection</span>
                <span v-else>Inactive Collection</span>
            </a>
        </li>
    </ul>

    <form action="/" @submit.prevent="loadColors">
        <p class="search-box mb-3">
            <input type="search" v-model="search" id="post-search-input" name="s" value="">
            <input type="submit" id="search-submit" class="button" value="Search Collection">
        </p>
    </form>

    <?php require_once(american_accent_plugin_base_dir() . 'application/templates/colors/lists.php'); ?>

    <?php require_once(american_accent_plugin_base_dir() . 'application/templates/colors/form.php'); ?>

</div>

<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/colors/colors.js'; ?>"></script>