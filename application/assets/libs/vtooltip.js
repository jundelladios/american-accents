/**
 * Enable Bootstrap tooltips using Vue directive
 * @author Vitim.us
 * @see https://gist.github.com/victornpb/020d393f2f5b866437d13d49a4695b47
 * @example
 *   <button v-tooltip="foo">Hover me</button>
 *   <button v-tooltip.click="bar">Click me</button>
 *   <button v-tooltip.html="baz">Html</button>
 *   <button v-tooltip:top="foo">Top</button>
 *   <button v-tooltip:left="foo">Left</button>
 *   <button v-tooltip:right="foo">Right</button>
 *   <button v-tooltip:bottom="foo">Bottom</button>
 *   <button v-tooltip:auto="foo">Auto</button>
 *   <button v-tooltip:auto.html="clock" @click="clock = Date.now()">Updating</button>
 *   <button v-tooltip:auto.html.live="clock" @click="clock = Date.now()">Updating Live</button>
 */
 Vue.directive('tooltip', function(el, binding){
    var placement = jQuery(el).attr('tooltip-placement');
    var trigger = jQuery(el).attr('tooltip-trigger');
    var tooltipclass = jQuery(el).attr('tooltip-class');
    try {
        jQuery(el).tooltip({
            template: `<div class="tooltip ${tooltipclass}"><div class="arrow"></div><div class="tooltip-inner"></div></div>`,
            title: binding.value,
            placement: placement ? placement : binding.arg,
            trigger: trigger ? trigger : 'hover'
        });
    } catch($e) {
        jQuery(document).ready( function() {
            jQuery(el).tooltip({
                template: `<div class="tooltip ${tooltipclass}"><div class="arrow"></div><div class="tooltip-inner"></div></div>`,
                title: binding.value,
                placement: placement ? placement : binding.arg,
                trigger: trigger ? trigger : 'hover'
            });
        });
    }
})