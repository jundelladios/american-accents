
<div v-if="pcolors.input.product_id" class="aa-popup">

<div class="aa-content-wrap">
    <div class="aa-content md">
        <a href="javascript:void(0)" @click.prevent="resetColorsInputs({product_id: null, index: null})" class="aa-close">&times;</a>
        <h1 class="wp-heading-inline">
            <span>Product Colors</span>
            <strong v-if="pcolors.input.index!=null">(Editing...)</strong>
        </h1>

        <div class="mt-4 mb-4 row">
            <div class="col-md-4">
                <form @submit.prevent="saveProductColor" data-vv-scope="productcolor">

                    <div class="mb-2" v-if="!pcolors.input.iscolorimage">
                        <label class="d-block mb-2" for="color">Product Color</label>
                        <colorpicker 
                        v-model="pcolors.input.colorhex" 
                        v-validate="'required'"
                        name="colorhex"
                        data-vv-as="product hex color"></colorpicker>
                        <span class="v-error">{{errors.first('productcolor.colorhex')}}</span>
                        <span class="color-circle mt-2" v-if="pcolors.input.colorhex" :style="`background: ${pcolors.input.colorhex}; width: 30px; height: 30px; border-radius: 100%; display: inline-block;`"></span>
                    </div>

                    <div class="mb-2" v-else>
                        <a href="javascript: void(0)" class="button" @click.stop="chooseProductImageColor">Choose Color Image</a>
                        <input type="hidden" v-model="pcolors.input.colorimageurl"  v-validate="'required'" name="colorimageurl">
                        <span class="v-error">{{errors.first('productcolor.colorhex')}}</span>
                        <span class="color-circle mt-2 d-block" v-if="pcolors.input.colorimageurl" :style="`background: url(${pcolors.input.colorimageurl}) center no-repeat; background-size: cover; width: 30px; height: 30px; border-radius: 100%; display: inline-block;`"></span>
                    </div>

                    <div class="mb-2">
                        <label for="">
                            <input type="checkbox" v-model="pcolors.input.iscolorimage" :true-value="1" :false-value="0">
                            Use image for the color.
                        </label>
                    </div>

                    <div class="mb-2">
                        <label class="d-block mb-2" for="colordesc">Color Name</label>
                        <input type="text" v-model="pcolors.input.colorname" v-validate="'required'" data-vv-as="product color name" name="colorname">
                        <span class="v-error">{{errors.first('productcolor.colorname')}}</span>
                    </div>

                    <div class="mb-2">
                        <label>Image count: {{ pcolors.input.image.length }}</label>
                    </div>

                    <div v-if="pcolors.input.image.length" class="mb-4 mt-4 slick-admin">
                        <vue-slick-carousel ref="imgform" :key="pcolors.input.imgformkey" v-bind="colorcarouseloptions">
                            <div v-for="(pcolorimg, index) in pcolors.input.image" :key="`carousel-item-inputs-${index}`" class="p-1 mb-5 mt-5">
                                <a href="javascript:void(0)" @click.stop="choosecolorimage(index)" class="d-block link-img">
                                    <img v-if="pcolors.input.image[index].image" :src="pcolors.input.image[index].image" alt="" class="full-width">
                                    <img v-else src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder.png'; ?>" alt="" class="full-width">
                                </a>
                                <a v-if="pcolors.input.image[index].image" href="javascript:void(0)" @click.stop="pcolors.input.image[index].image=null;" class="mt-2 mb-2"><small>remove</small></a>
                                <input type="text" v-model="pcolors.input.image[index].title" class="full-width mt-2" placeholder="Image Alt Text">
                                <a href="javascript:void(0)" @click.stop="pcolors.input.image.splice(index, 1);" class="button mt-2">remove</a>
                            </div>
                        </vue-slick-carousel>
                    </div>

                    <div class="mb-2">
                        <a href="javascript:void(0)" 
                        @click.stop="addcolorimage" 
                        class="button">Add Image</a>
                        <small class="d-block">Note: Empty image will automatically removed after submission.</small>
                    </div>

                    <div class="mb-2">
                        <label class="d-block mb-2">Priority #</label>
                        <input type="number" v-model="pcolors.input.priority">
                    </div>

                    <div class="mb-2">
                        <button v-if="pcolors.input.index==null" type="submit" class="button button-primary">Add Color</button>
                        <button v-else type="submit" class="button button-primary">Save Color</button>
                        <a href="javascript: void(0)" class="button" @click.stop="resetColorsInputs({product_id:pcolors.input.product_id, index: null})">Cancel</a>
                    </div>

                </form>
            </div>

            <div class="col-md-8">

                <div v-if="!pcolors.loading" class="row">

                    <div v-for="(pcolor, index) in productcolorarrangement" :key="`pcolor-data-${index}`" class="col-md-4">
                        <div class="mb-4 p-2" style="border: 1px solid #e0e0e0;">
                            <div class="d-block mb-2">Priority #: {{ pcolor.priority }}</div>
                            <div class="d-block mb-2">Image count: {{ pcolor.objimage.length }}</div>
                            <div class="d-block">
                                <!-- <img v-if="pcolor.objimage.image" :src="pcolor.objimage.image" alt="pcolor.objimage.title" style="max-width: 80px; display: inline-block;">
                                <div style="display: inline-block">
                                    <span :style="`width: 40px; height: 40px; background: ${pcolor.colorhex}; display: block; border-radius: 100%;`"></span>
                                    <span style="display: block; font-size: 10px; width: 40px; text-align: center;">{{pcolor.colorname}}</span>
                                </div> -->
                                <div class="d-block mt-3 mb-3">
                                    <vue-slick-carousel :key="`pcolor-data-key-${pcolor.datakeyfinal}`" v-bind="colorcarouseloptions">
                                        <div v-for="(pimg, pcindex) in pcolor.objimage" :key="`pcolor-img-item-list-${pcindex}`" class="p-2">
                                            <img :src="pimg.image" :alt="pimg.title" class="full-width">
                                        </div>
                                    </vue-slick-carousel>
                                </div>
                                <div class="d-block">
                                    <span v-if="!pcolor.iscolorimage" :style="`width: 40px; height: 40px; background: ${pcolor.colorhex}; display: block; border-radius: 100%;`"></span>
                                    <span v-else :style="`width: 40px; height: 40px; background: url(${pcolor.colorimageurl}); display: block; border-radius: 100%;`"></span>
                                    <span style="display: block; font-size: 10px; width: 40px; text-align: center;">{{pcolor.colorname}}</span>
                                </div>
                            </div>
                            <div class="d-block mt-3 mb-2">

                                <a href="javascript: void(0)" @click.stop="resetColorsInputs({
                                    ...pcolor,
                                    product_id:pcolors.input.product_id, 
                                    index: pcolor.dataIndexer,
                                    image: [...pcolor.objimage]
                                })" class="button">Edit</a>

                                <a href="javascript: void(0)" @click.stop="removeproductColor(pcolor.hid, pcolor.dataIndexer)" class="button">Remove</a>
                            </div>
                        </div>
                    </div>

                    <div v-if="!pcolors.data.length" class="col-md-12">
                        <p style="text-align: center;">There is no product color.</p>
                    </div>

                </div>

                <div class="row" v-else>
                    <div class="col-md-12">
                        <p style="text-align: center;">Loading..</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

</div>