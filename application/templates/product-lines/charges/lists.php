<ul class="fcharges-data-lists" v-if="!chargeData.loading">
    <li v-for="(c, index) in chargeData.data" :key="`charge-data-index-${index}`">
        <h4 class="mb-1"><span :class="`icon ${c.chargetypes.icon} icon-dark mr-2`"></span>{{c.chargetypes.charge_name}}</h4>
        <span class="d-block">Priority #: {{c.priority}}</span>
        <span class="d-block" v-if="c.note_value">{{c.note_value}}</span>

        <table v-if="c.pvalues.length" class="wp-list-table widefat fixed striped pages mt-3 mb-3">
            <tr>
                <td v-for="(q, i) in c.pvalues" :key="`charge-value-quantity-${i}`"><strong>{{q.quantity}}</strong></td>
            </tr>
            <tr>
                <td v-for="(v, i) in c.pvalues" :key="`charge-value-${i}`">
                    <span v-if="v.value">
                        {{parseFloat(v.value).toFixed(v.decimal_value)}}{{v.unit_value}}
                    </span>
                    <span v-else>{{ v.alternative_value }}</span>
                    <span v-if="v.asterisk" class="asterisk">*</span>
                </td>
            </tr>
        </table>

        <p v-else>Please setup quantity breakdown on this charge.</p>


        <div class="d-block mt-4">
            <button class="button" @click.stop="editCharge(c)">Edit Breakdown</button>
            <button class="button" @click.stop="removeCharge(c.hid, index)">Delete</button>
        </div>
    </li>
</ul>

<p v-if="chargeData.loading" style="text-align: center;">Loading...</p>
<p v-if="!chargeData.loading && !chargeData.data.length" style="text-align: center;">This product line does not have any charges.</p>

