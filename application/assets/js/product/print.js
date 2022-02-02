var productPrintTemplate = /*html */`
<div class="printable-wrap">
<style type="text/css">
@media print {
    #printable {
        display: block!important;
    }
}
</style>
<div id="printable" style="display: none;">
 <p>Lorem ipsum dolor print me.</p>
</div>
</div>
`;