<div class="container print-only printable-footer">
    <?php if( is_active_sidebar( 'aa_printable_footer' ) ): ?>
    <div class="logo-printable-footer">
        <?php dynamic_sidebar( 'aa_printable_footer' ); ?>
    </div>
    <?php endif; ?>

    <div class="row mt-5">
        <?php if( is_active_sidebar( 'aa_footer_2' ) ): ?>
        <div class="col-3 footer-col">
            <?php dynamic_sidebar( 'aa_footer_2' ); ?>
        </div>
        <?php endif; ?>
        
        <?php if( is_active_sidebar( 'aa_footer_3' ) ): ?>
        <div class="col-3 footer-col d-none d-md-block">
            <?php dynamic_sidebar( 'aa_footer_3' ); ?>
        </div>
        <?php endif; ?>
    </div>
</div>