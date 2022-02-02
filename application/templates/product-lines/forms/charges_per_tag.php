<div class="mt-3 mb-3">

    <div class="mb-3">
        <input 
        v-model="inputs.second_side" 
        type="checkbox" 
        value="second_side" 
        :true-value="1"
        :false-value="0"
        >
        <label for="second_side"> Second side is printable?</label>
        <small>("per panel" for offset napkins)</small>
    </div>

    <div class="mb-3">
        <input 
        v-model="inputs.wrap" 
        type="checkbox" 
        value="wrap"
        :true-value="1"
        :false-value="0">
        <label for="wrap"> Is this printed as a wrap?</label>
        <small>("per side" first and second side together)</small>
    </div>

    <!-- <div class="mb-3">
        <input 
        v-model="inputs.bleed" 
        type="checkbox" 
        value="bleed"
        :true-value="1"
        :false-value="0">
        <label for="bleed"> Is this printed as a bleed?</label>
    </div> -->

    <div class="mb-3">
        <input 
        v-model="inputs.multicolor" 
        type="checkbox" 
        value="multicolor"
        :true-value="1"
        :false-value="0">
        <label for="multicolor"> Can be printed more than one color?</label>
        <small>(per color)</small>
    </div>

    <!-- <div class="mb-3">
        <input 
        v-model="inputs.process" 
        type="checkbox" 
        value="process"
        :true-value="1"
        :false-value="0">
        <label for="process"> Can be printed with 4 color process?</label>
        <small>(typically only for Tradition Print Methods)</small>
    </div> -->

    <!-- <div class="mb-3">
        <input 
        v-model="inputs.white_ink" 
        type="checkbox" 
        value="whiteink"
        :true-value="1"
        :false-value="0">
        <label for="whiteink"> Has white-ink surcharge?</label>
        <small>(typically only for digitally-printed Coasters and Beverage wraps.)</small>
    </div> -->

    <!-- <div class="mb-3">
        <input 
        v-model="inputs.hotstamp" 
        type="checkbox" 
        value="hotstamp"
        :true-value="1"
        :false-value="0">
        <label for="hotstamp"> Has hotstamp imprint surcharge?</label>
        <small>(typically only for Coasters and Beverage Wraps.)</small>
    </div> -->

    <div class="mb-3">
        <input 
        v-model="inputs.per_thousand" 
        type="checkbox" 
        value="per_thousand"
        :true-value="1"
        :false-value="0">
        <label for="per_thousand"> is this measured as per thousand?</label>
        <small>(per thousand)</small>
    </div>

    <div class="mb-3">
        <input 
        v-model="inputs.per_item" 
        type="checkbox" 
        value="per_thousand"
        :true-value="1"
        :false-value="0">
        <label for="per_item"> priced per item?</label>
        <small>(per item)</small>
    </div>

    <div class="mb-3">
        <label> Amount for Charge Setup</label>
        <input v-model="inputs.setup_charge" type="number">
    </div>

    <div class="mb-3">
        <label class="mb-2 d-block"> Select Coupon Code</label>
        <div v-if="!coupons.loading">
            <select v-if="coupons.data.length" v-model="inputs.coupon_code_id">
                <option v-for="(coupon, index) in coupons.data" :value="coupon.hid">{{coupon.code}}</option>
            </select>
            <p v-else style="color: #e48e8e;">Please add coupon code.</p>
        </div>
    </div>

</div>