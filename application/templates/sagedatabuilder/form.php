<div class="mt-10">
    <h1 class="wp-heading-inline">Sage Form</h1>
</div>


<div class="mb-2">
    <div class="mb-1">
        <h4 class="mb-2"><label>Choose Update Type</label></h4>
    </div>
    <select v-model.number="sageproduct.data.updateType">
        <option :value="1">Basic pricing only</option>
        <option :value="0">Full product update (all fields)</option>
        <option :value="3">Select fields (only fields included will be updated)</option>
    </select>
    <p>Notes: Use UpdateType 0 to update all fields. Use update type 1 if only updating the pricing. If doing a partial update (not including all fields) use Type 3. Important: When using Type 3 completely omit any fields you do not want to update. Any fields that are not included will not be updated and will retain any previously existing value. Any fields you include with a blank value will clear the field's value (removing any previously existing data)</p>
    <p>For Basic Pricing Only: Quantities, Prices, Price Code, Pieces Per Unit, Quote Upon Request</p>
</div>


<div v-if="sageproduct.selected && 'name' in sageproduct.data">
    <div class="mb-2">
        <div class="mb-1">
            <h4 class="mb-2"><label>Product Name</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('name')">Remove Field</a></h4>
        </div>
        <input type="text" v-model="sageproduct.data.name" class="full-width">
    </div>
</div>

<div class="mb-2" v-if="'description' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Description</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('description')">Remove Field</a></h4>
    </div>
    <textarea class="full-width" rows="3" v-model="sageproduct.data.description"></textarea>
</div>

<div class="mb-2" v-if="'keywords' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Keywords</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('keywords')">Remove Field</a></h4>
    </div>
    <textarea class="full-width" rows="3" v-model="sageproduct.data.keywords"></textarea>
</div>

<div class="mb-2" v-if="'colors' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Colors</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('colors')">Remove Field</a></h4>
    </div>
    <textarea class="full-width" rows="2" v-model="sageproduct.data.colors"></textarea>
</div>

<div class="mb-5" v-if="'themes' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Themes</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('themes')">Remove Field</a></h4>
    </div>
    <textarea class="full-width" rows="2" v-model="sageproduct.data.themes"></textarea>
</div>


<div class="mb-5" v-if="'sizes' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Sizes</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('sizes')">Remove Field</a></h4>
    </div>
    <div v-for="(entry, entryindex) in sageproduct.data.sizes" class="mb-2">
        <input type="text" placeholder="Value" v-model="entry.val"> <input type="number" placeholder="Unit" v-model.number="entry.units"> <input type="number" placeholder="Type" v-model.number="entry.type">
        <a href="#appendentry" @click.prevent="sageproduct.data.sizes.push({ val: '', units: '', type: '' })" class="ml-1">Append</a>
        <a href="#removeentry" @click.prevent="sageproduct.data.sizes.splice(entryindex, 1)">Remove</a>
    </div>
</div>


<div class="mb-5" v-if="'quantities' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Quantities</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('quantities')">Remove Field</a></h4>
    </div>
    <div v-for="(entry, entryindex) in sageproduct.data.quantities" class="mb-2">
        <input type="number" placeholder="Value" v-model.number="sageproduct.data.quantities[entryindex]">
        <a href="#appendentry" @click.prevent="sageproduct.data.quantities.push('')" class="ml-1">Append</a>
        <a href="#removeentry" @click.prevent="sageproduct.data.quantities.splice(entryindex, 1)">Remove</a>
    </div>
</div>


<div class="mb-5" v-if="'prices' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Prices (Higher to Lower)</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('prices')">Remove Field</a></h4>
    </div>
    <div v-for="(entry, entryindex) in sageproduct.data.prices" class="mb-2">
        <input type="number" placeholder="Value" v-model.number="sageproduct.data.prices[entryindex]">
        <a href="#appendentry" @click.prevent="sageproduct.data.prices.push('')" class="ml-1">Append</a>
        <a href="#removeentry" @click.prevent="sageproduct.data.prices.splice(entryindex, 1)">Remove</a>
    </div>
</div>


<div class="mb-5" v-if="'prCode' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Price Code</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('prCode')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.prCode">
</div>


<div class="mb-5" v-if="'piecesPerUnit' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Pieces Per Unit</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('piecesPerUnit')">Remove Field</a></h4>
    </div>
    <div v-for="(entry, entryindex) in sageproduct.data.piecesPerUnit" class="mb-2">
        <input type="number" placeholder="Value" v-model.number="sageproduct.data.piecesPerUnit[entryindex]">
        <a href="#appendentry" @click.prevent="sageproduct.data.piecesPerUnit.push('')" class="ml-1">Append</a>
        <a href="#removeentry" @click.prevent="sageproduct.data.piecesPerUnit.splice(entryindex, 1)">Remove</a>
    </div>
</div>


<div class="mb-5" v-if="'piecePrices' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Piece Prices</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('piecePrices')">Remove Field</a></h4>
    </div>
    <div v-for="(entry, entryindex) in sageproduct.data.piecePrices" class="mb-2">
        <input type="number" placeholder="Value" v-model.number="sageproduct.data.piecePrices[entryindex]">
        <a href="#appendentry" @click.prevent="sageproduct.data.piecePrices.push('')" class="ml-1">Append</a>
        <a href="#removeentry" @click.prevent="sageproduct.data.piecePrices.splice(entryindex, 1)">Remove</a>
    </div>
</div>


<div class="mb-2" v-if="'quoteUponRequest' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Quote Upon Request</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('quoteUponRequest')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.quoteUponRequest">
</div>


<div class="mb-2" v-if="'priceIncludesClr' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Price includes color</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('priceIncludesClr')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.priceIncludesClr">
</div>


<div class="mb-2" v-if="'priceIncludesSide' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Price includes side</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('priceIncludesSide')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.priceIncludesSide">
</div>


<div class="mb-2" v-if="'priceIncludesLoc' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Price includes loc</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('priceIncludesLoc')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.priceIncludesLoc">
</div>




<div class="mb-2" v-if="'options' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Options</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('options')">Remove Field</a></h4>
    </div>
    <div v-for="(entry, entryindex) in sageproduct.data.options" class="mb-2" style="padding: 20px; border: 1px solid #cccccc;">
        
        <div>
            <input type="text" placeholder="Name" v-model="entry.name">
            <input type="number" placeholder="Pricing Is Total" v-model.number="entry.pricingIsTotal">
            <input type="text" placeholder="Price Code" v-model="entry.priceCode">

            <div v-for="(entry2, entryindex2) in entry.values" class="mt-2">
                <textarea type="text" placeholder="Value" v-model="entry2.value" class="full-width" rows="2"></textarea>
                <input v-for="(entry3, entryindex3) in entry2.prices" type="number" v-model.number="entry2.prices[entryindex3]">
                <a href="#insertprice" @click.prevent="entry2.prices.push(0)" class="ml-1">New Price</a>
                <a href="#insertprice" v-if="entry2.prices.length" @click.prevent="entry2.prices.splice(entry2.prices.length-1, 1)" class="ml-1">Remove Last Price</a>
            </div>
        </div>


        <a href="#appendentry" @click.prevent="sageproduct.data.options.push({ name: '', pricingIsTotal: 0, priceCode: 'VVVVVV', values: [{ name: '', prices: [] }] })" class="ml-1">Append</a>
        <a href="#removeentry" @click.prevent="sageproduct.data.options.splice(entryindex, 1)">Remove</a>

        <hr>
    </div>
</div>



<div class="mt-10" v-if="'additionalCharges' in sageproduct.data">
    <h3 class="wp-heading-inline">Additional Charges &#8250; <a href="#removefield" @click.prevent="removeUpdateField('additionalCharges')">Remove Field</a></h3>
    <p>Note: you can't remove this field to protect its data.</p>
</div>


<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Setup Charge</label></h4>
    </div>
    <input type="number" v-model.number="sageproduct.data.additionalCharges.setupChg">
</div>


<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Setup Charge Code</label></h4>
    </div>
    <input type="text" v-model="sageproduct.data.additionalCharges.setupChgCode">
    <hr>
</div>



<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Screen Charge</label></h4>
    </div>
    <input type="number" v-model.number="sageproduct.data.additionalCharges.screenChg">
</div>


<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Screen Charge Code</label></h4>
    </div>
    <input type="text" v-model="sageproduct.data.additionalCharges.screenChgCode">
    <hr>
</div>


<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Plate Charge</label></h4>
    </div>
    <input type="number" v-model.number="sageproduct.data.additionalCharges.plateChg">
</div>


<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Plate Charge Code</label></h4>
    </div>
    <input type="text" v-model="sageproduct.data.additionalCharges.plateChgCode">
    <hr>
</div>


<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Die Charge</label></h4>
    </div>
    <input type="number" v-model.number="sageproduct.data.additionalCharges.dieChg">
</div>


<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Die Charge Code</label></h4>
    </div>
    <input type="text" v-model="sageproduct.data.additionalCharges.dieChgCode">
    <hr>
</div>



<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Tooling Charge</label></h4>
    </div>
    <input type="number" v-model.number="sageproduct.data.additionalCharges.toolingChg">
</div>


<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Tooling Charge Code</label></h4>
    </div>
    <input type="text" v-model="sageproduct.data.additionalCharges.toolingChgCode">
    <hr>
</div>


<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Repeat Charge</label></h4>
    </div>
    <input type="number" v-model.number="sageproduct.data.additionalCharges.repeatChg">
</div>


<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Repeat Charge Code</label></h4>
    </div>
    <input type="text" v-model="sageproduct.data.additionalCharges.repeatChgCode">
    <hr>
</div>



<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Add Color Charge</label></h4>
    </div>
    <input type="number" v-model.number="sageproduct.data.additionalCharges.addClrChg">
</div>


<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Add Color Charge Code</label></h4>
    </div>
    <input type="text" v-model="sageproduct.data.additionalCharges.addClrChgCode">
    <hr>
</div>



<div class="mb-5" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Add Color Run Charges</label></h4>
    </div>
    <div v-for="(entry, entryindex) in sageproduct.data.additionalCharges.addClrRunChgs" class="mb-2">
        <input type="number" placeholder="Value" v-model.number="sageproduct.data.additionalCharges.addClrRunChgs[entryindex]">
        <a href="#appendentry" @click.prevent="sageproduct.data.additionalCharges.addClrRunChgs.push(0)" class="ml-1">Append</a>
        <a href="#removeentry" @click.prevent="sageproduct.data.additionalCharges.addClrRunChgs.splice(entryindex, 1)">Remove</a>
    </div>
</div>

<div class="mb-2" v-if="'additionalCharges' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Add Run Color Charge Code</label></h4>
    </div>
    <input type="text" v-model="sageproduct.data.additionalCharges.addClrRunChgCode">
    <hr>
</div>




<div class="mb-2" v-if="'isRecyclable' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.isRecyclable">
        Is Recyclable? &#8250; <a href="#removefield" @click.prevent="removeUpdateField('isRecyclable')">Remove Field</a>
    </label>
</div>

<div class="mb-2" v-if="'isEnvironmentallyFriendly' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.isEnvironmentallyFriendly">
        Is Environmental Friendly? &#8250; <a href="#removefield" @click.prevent="removeUpdateField('isEnvironmentallyFriendly')">Remove Field</a>
    </label>
</div>


<div class="mb-2" v-if="'isNewProd' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.isNewProd">
        Is New Product? &#8250; <a href="#removefield" @click.prevent="removeUpdateField('isNewProd')">Remove Field</a>
    </label>
</div>

<div class="mb-2" v-if="'showEndUsers' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.showEndUsers">
        Show End Users &#8250; <a href="#removefield" @click.prevent="removeUpdateField('showEndUsers')">Remove Field</a>
    </label>
</div>

<div class="mb-2" v-if="'exclusive' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.exclusive">
        Exclusive &#8250; <a href="#removefield" @click.prevent="removeUpdateField('exclusive')">Remove Field</a>
    </label>
</div>


<div class="mb-2" v-if="'notSuitable' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.notSuitable">
        Not Suitable &#8250; <a href="#removefield" @click.prevent="removeUpdateField('notSuitable')">Remove Field</a>
    </label>
</div>


<div class="mb-2" v-if="'isFood' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.isFood">
        is Food &#8250; <a href="#removefield" @click.prevent="removeUpdateField('isFood')">Remove Field</a>
    </label>
</div>

<div class="mb-2" v-if="'isClothing' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.isClothing">
        is Clothing &#8250; <a href="#removefield" @click.prevent="removeUpdateField('isClothing')">Remove Field</a>
    </label>
</div>

<div class="mb-2" v-if="'hazardous' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.hazardous">
        Hazardous &#8250; <a href="#removefield" @click.prevent="removeUpdateField('hazardous')">Remove Field</a>
    </label>
</div>


<div class="mb-2" v-if="'officiallyLicensed' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.officiallyLicensed">
        Officially Licensed &#8250; <a href="#removefield" @click.prevent="removeUpdateField('officiallyLicensed')">Remove Field</a>
    </label>
</div>


<div class="mb-2" v-if="'imprintLoc' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Imprint Loc</label>
        &#8250; <a href="#removefield" @click.prevent="removeUpdateField('imprintLoc')">Remove Field</a>
        </h4>
    </div>
    <input type="text" v-model="sageproduct.data.imprintLoc" class="full-width">
</div>

<div class="mb-2" v-if="'secondImprintLoc' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Second Imprint Loc</label>
        &#8250; <a href="#removefield" @click.prevent="removeUpdateField('secondImprintLoc')">Remove Field</a>
        </h4>

    </div>
    <input type="text" v-model="sageproduct.data.secondImprintLoc" class="full-width">
</div>

<div class="mb-2" v-if="'decorationMethod' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Decoration Method</label>
        &#8250; <a href="#removefield" @click.prevent="removeUpdateField('decorationMethod')">Remove Field</a>
        </h4>
    </div>
    <input type="text" v-model="sageproduct.data.decorationMethod" class="full-width">
</div>


<div class="mb-2" v-if="'noDecoration' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.noDecoration">
        No Decoration &#8250; <a href="#removefield" @click.prevent="removeUpdateField('noDecoration')">Remove Field</a>
    </label>
</div>


<div class="mb-2" v-if="'noDecorationOffered' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.noDecorationOffered">
        No Decoration Offered &#8250; <a href="#removefield" @click.prevent="removeUpdateField('noDecorationOffered')">Remove Field</a>
    </label>
</div>


<div class="mb-5" v-if="'pics' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Pictures</label>
        &#8250; <a href="#removefield" @click.prevent="removeUpdateField('pics')">Remove Field</a>
        </h4>
    </div>
    <div v-for="(entry, entryindex) in sageproduct.data.pics" class="mb-2">
        <div style="padding: 10px; border: 1px solid #ccc;">
            <label class="d-block full-width mb-1">Enter URL</label>
            <input type="text" v-model="entry.url" class="full-width mb-2">
            <a href="#choosephoto" @click.prevent="choosePhoto(entryindex)" class="mb-2 d-block">Choose Photo</a>
            <label class="d-block full-width mb-1">Enter INDEX</label>
            <input type="number" v-model.number="entry.index" class="mb-2">
            <label class="d-block full-width mb-1">Enter Caption</label>
            <textarea rows="2" v-model="entry.caption" class="full-width mb-2"></textarea>
            <div class="mb-2">
                <label>
                    <input type="checkbox"  :true-value="1" :false-value="0" v-model="entry.hasLogo">
                    Has logo?
                </label>
            </div>

            <a href="#appendentry" @click.prevent="sageproduct.data.pics.push({ index: sageproduct.data.pics.length+1, caption: '', url: '', haslogo: 0 })" class="ml-1">Append</a>
            <a href="#removeentry" @click.prevent="sageproduct.data.pics.splice(entryindex, 1)">Remove</a>
        </div>
    </div>

    <hr>
</div>



<div class="mb-2" v-if="'notPictured' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.notPictured">
        Not Pictured &#8250; <a href="#removefield" @click.prevent="removeUpdateField('notPictured')">Remove Field</a>
    </label>
</div>

<div class="mb-2" v-if="'madeInCountry' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Made in Country</label>
        &#8250; <a href="#removefield" @click.prevent="removeUpdateField('madeInCountry')">Remove Field</a>
        </h4>
    </div>
    <input type="text" v-model="sageproduct.data.madeInCountry" class="full-width">
</div>

<div class="mb-2" v-if="'assembledInCountry' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Assembeled in Country</label>
        &#8250; <a href="#removefield" @click.prevent="removeUpdateField('assembledInCountry')">Remove Field</a>
        </h4>
    </div>
    <input type="text" v-model="sageproduct.data.assembledInCountry" class="full-width">
</div>

<div class="mb-2" v-if="'decoratedInCountry' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Decorated in Country</label>
        &#8250; <a href="#removefield" @click.prevent="removeUpdateField('decoratedInCountry')">Remove Field</a>
        </h4>
    </div>
    <input type="text" v-model="sageproduct.data.decoratedInCountry" class="full-width">
</div>

<div class="mb-2" v-if="'complianceList' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Compliance List</label>
        &#8250; <a href="#removefield" @click.prevent="removeUpdateField('complianceList')">Remove Field</a>
        </h4>
    </div>
    <textarea class="full-width" rows="3" v-model="sageproduct.data.complianceList"></textarea>
</div>

<div class="mb-2" v-if="'warningLbl' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Warning Label</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('warningLbl')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.warningLbl" class="full-width">
</div>


<div class="mb-2" v-if="'complianceMemo' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Compliance Memo</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('complianceMemo')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.complianceMemo" class="full-width">
</div>

<div class="mb-2" v-if="'prodTimeLo' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Product Time Lo</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('prodTimeLo')">Remove Field</a></h4>
    </div>
    <input type="number" v-model.number="sageproduct.data.prodTimeLo" class="full-width">
</div>

<div class="mb-2" v-if="'prodTimeHi' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Product Time Hi</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('prodTimeHi')">Remove Field</a></h4>
    </div>
    <input type="number" v-model.number="sageproduct.data.prodTimeHi" class="full-width">
</div>


<div class="mb-2" v-if="'rushProdTimeLo' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Rush Time Lo</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('rushProdTimeLo')">Remove Field</a></h4>
    </div>
    <input type="number" v-model.number="sageproduct.data.rushProdTimeLo" class="full-width">
</div>

<div class="mb-2" v-if="'rushProdTimeHi' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Rush Time Hi</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('rushProdTimeHi')">Remove Field</a></h4>
    </div>
    <input type="number" v-model.number="sageproduct.data.rushProdTimeHi" class="full-width">
</div>


<div class="mb-2" v-if="'packaging' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Packaging</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('packaging')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.packaging" class="full-width">
</div>

<div class="mb-2" v-if="'cartonL' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Carton L</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('cartonL')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.cartonL" class="full-width">
</div>

<div class="mb-2" v-if="'cartonW' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Carton W</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('cartonW')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.cartonW" class="full-width">
</div>

<div class="mb-2" v-if="'cartonH' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Carton H</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('cartonH')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.cartonH" class="full-width">
</div>


<div class="mb-2" v-if="'weightPerCtn' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Weights Per Carton</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('weightPerCtn')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.weightPerCtn" class="full-width">
</div>



<div class="mb-2" v-if="'unitsPerCtn' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Units Per Carton</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('unitsPerCtn')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.unitsPerCtn" class="full-width">
</div>



<div class="mb-2" v-if="'shipPointCountry' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Ship point country</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('shipPointCountry')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.shipPointCountry" class="full-width">
</div>

<div class="mb-2" v-if="'shipPointZip' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Ship point zip</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('shipPointZip')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.shipPointZip" class="full-width">
</div>

<div class="mb-2" v-if="'comment' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Comments</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('comment')">Remove Field</a></h4>
    </div>
    <textarea rows="3" v-model="sageproduct.data.comment" class="full-width"></textarea>
</div>


<div class="mb-2" v-if="'verified' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.verified">
        Verified &#8250; <a href="#removefield" @click.prevent="removeUpdateField('verified')">Remove Field</a>
    </label>
</div>


<div class="mb-2" v-if="'catYear' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Cat Year</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('catYear')">Remove Field</a></h4>
    </div>
    <input type="number" v-model.number="sageproduct.data.catYear" class="full-width">
</div>

<div class="mb-2" v-if="'expirationDate' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Expiration Date</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('expirationDate')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.expirationDate" class="full-width">
</div>

<div class="mb-2" v-if="'inventoryOnHand' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Inventory Onhand</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('inventoryOnHand')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.inventoryOnHand" class="full-width">
</div>

<div class="mb-2" v-if="'inventoryLastUpdated' in sageproduct.data">
    <div class="mb-1">
        <h4 class="mb-2"><label>Expiration last updated</label> &#8250; <a href="#removefield" @click.prevent="removeUpdateField('inventoryLastUpdated')">Remove Field</a></h4>
    </div>
    <input type="text" v-model="sageproduct.data.inventoryLastUpdated" class="full-width">
</div>

<div class="mb-2" v-if="'discontinued' in sageproduct.data">
    <label>
        <input type="checkbox"  :true-value="1" :false-value="0" v-model="sageproduct.data.discontinued">
        Discontinued &#8250; <a href="#removefield" @click.prevent="removeUpdateField('discontinued')">Remove Field</a>
    </label>
</div>




<div v-if="!vdssage.loading && !sageProducts.length" class="notice notice-error">
    <p>This PRODUCT has no SAGE PRODUCT ID on it's variant in COLORS/STOCKSHAPES/COLORS+STOCKSHAPES.</p>
</div>




<div class="floating-button-save">
    <button class="button button-primary" @click.prevent="saveSageProduct" type="button" :disabled="sageproduct.submit.loading"
    >
        <span v-if="!sageproduct.submit.loading">Save Changes</span>
        <span v-else>Sending Request to SAGE...</span>
    </button>
</div>



<div v-if="getResSubmitted.length">
    <h2 class="wp-heading-inline">Sage Logs</h2>

    <div style="display: flex; flex-wrap: wrap; column-gap: 10px;">
        <div 
        style="flex: 0 0 15%; padding: 10px; border: 1px solid #ccc;"
        v-for="(entry, entryindex) in getResSubmitted"
        :key="`sage-log-${entryindex}`"
        class="mb-2">
            <h4 style="font-weight" class="mb-0">{{entry.itemNum}}</h4>
            <p
            class="mt-1"
            :style="[
                entry.ok ? { 'color': 'green' } : { 'color': 'red' }
            ]"
            >
                <span v-if="entry.ok">SUCCESS</span>
                <span v-else>FAILED</span>
            </p>
            <p class="mt-1" style="color: red;" v-if="entry.errors">{{ entry.errors }}</p>
        </div>
    </div>
</div>