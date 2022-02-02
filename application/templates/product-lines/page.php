<?php if( isset( $_GET['productLines'] ) ): ?>

<?php $_ids = explode( '-', $_GET['productLines'] );
    if( count( $_ids ) === 2 ):
?>

<script type="text/javascript">
    var _IDS = {
        category: '<?php echo $_ids[0]; ?>',
        subcategory: '<?php echo $_ids[1]; ?>'
    }
</script>

<?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/methods.php' ); ?>

<?php else: // if missing ID ?>
<div class="notice notice-error is-dismissible">
    <p>The page that you are looking for was not found.</p>
</div>

<?php endif; ?>

<?php else: // if dont have ID ?>
<div class="notice notice-error is-dismissible">
    <p>The page that you are looking for was not found.</p>
</div>
<?php endif; ?>
