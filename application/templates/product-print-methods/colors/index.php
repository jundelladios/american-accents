
<div v-if="pcolors.input.product_print_method_id" class="aa-popup" :data-print-methodid="pcolors.input.product_print_method_id">

<div class="aa-content-wrap">
    <div class="aa-content md">
        <a href="javascript:void(0)" @click.prevent="resetColorsInputs({product_print_method_id: null, index: null})" class="aa-close">&times;</a>
        
        <h1 class="wp-heading-inline">
            <span>Colors for<br>
            
            <strong v-if="pcolorselectedcombo" :style="`color:${pcolorselectedcombo.productline.printmethod.method_hex};`">
                <span v-if="pcolorselectedcombo.allow_print_method_prefix">{{pcolorselectedcombo.productline.printmethod.method_prefix}}</span><span>{{pcolorselectedcombo.product.product_name}}</span>
            </strong>

            ({{ pcolors.pagination.metas.total }})

            </span>
            <strong v-if="pcolors.input.index!=null" class="d-block">Editing for {{ pcolors.input.colorname }}</strong>
        </h1>

        <small class="d-block mb-2">Note: product combo colors without images will be ignored in product display.</small>

        <!-- form here -->

        <div v-if="pcolorstate!='lists'" class="mt-4 mb-4 row">
            <div class="col-md-12">
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
                        <a href="javascript: void(0)" class="button" @click.stop="choosecombocolorimage">Choose Color Image</a>
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
                        <label class="d-block mb-2" for="colorname">Color Name:</label>
                        <input type="text" v-model="pcolors.input.colorname" v-validate="'required'" data-vv-as="product color name" name="colorname">
                        <span class="v-error">{{errors.first('productcolor.colorname')}}</span>
                    </div>

                    <div class="mb-2">
                        <label class="d-block mb-2" for="pantone">Pantone:</label>
                        <input type="text" v-model="pcolors.input.pantone">
                    </div>

                    <div class="mb-2">
                        <label for="isavailable">
                            <input type="checkbox" v-model="pcolors.input.isavailable" :true-value="1" :false-value="0">
                            This color swatch is visible
                        </label>
                    </div>

                    <div class="mb-2">
                        <label class="d-block mb-2">Priority #</label>
                        <input type="number" v-model="pcolors.input.priority">
                    </div>

                    <div class="mb-2">
                        <label class="d-block mb-2">Stock (0 if out of stock)</label>
                        <input type="number" v-model="pcolors.input.in_stock">
                    </div>

                    <div class="mb-2">
                        <label class="d-block mb-2">VDS SAGE PRODUCT ID</label>
                        <input type="text" v-model="pcolors.input.vdsproductid">
                    </div>
                    
                    <div class="mb-2">
                        <label class="d-block mb-2">VDS SAGE ITEM #</label>
                        <input type="text" v-model="pcolors.input.vdsid">
                    </div>

                    <hr class="mt-5">

                    <div>

                        <div class="mb-2">
                            <strong class="d-block">Main Gallery Images:</strong>
                            <p>Note: Empty image will automatically be removed after submission. You can drag and drop product main gallery images.</p>
                            <p v-if="!pcolors.input.hid">
                                <label>
                                    <input type="checkbox" v-model="pcolors.input.autoassignimg" :true-value="1" :false-value="null"> auto-assign main gallery images? 
                                </label>
                            </p>
                        </div>

                        <div class="mb-3">
                            <button type="button" @click="pcolors_toggleSelectKey('image', true)" class="button mr-2">Select All Images</button>
                            <button type="button" @click="pcolors_toggleSelectKey('image', false)" class="button mr-2">Unselect Images</button>
                            <button type="button" @click="pcolors_removeCheckedItems_('image', 'Are you sure you want to remove selected images?')" v-if="pcolors_getIsSelectedImage.length" class="button mr-2">Remove {{pcolors_getIsSelectedImage.length}} Images</button>
                            <button v-if="pcolors.input.colorname" type="button" @click="pullAnimatedMediasColors({
                                id: pcolorselectedcombo.hid,
                                color: pcolors.input.colorname,
                                type: 'main'
                            })" class="button mr-2">Pull animated medias</button>
                        </div>

                        <!-- <div class="mb-2">
                            <label>Image count: {{ pcolors.input.image.length }}</label>
                        </div> -->

                        <div v-if="pcolors.input.image && !pcolors.input.autoassignimg" class="mb-2 col-md-12">
                            <draggable 
                            v-model="pcolors.input.image" 
                            class="v-draggable"
                            tag="div" 
                            v-bind="vueDragOptions"
                            @start="imagedrag = true"
                            @end="imagedrag = false"
                            >
                                <transition-group type="transition" tag="div" class="row" :name="!imagedrag ? 'flip-list' : null">
                                    <div v-for="(pcolorimg, index) in pcolors.input.image" :key="`colors-carousel-item-inputs-${index}`" class="p-1 col-xl-4 col-lg-12 mb-4">
                                        
                                        <div style="position:absolute;top:15px;right:15px;">
                                            <input type="checkbox" 
                                            @input="() => $set(pcolors.input.image, index, {...pcolorimg, _isSelected: !pcolorimg._isSelected})" 
                                            :checked="pcolorimg._isSelected">
                                        </div>

                                        <a href="javascript:void(0)" @click.stop="choosecolorimage(index)" class="d-block link-img img-form-wrap mb-2">
                                            <template v-if="!pcolors?.input?.image?.[index]?.image">
                                                <img src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder.png'; ?>" alt="" class="full-width">
                                            </template>
                                            <template v-else>
                                                <template v-if="!pcolorimg?.type">
                                                    <img :src="pcolors.input.image[index].image" alt="" class="full-width">
                                                </template>
                                                <template v-else>
                                                    <img v-if="pcolorimg?.type != 'html'" :src="pcolors.input.image[index].image" alt="" class="full-width">
                                                    <div v-if="pcolorimg?.type == 'html'" class="anim-thumbnail">
                                                        <iframe :src="pcolors.input.image[index].image" class="full-height full-width" scrolling="no"></iframe>
                                                        <p class="text-center m-0">Click here to choose media</p>
                                                    </div>
                                                </template>
                                            </template>
                                        </a>
                                        
                                        <div class="mb-2">
                                            <input type="text" v-model="pcolors.input.image[index].title" placeholder="Enter Title" class="full-width" />
                                        </div>

                                        <div class="mb-2">
                                            <input type="number" v-model="pcolors.input.image[index].top" placeholder="Top Position (%)" class="full-width" />
                                        </div>

                                        <a href="javascript:void(0)" @click.stop="pcolors.input.image.splice(index, 1);" class="button mt-2">remove</a>
                                    </div>
                                </transition-group>
                            </draggable>
                        </div>

                        <div v-if="!pcolors.input.autoassignimg" class="mb-2">
                            <a href="javascript:void(0)" 
                            @click.stop="addcolorimage" 
                            class="button mb-2 button-secondary">Add main gallery images</a>
                        </div>

                    </div>

                    <hr class="mt-5">

                    
                    <div class="mb-2">
                        <strong class="d-block">TEMPLATES:</strong>
                        <p>Note: Empty template will automatically be removed after submission. You can drag and drop product templates.</p>
                    </div>

                    <div class="mb-3">
                        <button type="button" @click="pcolors_toggleSelectKey('templates', true)" class="button mr-2">Select All Templates</button>
                        <button type="button" @click="pcolors_toggleSelectKey('templates', false)" class="button mr-2">Unselect Templates</button>
                        <button type="button" @click="pcolors_removeCheckedItems_('templates', 'Are you sure you want to remove selected templates?')" v-if="pcolors_getIsSelectedTemplates.length" class="button mr-2">Remove {{pcolors_getIsSelectedTemplates.length}} Templates</button>
                    </div>

                    <draggable 
                    v-model="pcolors.input.templates" 
                    class="v-draggable"
                    tag="div" 
                    v-bind="vueDragOptions"
                    @start="templatedrag = true"
                    @end="templatedrag = false"
                    >
                        <transition-group type="transition" tag="div" class="mb-2 row col-md-12" :name="!templatedrag ? 'flip-list' : null">
                            <div class="col-xl-4 col-lg-12 col-sm-12 p-3 mb-3 v-drag-item" v-for="(dl, index) in pcolors.input.templates" :key="`download-${index}`">
                                <div class="pt-5 pb-3" style="border: 1px solid rgb(195, 195, 195);border-radius: 5px;position:relative;">
                                    
                                    <div style="position:absolute;top:15px;right:15px;">
                                        <input type="checkbox" 
                                        @input="() => $set(pcolors.input.templates, index, {...dl, _isSelected: !dl._isSelected})" 
                                        :checked="dl._isSelected">
                                    </div>

                                    <div v-if="dl.preview" class="col-md-12 text-center">
                                        <a class="mb-2" :href="dl.preview" target="_blank">
                                            <div style="width: 100%; height: 230px;" class="d-flex justify-content-center align-items-center">
                                                <img :src="dl.preview" class="full-width img-r">
                                            </div>
                                        </a>
                                    
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" readonly class="full-width" v-model="dl.link" placeholder="Document Link">
                                        <a class="button mt-2 mr-2" href="javascript:void(0)" @click.stop="selectTemplateLink(index)">select</a>
                                        <a class="button mt-2 button-danger" href="javascript:void(0)" @click.stop="pcolors.input.templates.splice(index, 1)">remove</a>
                                    </div>
                                </div>
                            </div>
                        </transition-group>
                    </draggable>

                    <div class="mb-2">
                        <a class="button button-secondary" @click.stop="addTemplates">Add Template</a>
                    </div>


                    <hr class="mt-5">


                    <div class="mb-2">
                        <strong class="d-block">IDEA GALLERIES:</strong>
                        <p>Note: Empty idea gallery images will automatically be removed after submission, You can drag and drop product idea galleries images.</p>
                        <p v-if="!pcolors.input.hid">
                            <label>
                                <input type="checkbox" v-model="pcolors.input.autoassignidea" :true-value="1" :false-value="null"> auto-assign idea gallery images? 
                            </label>
                        </p>
                    </div>

                    <div class="mb-3">
                        <button type="button" @click="pcolors_toggleSelectKey('idea_galleries', true)" class="button mr-2">Select All Idea Gallery</button>
                        <button type="button" @click="pcolors_toggleSelectKey('idea_galleries', false)" class="button mr-2">Unselect Idea Gallery</button>
                        <button type="button" @click="pcolors_removeCheckedItems_('idea_galleries', 'Are you sure you want to remove selected idea gallery?')" v-if="pcolors_getIsSelectedIdeaGallery.length" class="button mr-2">Remove {{pcolors_getIsSelectedIdeaGallery.length}} Idea Gallery</button>
                        <button v-if="pcolors.input.colorname" type="button" @click="pullAnimatedMediasColors({
                            id: pcolorselectedcombo.hid,
                            color: pcolors.input.colorname,
                            type: 'idg'
                        })" class="button mr-2">Pull animated medias</button>
                    </div>

                    <div v-if="pcolors.input.idea_galleries && !pcolors.input.autoassignidea" class="mb-2 col-md-12">
                        <draggable 
                        v-model="pcolors.input.idea_galleries" 
                        class="v-draggable"
                        tag="div" 
                        v-bind="vueDragOptions"
                        @start="ideagallerydrag = true"
                        @end="ideagallerydrag = false"
                        >
                            <transition-group type="transition" tag="div" class="row" :name="!ideagallerydrag ? 'flip-list' : null">
                                <div v-for="(pcolorimg, index) in pcolors.input.idea_galleries" :key="`carousel-item-inputs-${index}`" class="p-1 col-xl-4 col-lg-12 mb-4">
                                    
                                    <div style="position:absolute;top:15px;right:15px;">
                                        <input type="checkbox" 
                                        @input="() => $set(pcolors.input.idea_galleries, index, {...pcolorimg, _isSelected: !pcolorimg._isSelected})" 
                                        :checked="pcolorimg._isSelected">
                                    </div>

                                    <a href="javascript:void(0)" @click.stop="chooseideagalleryimage(index)" class="d-block link-img img-form-wrap mb-2">
                                        <template v-if="!pcolors?.input?.idea_galleries?.[index]?.image">
                                            <img src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder.png'; ?>" alt="" class="full-width">
                                        </template>
                                        <template v-else>
                                            <template v-if="!pcolorimg?.type">
                                                <img :src="pcolors.input.idea_galleries[index].image" alt="" class="full-width">
                                            </template>
                                            <template v-else>
                                                <img v-if="pcolorimg?.type != 'html'" :src="pcolors.input.idea_galleries[index].image" alt="" class="full-width">
                                                <div v-if="pcolorimg?.type == 'html'" class="anim-thumbnail">
                                                    <iframe :src="pcolors.input.idea_galleries[index].image" class="full-height full-width" scrolling="no"></iframe>
                                                    <p class="text-center m-0">Click here to choose media</p>
                                                </div>
                                            </template>
                                        </template>
                                    </a>

                                    <div v-if="!pcolors.input.idea_galleries[index].usecurfile" class="mb-2">
                                        <input type="text" readonly placeholder="Download Link URL" v-model="pcolors.input.idea_galleries[index].downloadLink" class="full-width mb-2">
                                        <a href="javascript:void(0)" @click.stop="ideagallerydownloadfileselect(index)" class="button mb-2">Select File</a>
                                    </div>

                                    <label for="per_thousand" class="mb-2 d-block"> 
                                    <input 
                                    v-model="pcolors.input.idea_galleries[index].usecurfile" 
                                    type="checkbox" 
                                    value="per_thousand"
                                    :true-value="1"
                                    :false-value="0">
                                    Use current photo for the download link url.</label>

                                    <div class="mb-2">
                                        <input type="text" v-model="pcolors.input.idea_galleries[index].text" placeholder="Enter Title" class="full-width" />
                                    </div>

                                    <div class="mb-2">
                                        <input type="number" v-model="pcolors.input.idea_galleries[index].top" placeholder="Top Position  (%)" class="full-width" />
                                    </div>

                                    <a href="javascript:void(0)" @click.stop="pcolors.input.idea_galleries.splice(index, 1);" class="button mt-2">remove</a>
                                </div>
                            </transition-group>
                        </draggable>
                    </div>

                    <div class="mb-2" v-if="!pcolors.input.autoassignidea">
                        <a class="button button-secondary" @click.stop="addIdeaGallery">Add Idea Gallery Image</a>
                    </div>

                    <hr class="mt-5">


                    <div class="mt-5 mt-3 floating-button-save">
                        <button v-if="pcolors.input.id==null" type="submit" class="button button-primary">Add Color</button>
                        <button v-else type="submit" class="button button-primary">Save Color</button>
                        <a href="javascript: void(0)" class="button" @click.stop="resetColorsInputs({product_print_method_id:pcolors.input.product_print_method_id, index: null}); pcolorstate='lists'">View Colors</a>
                    </div>

                </form>
            </div>

        </div>




        <!-- lists here. -->


        <div v-else>
            
            <div class="row mt-3 mb-3">
                <div class="col-md-6">
                    <a href="javascript:void(0)" @click.stop="resetColorsInputs({product_print_method_id:pcolors.input.product_print_method_id, index: null}); pcolorstate='form'" class="button">Add Color</a>
                </div>
                <div class="col-md-6" v-if="!colors_collections.loading">
                    <div v-if="colors_collections.data.length" class="d-flex">
                        Generate color from color collection: 
                        <select class="ml-1" v-model="generateCollection">
                            <option v-for="ccollection in colors_collections.data" :value="ccollection.hid">{{ ccollection.title }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6" v-else>
                    Loading...
                </div>
            </div>

            <div class="mb-3">
                <button type="button" @click="selectAll_" class="button mr-2">Select All</button>
                <button type="button" @click="unselectAll_" class="button mr-2">Unselect</button>
                <button type="button" @click="removeCheckedItems_" v-if="getIsSelectedColor.length" class="button mr-2">Remove ({{getIsSelectedColor.length}})</button>
            </div>

            <div class="mb-3">
                <form @submit.prevent="searchEntryColorQueryFilter" style="text-align:right;">
                    <input placeholder="Search" type="text" v-model="pcolors.query" />
                </form>
            </div>

            <div class="row" v-if="pcolors.loading">
                <div class="col-md-12">
                    <p style="text-align: center;">Loading..</p>
                </div>
            </div>

            <div class="row" v-else>
                <div v-if="!pcolors.data.length" class="col-md-12">
                    <p style="text-align: center;">There is no product color.</p>
                </div>

                <div v-else class="col-md-12 mt-3 row">

                    <div v-for="(pcolor, index) in pcolors.data" :key="`pcolor-data-${index}`" class="col-md-4">
                        <div class="mb-4 p-2" style="border: 1px solid #e0e0e0;">

                            <div style="float:right;">
                                <input type="checkbox" 
                                @input="() => $set(pcolors.data, index, {...pcolor, _isSelected: !pcolor._isSelected})" 
                                :checked="pcolor._isSelected">
                            </div>

                            <div class="d-block mb-2">Priority #: {{ pcolor.priority }} ({{ pcolor.isavailable ? 'Available' : 'Not Available' }})</div>
                            <div class="d-block mb-2">Images: {{ pcolor.imagedata.length }}</div>
                            <div class="d-block mb-2">Templates: {{ pcolor.counttemplates }}</div>
                            <div class="d-block mb-2">VDS SAGE PRODUCT ID: {{ pcolor.vdsproductid }}</div>
                            <div class="d-block mb-2">VDS SAGE ITEM #: {{ pcolor.vdsid }}</div>
                            <div class="d-block mb-2">STOCK: {{ pcolor.in_stock }} 
                                <span v-if="pcolor.in_stock>0" style="color:green;font-weight:bold">(In Stock)</span>
                                <span v-else style="color:red;font-weight:bold">(Out of Stock)</span>
                            </div>

                            <div class="d-block">
                                <div class="d-block">
                                    <span v-if="!pcolor.iscolorimage" :style="`width: 40px; height: 40px; background: ${pcolor.colorhex}; display: block; border-radius: 100%;`"></span>
                                    <span v-else :style="`width: 40px; height: 40px; background: url(${pcolor.colorimageurl}); display: block; border-radius: 100%;`"></span>
                                    <span class="mt-2" style="display: block; font-size: 10px; width: 40px; text-align: center;line-height:10px;">{{pcolor.colorname}}</span>
                                </div>
                            </div>
                            <div class="d-block mt-3 mb-2">

                                <a href="javascript: void(0)" @click.stop="resetColorsInputs({
                                    ...pcolor,
                                    id: pcolor.hid,
                                    product_print_method_id:pcolors.input.product_print_method_id, 
                                    index: index,
                                    image: [...pcolor.imagedata],
                                    idea_galleries: [...pcolor.ideagallerydata],
                                    templates: [...pcolor.templatedata]
                                }); pcolorstate='form';" class="button">Edit</a>

                                <a href="javascript: void(0)" @click.stop="removeproductColor(pcolor.hid, index)" class="button">Remove</a>
                            </div>
                        </div>
                    </div>

                    <p v-if="pcolors.pagination.metas.next_page_url" style="text-align: center;width:100%;">
                        <button v-if="!pcolors.loadingNext" @click="getColorNextButton" class="button primary">Load More</button>
                        <span v-else>Loading...</span>
                    </p>

                </div>
                
            </div>

        </div>

        
    </div>
</div>

</div>