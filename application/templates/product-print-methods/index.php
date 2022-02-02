<?php if( isset( $_GET['productId'] ) ): ?>

<script type="text/javascript">
    var productID = '<?php echo $_GET['productId']; ?>';
</script>

<?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/main.php' ); ?>


<?php else: // if dont have ID ?>
<div class="notice notice-error is-dismissible">
    <p>The page that you are looking for was not found.</p>
</div>
<?php endif; ?>
